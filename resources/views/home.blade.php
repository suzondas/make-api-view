@extends('layouts.app')
@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="">
            <a href="{{ url('/newApi') }}">
                <button class="btn btn-success">Make New API</button>
            </a>
            <br>
            <br>
            <h2>List of APIs</h2>
            <table class="table table-bordered table-striped">
                <tr class="bg-primary text-white">
                    <th>Sl</th>
                    <th>Name of API</th>
                    <th>URL</th>
                    <th>Type</th>
                    <th>Request Method</th>
                    <th colspan="3">Action</th>
                </tr>
                @foreach($apis as $key=>$value)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$value->name}}</td>
                        <td>{{$value->url}}</td>
                        <td>{{$value->type}}</td>
                        <td>{{$value->request_type}}</td>
                        <td><a href="{{url('/runApi').'/'.$value->id}}">Run</a></td>
                        <td><a href="{{url('/editApi').'/'.$value->id}}">Edit</a></td>
                        <td>
                            <a href="{{url('/deleteApi').'/'.$value->id}}">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
