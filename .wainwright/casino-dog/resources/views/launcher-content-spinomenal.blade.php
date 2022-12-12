@php
    if(!isset($_GET['rgsUrl'])) {

        $url = "g?".$_SERVER['QUERY_STRING'].'&'.$game_content['query'];
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: $url");
    }
@endphp

@if(isset($_GET['rgsUrl']))
<base href="https://wainwrighted.herokuapp.com/https://cdn-live.spinomenal.com/external_components/">

    {!! $game_content['html'] !!}
@else

@endif