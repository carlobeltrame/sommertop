<ul>
    @foreach(collect($contents['files'])->sortBy('sortName') as $item)
        @if(isset($item['type']) && $item['type'] === 'html')
            </ul>
                {!! $item['html'] !!}
            <ul>
        @else
            <li class="py-2"><a target="_blank" href="{{ $item['path'] }}">{{ $item['displayName'] }}</a></li>
        @endif
    @endforeach
</ul>

@foreach(collect($contents['subsections'])->sortBy('sortName') as $subsection)
    @include('subsection', ['contents' => $subsection])
@endforeach
