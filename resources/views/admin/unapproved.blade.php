
<div class="callout">
    <table class="hover">
      <thead>
        <tr>
          <th width="20">Id</th>
          <th>Caption</th>
          <th width="50">Photo</th>

          <th width="50">Map</th>
          <th width="50">Handle</th>
        </tr>
      </thead>
      <tbody>
      @foreach($tentsites as $tentsite)
          <tr>
              <td>{{$tentsite->id}}</td>
              <td>{{$tentsite->caption}}</td>
              <td></td>
              <td><a href="https://google.com/maps/?q={{$tentsite->latitude}},{{$tentsite->longitude}}" target="_blank">Map</a></td>
              <td>
                  <a href="{{ url('/admin/approve', [$tentsite->id]) }}" class="badge success">
                      <i class="fa fa-thumbs-up"></i></a>
                  <a href="{{ url('/admin/deny', [$tentsite->id]) }}" class="badge alert">
                      <i class="fa fa-thumbs-down"></i></a>
              </td>
          </tr>
      @endforeach
      </tbody>
    </table>

</div>
