<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//　税金計算モデル
class Tax extends Model
{
    protected $fillable = [
        'year',
        'pattern',
        'pattern_memo',
        'income',
        'expenses',
        'declaration_flg',
        'deductions',
        'insurance_fee_deduction',
        'insurance_fee_income',
        'insurance_fee_equal',
        'municipal_tax_income',
        'municipal_tax_equal',
        'pension'
    ];

    protected $casts = [
        'deductions'  => 'array',
    ];

}
