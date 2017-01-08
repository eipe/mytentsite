
    Menu
    <ul class="vertical text-left">
        <li><a href="{{ url('/admin') }}">Dashboard</a></li>
        <li><a href="{{ url('/admin/logs') }}">Logs</a></li>
        <li><a href="{{ url('/admin/tentsites') }}">Tent sites
                @if($tentsites_count > 0)
                    <span class="badge">{{$tentsites_count}}</span>
                @endif
            </a></li>
        <li><a href="#">Statistics</a></li>
        <li><a href="#">Users</a></li>
    </ul>
