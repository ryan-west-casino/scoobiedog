<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
        background-color: #111315;
    }
  </style>
</head>
<body>
<div class="max-w-7xl mx-auto sm:px-2 lg:px-4">
    <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">

<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Games List') }}
        </h2>
    </x-slot>
    <div class="max-w-fluid mx-auto py-4 px-2 sm:px-6 lg:px-8">
<div class="p-4 flex justify-center items-center flex-wrap">
    @foreach($games_list['providers'] as $provider)
    <a href="gameslist?page=1&filter=provider&filter_value={{ $provider['slug'] }}">
  <span class="inline-flex items-center m-2 px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded-full text-sm font-semibold text-gray-600">
	<img class="w-4 h-4 m-1" src="https://wainwrighted.herokuapp.com/https://cdn2.softswiss.net/logos/providers_small/black/{{ $provider['image_tag'] }}.svg" />
	  {{ $provider['slug'] }} <span class="text-purple-700"><small> / {{ $provider['game_count'] }}</small></span>
	</span>
  </span>
</a>
  @endforeach
</div>
<div class="py-2 px-4 md:px-2 flex items-center justify-center grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-5">
    @foreach ($games_list['games'] as $game)
        <a href="{{ $game->link($game->slug) }}">
            <div class="group relative bg-gray-200 hover:bg-gray-300 rounded-2xl shadow-md cursor-pointer">
                <div class="mb-1">
                    <div class="image block w-full h-full items-center object-cover justify-center">
                        <div class="transition duration-300 ease absolute opacity-0 group-hover:opacity-100 z-10 items-center p-2">
                        <button class="inline-flex items-center px-4 py-2 bg-purple-700 border border-transparent rounded-md font-semibold text-xs text-gray-200 uppercase tracking-widest hover:bg-purple-900 active:bg-purple-800 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-1">
                            Play
                        </button>
                    </div>
                        <img class="object-cover rounded-t-lg w-full opacity-100 group-hover:opacity-25" src="{{ $game->image }}" loading="lazy">
                    </div>
                    <div class="py-2 px-3">
                    <p class="text-xs font-semibold text-gray-800 my-1 game-name">{{$game->name}}</p>
                    <div class="flex space-x-2 text-gray-400 text-xs">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    <p>{{$game->provider}}</p>
                    </div>
                </div>
                </div>
            </div>
        </a>
    @endforeach
    <div class="container-fluid mx-auto mt-10 mb-20" style="max-width:1250px;">
        {!! $games_list['games']->appends(Request::only(['filter','filter_value']))->links('wainwright::layout-pagination') !!}		
    </div>
</div>
</div>
</div>
</div>
</body>
</html>
