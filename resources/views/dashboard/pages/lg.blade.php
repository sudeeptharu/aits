@foreach ($group as $list)

    <li>
        {{ $list['title'] }}
    </li>
    @if (!empty($list['ledgers']))

        <ul>
    @foreach ($list['ledgers'] as $ledger)
           @php
            $amount=App\Models\LedgerGroup::amount($ledger['id'])
            @endphp
            <li>
                {{$ledger['title'] }}-<b>{{$amount}}</b><h6></h6>
            </li>
    @endforeach
        </ul>
    @endif
        @if (isset($list['all_descendants']) && !empty($list['all_descendants']))
        <ul>
            @include('dashboard.pages.lg',['group'=>$list['all_descendants']])
        </ul>
    @endif
@endforeach
