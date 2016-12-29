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
            <div class="shrink columns text-left">
                Menu
                <ul class="vertical text-left">
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Tent sites
                            @if($tentsites_count > 0)
                                <span class="badge">{{$tentsites_count}}</span>
                            @endif
                        </a></li>
                    <li><a href="#">Statistics</a></li>
                    <li><a href="#">Users</a></li>
                </ul>
            </div>
            <div class="columns-large-1 text-center">
                @include('admin.unapproved')

            </div>
        </div>
    </div>
    </div>
    <br><br>
    @include('page.footer')
@endsection