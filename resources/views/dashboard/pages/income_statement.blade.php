@extends('dashboard.layouts.app',['name' => 'Income Statement'])

@section('content')
<div class="content-wrapper">
    <section class="content">
        <h1>Income Statement</h1>
        <div class="row">
            <div class="col-4">
                <h1>Income</h1>
                <ul>
                    @foreach($incomes as $income)
                    @if($income->ledgers)

                        @foreach($income->ledgers as $ledgers)
                        <li>{{$ledgers->title}}-<b>{{$ledgers->amount}}</b></li>
                        @endforeach

                    @endif
                    @if($income->allDescendants)
                    @include('dashboard.pages.income_child',['subGroups'=>$income->allDescendants])
                    @endif
                    @endforeach
                </ul>
                <h3>{{$amt1}}</h3>
                <h3>Operating Income:{{$amt4}}</h3>
                <h3>Net Income:{{$amt5}}</h3>

            </div>
            <div class="col-4">
                <h1>Expenses</h1>
                <ul>
                @foreach($expenses as $expense)
                @if($expense->ledgers)

                @foreach($expense->ledgers as $ledgers)
                <li>{{$ledgers->title}}-<b>{{$ledgers->amount}}</b></li>
                @endforeach

                @endif
                @if($expense->allDescendants)
                @include('dashboard.pages.income_child',['subGroups'=>$expense->allDescendants])
                @endif
                @endforeach
                </ul>
                <h3>{{$amt2}}</h3>

            </div>
            <div class="col-4">
                <h1>Income(dc=0)</h1>
                <ul>
                    @foreach($unknowns as $expense)
                    @if($expense->ledgers)

                    @foreach($expense->ledgers as $ledgers)
                    <li>{{$ledgers->title}}-<b>{{$ledgers->amount}}</b></li>
                    @endforeach

                    @endif
                    @if($expense->allDescendants)
                    @include('dashboard.pages.income_child',['subGroups'=>$expense->allDescendants])
                    @endif
                    @endforeach
                </ul>
                <h3>{{$amt3}}</h3>

            </div>
        </div>
    </section>
</div>
@endsection
