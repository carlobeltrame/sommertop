<ul>
    @foreach($contents['files'] as $item)
        <li class="py-2"><a target="_blank" href="{{ $item['path'] }}">{{ $item['name'] }}</a></li>
    @endforeach
</ul>

@foreach($contents['subsections'] as $subsection)
    @include('subsection', ['contents' => $subsection])
@endforeach
