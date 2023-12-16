<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'group_identifier'
    ];

    public function group()
    {
        return $this->belongsTo(LedgerGroup::class,'group_identifier','identifier');
    }
    public function openingBalance()
    {
        $transaction=Transaction::where('transaction_no',1)->first();
        if($transaction){
            $transactionEntry=$transaction->transaction_entries->where('ledger_id',$this->id)->first();
            if($transactionEntry){
                return $transactionEntry->amount;
            }else{
                return '0';
            }
        }else{
            return '0';
        }
    }

    public function getLedgerClassification()
    {
        $group=$this->group;
        while($group){
            if($group->parent){
                $group=$group->parent;
            }else{
                $classification=$group->classification;
                if($classification){
                    return $classification->title;
                }
                return null;
            }
        }
        return null;
    }

    public function getAmountAttribute()
    {
        if(TransactionEntry::where('ledger_id',$this->id))
        {
            $amt1= TransactionEntry::where('ledger_id',$this->id)->where('dc',1)->sum('amount');
            $amt2= TransactionEntry::where('ledger_id',$this->id)->where('dc',0)->sum('amount');
            return $amt2-$amt1;
        }else{
            return 0;
        }

    }

}
