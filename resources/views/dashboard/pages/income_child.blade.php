    @foreach($subGroups as $group)
    @if($group->ledgers)

        @foreach($group->ledgers as $ledger)
        <li>{{$ledger->title}}-<b>{{$ledger->amount}}</b></li>
        @endforeach

    @endif
    @if($group->allDescendants)
    @include('dashboard.pages.child_recursion',['subGroups'=>$group->allDescendants])
    @endif

    @endforeach
