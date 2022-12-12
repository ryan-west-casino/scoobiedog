@foreach($data['routes'] as $route)
    <p>Route: <a href="/{{ $route->uri }}">/{{ $route->uri }}</a></p>
@endforeach