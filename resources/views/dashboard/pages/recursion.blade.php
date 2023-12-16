@extends('dashboard.layouts.app',['name' => 'Sales Voucher'])

@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-6">
                <h1>Assets</h1>
                <ul>
                    @foreach($assetLists as $group)
                    <li>{{$group->title}}</li>
                    @if($group->ledgers)
                    <ul>
                        @foreach($group->ledgers as $ledgers)
                        <li>{{$ledgers->title}}-<b>{{$ledgers->amount}}</b></li>
                        @endforeach
                    </ul>
                    @endif
                    @if($group->allDescendants)
                        @include('dashboard.pages.child_recursion',['subGroups'=>$group->allDescendants])
                    @endif
                    @endforeach
                </ul>
            </div>
            <div class="col-6">
                <h1>Liabilities</h1>
                <ul>
                    @foreach($liabilitiesLists as $group)
                    <li>{{$group->title}}</li>
                    @if($group->ledgers)
                    <ul>
                        @foreach($group->ledgers as $ledgers)
                        <li>{{$ledgers->title}}-<b>{{$ledgers->amount}}</b></li>
                        @endforeach
                    </ul>
                    @endif
                    @if($group->allDescendants)

                        @include('dashboard.pages.child_recursion',['subGroups'=>$group->allDescendants])
                    @endif
                    @endforeach
                </ul>

            </div>
        </div>
    </section>
</div>
@endsection
