<?php

namespace App\Http\Controllers;

use App\Models\Apis;
use App\Models\Keys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Ramsey\Uuid\Type\Integer;


class ApiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function newApi()
    {
//        Reference Apis required for fetching key's Value from another Api hit
        $reference_apis = Apis::with('keys')->where(['user_id' => Auth::id()])->get(['id', 'name']);
        return view('api/newApi')->with('reference_apis', $reference_apis);
    }

    /**
     * @param Request $request holds new API record
     * @return \Illuminate\Http\JsonResponse whether New API is saved or not
     */
    public function saveApi(Request $request)
    {

        $data = json_decode($request->getContent(), true);//converting json request to php array
//        return response()->json($data['keys']);

        /*Saving New API*/
        try {
            $newApi = new Apis();
            $newApi->name = $data["name"];
            $newApi->url = $data["url"];
            $newApi->type = $data["type"];
            $newApi->request_type = $data["request_type"];
            $newApi->user_id = Auth::id();
            $newApi->created_at = now();
            $newApi->updated_at = now();
            $newApi->save();
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        /*Saving Keys for New API created with inherited newApi Id*/
        if (!empty($data['keys'])) { //checking if keys array is not empty
            try {
                $keysData = [];
                for ($i = 0; $i < sizeof($data['keys']); $i++) {
                    //setting keys table's columns values
                    array_push($keysData, [
                        'key' => $data['keys'][$i]['key'],
                        'value' => $data['keys'][$i]['value'],
                        'option' => $data['keys'][$i]['option'],
                        //@todo: I'm typecasting int to input value and checking if returns 0 then making it null as DB returns err for empty string
//                        'reference_api_id' => $data['keys'][$i]['reference_api_id'],
                        'reference_api_id' => (int)$data['keys'][$i]['reference_api_id'] == 0 ? null : (int)$data['keys'][$i]['reference_api_id'],
                        'reference_api_response_key' => $data['keys'][$i]['reference_api_response_key'],
                        'apis_id' => $newApi->id,
                        'created_at' => now(),
                        'updated_at' => now()]);
                }

//                return response()->json($keysData);

                Keys::insert($keysData);
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }
        }
        return response()->json('API created!', 200);
    }

    /**
     * @param Integer $id API id to edit
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function editApi($id)
    {
        return view('api/editApi')->with('id', $id);
    }

    /**
     * @param Integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getApi($id)
    {
        $apiData = Apis::with('keys')->find($id);
        $reference_apis = Apis::with('keys')->where(['user_id' => Auth::id()])->whereNotIn('id', [$id])->get(['id', 'name']);
        return response()->json(['apiData' => $apiData, 'reference_apis' => $reference_apis]);
    }

    /**
     * @param Request $request
     * @param Integer $id API id to update
     * @return \Illuminate\Http\JsonResponse Updated or not
     */
    public function updateApi(Request $request, $id)
    {
        $data = json_decode($request->getContent(), true);//converting json request to php array

        try {
            $newApi = Apis::find($id);
            $newApi->name = $data["name"];
            $newApi->url = $data["url"];
            $newApi->type = $data["type"];
            $newApi->request_type = $data["request_type"];
            $newApi->updated_at = now();
            $newApi->save();

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        /*Deleting Existing Rows in Batch*/
        Keys::where(['apis_id' => $newApi->id])->delete();

        /*Saving Keys in batch*/
        if (!empty($data['keys'])) { //checking if keys array is not empty
            try {
                $keysData = [];
                for ($i = 0; $i < sizeof($data['keys']); $i++) {
                    //setting keys table's columns values
                    array_push($keysData, [
                        'key' => $data['keys'][$i]['key'],
                        'value' => $data['keys'][$i]['value'],
                        'option' => $data['keys'][$i]['option'],
                        //@todo: I'm typecasting int to input value and checking if returns 0 then making it null as DB returns err for empty string
//                        'reference_api_id' => $data['keys'][$i]['reference_api_id'],
                        'reference_api_id' => (int)$data['keys'][$i]['reference_api_id'] == 0 ? null : (int)$data['keys'][$i]['reference_api_id'],
                        'reference_api_response_key' => $data['keys'][$i]['reference_api_response_key'],
                        'apis_id' => $newApi->id,
                        'created_at' => now(),
                        'updated_at' => now()]);
                }
                Keys::insert($keysData);
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }
        }
        return response()->json('Api Updated', 200);
    }

    /**
     * @param Integer $id API id to delete
     * @return \Illuminate\Http\RedirectResponse api is deleted or not
     */
    public function deleteApi($id)
    {
        try {
            Apis::destroy($id);
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => $e->getMessage()]);
        }
        return redirect()->route('home');
    }

    /**
     * @param Integer $id API id to run
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function runApi($id)
    {
        return view('api/runApi')->with(['id' => $id]);
    }

    /**
     * @param Integer $id API id to check whether it has key(s) which value(s) to be provided at runtime
     * @return \Illuminate\Http\JsonResponse API response or parameter(s) value(s) to be provided for running API
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @description checking if API has any key which value should be provided during runtime.
     * If true then returning to View for asking value(s) else continue to run API
     */
    public function checkUserGivenValue($id)
    {
        $api = Apis::with('keys')->find($id);
        $params = [];//container for user providable key values
        //Iterating array of keys
        for ($i = 0; $i < sizeof($api->keys); $i++) {
            $item = $api->keys[$i];
            if (isset($item->key)) {//checking if key is not empty
                if ($item->option == 1) {//1 is set for 'Ask Value at Runtime' option value
                    $params[] = (object)['id' => $item->id, 'key' => $item->key];//push id and key combined object to params
                }
            }
        }

        if (empty($params)) {
            return $this->fetchApiData($id);//if there is no user providable key value then continue to run api
        } else {
            //else return parameter for asking values
            return response()->json(['userGivenValue' => true, 'params' => $params]);
        }
    }

    /**
     * @param integer $id
     * @param null | array $submitUserGivenValues
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @description Run API  with keys-values using GuzzleHttp
     *
     */
    public function fetchApiData($id, $submitUserGivenValues = null)
    {
        $returnResponse = [];
        $returnResponse['err'] = false;
        $returnResponse['data'] = '';

        $api = Apis::with('keys')->find($id);

        /*Setting GuzzleHttp request configuration*/
        $url = $api->url;
        //setting GET/POST config parameter for GuzzleHttp
        $requestMethod = $api->request_type;
        $api->request_type == 'get' ? $clietRequestOption = 'query' : $clietRequestOption = 'form_params';
        $params = [];
        for ($i = 0; $i < sizeof($api->keys); $i++) {
            $item = $api->keys[$i];

            //if value is not derived from another API response and  key, value is not empty
            if (isset($item->key, $item->value) && $item->option == 0) {
                $params[$item->key] = $item->value;
            }

            /* if API has any key(s) which value has given at run time */
            if (isset($item->key) && $item->option == 1) {
                //if request is from submitUserGivenValues
                if ($submitUserGivenValues) {
                    $valueForThisItem = "";
                    for ($j = 0; $j < sizeof($submitUserGivenValues); $j++) {
                        if ($submitUserGivenValues[$j]["id"] == $item->id) {
                            $valueForThisItem = $submitUserGivenValues[$j]["value"];
                            break;
                        }
                    }
                    $params[$item->key] = $valueForThisItem;
                } else {
                    //quite impossible to run this block :)
                    // as this method will be called differently with $submitUserGivenValues =true for usergivenvalue
                    $params[$item->key] = "";//precaution
                }

            }

            /* if value is derived from another API response and
             *  key, reference API ID, reference API response key is not empty
             */
            if (isset($item->key, $item->reference_api_id, $item->reference_api_response_key) && $item->option == 2) {
                //fetching value from another API from method fetchReferenceApiData
                $refApiResVal = $this->fetchReferenceApiData($item->reference_api_id, $item->reference_api_response_key);
                if ($refApiResVal) {
                    $params[$item->key] = $refApiResVal;
                } else {
                    $params[$item->key] = "";
                }
            }
        }
        /*Ends*/
//        return response()->json($params);

        /*GuzzleHTTP Request*/
        try {
            $client = new Client();
            //returning response from GuzzleHTTP request with config parameter
            $res = $client->request($requestMethod, $url, [$clietRequestOption => $params]);

            if ($res->getStatusCode() == 200) {//if response is ok
                $response_data = $res->getBody()->getContents();
                return response()->json(['userGivenValue' => false, 'data' => $response_data]);
            } else {
                return response()->json("Could not get response body", 500);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
        /*Ends*/
    }

    /**
     * @param integer $id
     * @param string $key
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchReferenceApiData($id, $key)
    {
        $api = Apis::with('keys')->find($id);
        if ($api == null) {
            return false;
        }
        $url = $api->url;
        $params = [];
        for ($i = 0; $i < sizeof($api->keys); $i++) {
            $item = $api->keys[$i];
            if (isset($item->key)) {
                $params[$item->key] = $item->value;
            }
        }

        $requestMethod = $api->request_type;
        $requestMethod == 'get' ? $clietRequestOption = 'query' : $clietRequestOption = 'form_params';
        try {
            $client = new Client();
            $res = $client->request($requestMethod, $url, [$clietRequestOption => $params]);

            if ($res->getStatusCode() == 200) {
                $response_data = $res->getBody()->getContents();
//                get the value of $key given in api key declaration
                if (isset((json_decode($response_data))->$key)) {
                    return (json_decode($response_data))->$key;
                }
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function submitUserGivenValues($id, Request $request)
    {
        $params = json_decode($request->getContent(), true);//converting json request to php array
        return $this->fetchApiData($id, $params);//if there is user providable key value
//        return response()->json($data);

    }
}
