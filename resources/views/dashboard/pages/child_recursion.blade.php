<ul>
@foreach($subGroups as $group)
    <li>{{$group->title}}</li>
    @if($group->ledgers)
    <ul>
        @foreach($group->ledgers as $ledger)
        <li>{{$ledger->title}}-<b>{{$ledger->amount}}</b></li>
        @endforeach
    </ul>
    @endif
@if($group->allDescendants)
    @include('dashboard.pages.child_recursion',['subGroups'=>$group->allDescendants])
@endif

@endforeach
</ul>
