<ul>
    @foreach(collect($contents['files'])->sortBy('sortName') as $item)
        @if(isset($item['type']) && $item['type'] === 'html')
            </ul>
                {!! $item['html'] !!}
            <ul>
        @elseif(isset($item['type']) && $item['type'] === 'audio')
            <li class="py-2">
                <a target="_blank" href="{{ $item['path'] }}">{{ $item['displayName'] }}</a><br/>
                <audio controls preload="none">
                    <source src="{{ $item['path'] }}">
                </audio>
            </li>
        @else
            <li class="py-2"><a target="_blank" href="{{ $item['path'] }}">{{ $item['displayName'] }}</a></li>
        @endif
    @endforeach
</ul>

@foreach(collect($contents['subsections'])->sortBy('sortName') as $subsection)
    @include('subsection', ['contents' => $subsection])
@endforeach
