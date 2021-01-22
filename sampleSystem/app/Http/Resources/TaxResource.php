<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaxResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'targetYear' => $this->year,
            'patternFlg' => $this->pattern,
            'patternMemo' => $this->pattern_memo,
            'income' => $this->income,
            'expenses' => $this->expenses,
            'declarationFlg' => $this->declaration_flg,
            'deductions' => $this->deductions,
            'insuranceFeeDeduction' => $this->insurance_fee_deduction,
            'insuranceFeeIncome' => $this->insurance_fee_income,
            'insuranceFeeEqual' => $this->insurance_fee_equal,
            'municipalTaxIncome' => $this->municipal_tax_income,
            'municipalTaxEqual' => $this->municipal_tax_equal,
            'pension' => $this->pension,
        ];
    }
}
