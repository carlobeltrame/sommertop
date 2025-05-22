@extends('app')

@section('content')
    <div class="font-sans">
        <h2 class="break-normal text-gray-900 dark:text-gray-100 pb-2 m-0!">{{ $title }}</h2>
        <hr class="border-b border-gray-400 dark:border-gray-600 p-0 mb-6">
    </div>
    <ul>
        @foreach($contents as $item)
            <li class="py-2"><a target="_blank" href="{{ $item['path'] }}">{{ $item['name'] }}</a></li>
        @endforeach
    </ul>
@endsection
