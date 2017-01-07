@extends('welcome')
@section('content')
    <div class="page page-allow-overflow">
        <div class="row"><br/></div>
        <div class="row">
            <div class="columns text-center">
                <h4>Approve tent sites</h4>
            </div>
        </div>
        <div class="row">
            <div class="small-4 large-2 columns">
                @include('admin.menu')
            </div>

            <div class="small-6 large-10 columns">
                @include('admin.unapproved')
            </div>
        </div>
    </div>
    <br><br>
    @include('page.footer')
@endsection