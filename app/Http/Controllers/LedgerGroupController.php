<?php

namespace App\Http\Controllers;

use App\Models\LedgerClassification;
use App\Models\LedgerGroup;
use App\Models\LedgerType;
use App\Models\TransactionEntry;
use App\Models\VoucherType;
use Illuminate\Http\Request;

class LedgerGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//
//        $ledgerGroups=LedgerGroup::where('classification_identifier','ASSETS')->get();
////        dd($ledgerGroups);
//        $groups=[];
//        foreach($ledgerGroups as $ledgerGroup){
//            $groups[]=$ledgerGroup->allDescendants->toArray();
//        }
//
//
//        $liabilitiesGroups=LedgerGroup::where('classification_identifier','LIABILITIES')->get();
//        $liabilities=[];
//        foreach($liabilitiesGroups as $ledgerGroup){
//            $liabilities[]=$ledgerGroup->allDescendants->toArray();
//        }
//        $totalAssets=TransactionEntry::where('dc','0')->sum('amount');
//        $totalLiabilities=TransactionEntry::where('dc',1)->sum('amount');

//        dd($totalLiabilities);
//        dd($liabilities);
//        dd($groups);
//        return view('dashboard.pages.ledger_group',compact('groups','liabilities','totalAssets','totalLiabilities'));

        $ledger_classifications=LedgerClassification::all();
        $ledger_types=LedgerType::all();
        $voucher_types=VoucherType::all();
        $ledger_groups=LedgerGroup::with('classification')->with('parent')->with('negative_ledger')->get();
        return view('dashboard.pages.ledger_groups',compact('ledger_groups','voucher_types','ledger_types','ledger_classifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'title' => 'required|string',
            'identifier'=>'required|string',
            'classification_identifier' => 'nullable|string',
            'parent_identifier' => 'nullable|string',
            'negative_identifier' => 'nullable|string',
            'affects_gross_profit' => 'boolean',
            'ledger_type'=>'required'
        ]);
        LedgerGroup::create($data);
        return redirect('ledger-group');

    }

    /**
     * Display the specified resource.
     */
    public function show(LedgerGroup $ledgerGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LedgerGroup $ledgerGroup)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LedgerGroup $ledgerGroup)
    {
        $id=$request->id;
        $data = $request->validate([
            'title' => 'required|string',
            'identifier' => 'required|string',
            'classification_identifier' => 'nullable|string',
            'parent_identifier' => 'nullable|string',
            'negative_identifier' => 'nullable|string',
            'affects_gross_profit' => 'boolean',
            'ledger_type'=>'required'

        ]);
        $ledgerGroup = LedgerGroup::findOrFail($id);
        $ledgerGroup->update($data);
        return redirect('ledger-group');

    }

    public function destroy($id)
    {
        LedgerGroup::where(['id'=>$id])->delete();
        return redirect('ledger-group');
    }
    public function recursion()
    {
//        $incomes=LedgerGroup::where('classification_identifier','INCOME')->where('affects_gross_profit',1)->with('allDescendants','ledgers',)->get();
////        $ledgersId=$incomes->pluck('ledgers')->toArray();
//        $amt1=0;
//        foreach ($incomes as $income)
//        {
//            if($income->ledgers){
//                foreach ($income->ledgers as $ledger)
//                {
//                    $amt1=$amt1+$ledger->amount;
//                }
//            }
//            if($income->allDescendants)
//            {
//                $this->recursionChild($income->allDescendants,$amt1);
//            }
//        }
////        dd($amt1);
//        dd($incomes);
//        $expenses=LedgerGroup::where('classification_identifier','EXPENSES')->with('allDescendants','ledgers')->get();
//        $amt2=0;
//        foreach ($expenses as $expense)
//        {
//            if($expense->ledgers){
//                foreach ($expense->ledgers as $ledger)
//                {
//                    $amt2=$amt2+$ledger->amount;
//                }
//            }
//            if($income->allDescendants)
//            {
//                $this->recursionChild($income->allDescendants,$amt2);
//            }
//        }
//        dd($expenses);
        $assetLists=LedgerGroup::where('classification_identifier','ASSETS')->with('allDescendants','allDescendants')->get();
        $liabilitiesLists=LedgerGroup::where('classification_identifier','LIABILITIES')->with('allDescendants','ledgers')->get();
//        dd($ledgerGroups);
        return view('dashboard.pages.recursion',compact('assetLists','liabilitiesLists',));
    }

    public function recursionChild($children,$amt)
    {
        foreach($children as $child)
        {
//            if($child->ledgers){
//                foreach ($child->ledgers as $ledger)
//                {
//                    $amt=$amt+$ledger->amount;
//                }
//            }
            $amt=$child->ledgers->reduce(function ($carry,$ledger){
                return $carry+$ledger->amount;
            },0);
            if($child->allDescendants)
            {
                $this->recursionChild($child->allDescendants,$amt);
            }
        }
        return $amt;
    }
    public function incomeStatement()
    {
        $incomes=LedgerGroup::where('classification_identifier','INCOME')
            ->where('affects_gross_profit',1)
            ->with('allDescendants','ledgers',)
            ->get();
//        $ledgersId=$incomes->pluck('ledgers')->toArray();
        $amt1=0;
        foreach ($incomes as $income)
        {
//            if($income->ledgers){
//                foreach ($income->ledgers as $ledger)
//                {
//                    $amt1=$amt1+$ledger->amount;
//                }
//            }
            $amt1=$income->ledgers->reduce(function ($carry,$ledger){
                return $carry+$ledger->amount;
            },0);
            if($income->allDescendants)
            {
                $this->recursionChild($income->allDescendants,$amt1);
            }
        }
//        dd($amt1);
//        dd($incomes);
        $expenses=LedgerGroup::where('classification_identifier','EXPENSES')
            ->with('allDescendants','ledgers')
            ->get();
        $amt2=0;
        foreach ($expenses as $expense)
        {
//
            $amt2=$expense->ledgers->reduce(function ($carry,$ledger){
                return $carry+$ledger->amount;
            },0);
            if(!empty($expense->allDescendants))
            {
                $this->recursionChild($expense->allDescendants,$amt2);
            }
        }

        $unknowns=LedgerGroup::where('classification_identifier','INCOME')
            ->where('affects_gross_profit',0)
            ->with('allDescendants','ledgers',)
            ->get();
        $amt3=0;
        foreach ($unknowns as $unknown)
        {
            $amt3=$unknown->ledgers->reduce(function ($carry,$ledger){
                return $carry+$ledger->amount;
            },0);
            if($unknown->allDescendants->isNotEmpty())
            {
                $this->recursionChild($unknown->allDescendants,$amt3);
            }
        }
        $amt4=$amt1-$amt2;
        $amt5=$amt4+$amt3;
        return view('dashboard.pages.income_statement',compact('incomes','expenses','amt1','amt2','unknowns','amt3','amt4','amt5'));
//        dd($expenses);
    }
}
