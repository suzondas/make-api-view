@extends('layouts.app')
@section('content')
    <div class="container" id="editApi">
        <div class="row justify-content-center">
            <div v-if="!dataLoaded">
                Loading: <i class="spinner-border text-danger"></i>
            </div>
            <div v-if="dataLoaded" class="col-md-12">

                {{--If there is any validation errors while submitting data--}}
                <div v-if="validationErrors.length" class="text-danger bg-warning">
                    <ol>
                        <li v-for="item in validationErrors">@{{ item }}</li>
                    </ol>
                </div>
                {{--Ends--}}

                <div class="card">
                    <div class="card-header">Create New API</div>
                    <div class="card-body">
                        API URL: <input type="text" class="form-control w-50" v-model="apiData.url"/><br>
                        {{--Type: <input type="radio" v-model="apiData.type" value="token"/> Token &nbsp;&nbsp;--}}
                        {{--<input type="radio" v-model="apiData.type" value="resource"/>--}}
                        {{--Resource<br><br>--}}
                        {{--Parameter:<br>--}}
                        <table class="table table-bordered table-striped">
                            <tr class="bg-primary">
                                <th>Sl</th>
                                <th>Key</th>
                                <th>Value</th>
                                <th>Option</th>
                                <th>Action</th>
                            </tr>
                            <tr v-for="(item, index) in apiData.keys">
                                <td>@{{index+1}}</td>
                                <td><input type="text" v-model="item.key"/>
                                </td>
                                <td><input type="text" v-model="item.value" :disabled="item.option!=0"></td>
                                <td>
                                    <select v-model="item.option" @change="item.value=''">
                                        <option value="0">Select
                                        </option>
                                        <option value="1">Ask Value at
                                            Runtime
                                        </option>
                                        <option value="2">Get Value
                                            from API Response
                                        </option>
                                    </select>
                                    <div class="mt-1" v-if="item.option==2">
                                        Select Saved API Name:<br>
                                        <select v-model="item.reference_api_id">
                                            <option value="">Select</option>
                                            <option v-for="i in reference_apis" v-bind:value="i.id">
                                                @{{i.name}}
                                            </option>
                                        </select><br><br>
                                        Type API response Key:<br>
                                        <input type="text" v-model="item.reference_api_response_key"
                                               :disabled="item.reference_api_id==''||item.reference_api_id==null"/>
                                    </div>
                                </td>
                                <td><input type="button" class="btn btn-danger" value="Delete"
                                           @click="apiData.keys.splice(index,1)"/></td>
                            </tr>
                        </table>
                        <input type="button" @click="newParameter" value="+Add Parameter"/>
                        <br><br>
                        Request Method:
                        Type: <input type="radio" v-model="apiData.request_type" value="get"/> GET &nbsp;&nbsp;
                        <input type="radio" v-model="apiData.request_type" value="post"/>POST<br><br>
                        Name of this Api to save: <input type="text" class="form-control w-50"
                                                         v-model="apiData.name"/><br><br>
                    </div>
                </div>
                <div class="card-footer">
                    <input type="button" class="btn btn-success" value="Update" @click="validate"/>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script>var apiId = '{{$id}}'</script>
    <script src="{{ asset('js/editApi.js') }}" type="module"></script>
@stop
