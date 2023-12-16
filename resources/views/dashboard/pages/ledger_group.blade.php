@extends('dashboard.layouts.app',['name' => 'Sales Voucher'])

@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="row">
        <div class="col-6">
            <h1>Assets</h1>
            <ul>
                @foreach ($groups as $group)
                @include('dashboard.pages.lg',['group'=>$group])
                @endforeach
            </ul>
            <h2>Total Assets:{{$totalAssets}}</h2>
        </div>
            <div class="col-6">
                <h1>Liabilities</h1>
                <ul>
                    @foreach ($liabilities as $group)
                    @include('dashboard.pages.lg',['group'=>$group])
                    @endforeach
                </ul>
                <h2>Total Assets:{{$totalLiabilities}}</h2>

            </div>
        </div>
            <div class="col-6">
                @foreach($incomes as $income)
                <ul>
                @if($income->ledgers)

                <li>
                    {{$ledgers->title}}-{{$ledgers->amount}}
                </li>
                @endif
                    </ul>
                @endforeach
            </div>

    </section>
</div>
@endsection
