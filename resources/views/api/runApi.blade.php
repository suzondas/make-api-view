@extends('layouts.app')
@section('content')
    <div class="container" id="runApi">
        <div class="row justify-content-center">
            <div v-if="!dataLoaded">
                Loading: <i class="spinner-border text-danger"></i>
            </div>
            <div class="col-md-12" v-if="dataLoaded">

                {{--If user set any key's value should be given at runtime--}}
                <div v-if="checkUserGivenValue===true">
                    Provide Parameter Data to Run this API:
                    <table class="table table-bordered">
                        <tr>
                            <td>Key</td>
                            <td>Value</td>
                        </tr>
                        <tr v-for="item in params">
                            <td>@{{ item.key }}</td>
                            <td><input type="text" v-model="item.value"/></td>
                        </tr>
                    </table>
                    <input type="button" value="Submit" @click="submitUserGivenValues"/>
                </div>
                {{--Ends--}}

                {{--Else--}}
                <div v-if="checkUserGivenValue===false">
                    Response:
                    <table class="table table-bordered">
                        <tr class="bg-primary text-white font-weight-bold">
                            <td>Sl</td>
                            <td>Key</td>
                            <td>Value</td>
                        </tr>
                        {{--@todo: Should use thrid party plugin for showing JSON value in nested table format--}}
                        <tr v-for="(value, key, index) in data">
                            <td class="font-weight-bold">@{{ index+1 }}</td>
                            <td class="font-weight-bold">@{{ key }}</td>
                            <td class="bg-white">@{{ value }}</td>
                        </tr>
                    </table>
                </div>
                {{--End--}}
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script type="text/javascript">var id = "<?=$id ?>"</script>
    <script src="{{ asset('js/runApi.js') }}" type="module"></script>
@stop
