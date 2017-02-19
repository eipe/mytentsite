@extends('welcome')
@section('content')
    <div class="page page-allow-overflow">
        <div class="row"><br/></div>
        <div class="row">
            <div class="columns text-center">
                <h4>Dashboard</h4>
            </div>
        </div>
        <div class="row">
            <div class="small-4 large-2 columns">
                @include('admin.menu')
            </div>

            <div class="small-6 large-10 columns">
                <ul>
                    <li>Apdex: {{$status->apdex}}</li>
                    <li>- redirect: {{$status->timings->redirect}}</li>
                    <li>- namelookup: {{$status->timings->namelookup}}</li>
                    <li>- connection: {{$status->timings->connection}}</li>
                    <li>- handshake: {{$status->timings->handshake}}</li>
                    <li>- response: {{$status->timings->response}}</li>
                    <li>- total: {{$status->timings->total}}</li>
                </ul>
            </div>
        </div>
    </div>
    <br><br>
@endsection