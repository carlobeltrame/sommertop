<ul>
    @foreach(collect($contents['files'])->sortBy('sortName') as $item)
        <li class="py-2"><a target="_blank" href="{{ $item['path'] }}">{{ $item['displayName'] }}</a></li>
    @endforeach
</ul>

@foreach(collect($contents['subsections'])->sortBy('sortName') as $subsection)
    @include('subsection', ['contents' => $subsection])
@endforeach
