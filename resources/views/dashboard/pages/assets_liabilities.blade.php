
@foreach ($group as $list)

<li>
    {{ $list['title'] }}
</li>
@if (!empty($list['ledgers']))
<ul>
    @foreach ($list['ledgers'] as $ledger)
    <li>
        {{ $ledger['title'] }}
    </li>
    @endforeach
</ul>
@endif
@if (isset($list['all_descendants']) && !empty($list['all_descendants']))
<ul>
    <li>ppppppaa</li>
</ul>
@endif
@endforeach
