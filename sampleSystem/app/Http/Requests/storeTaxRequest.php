<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeTaxRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // trueにすることでバリデーションがきくようになる
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tax.targetYear' => 'required',
            'tax.patternFlg' => 'required',
            'tax.patternMemo' => ['nullable','string'],
            'tax.income' => ['bail','required','numeric'],
            'tax.expenses' => ['bail','required','numeric'],
            'tax.declarationFlg' => ['in:1,2'],
            'tax.deductions.*.deductionName' => ['required'],
            'tax.insuranceFeeDeduction' => ['required'],
            'tax.insuranceFeeIncome' => ['required'],
            'tax.insuranceFeeEqual' => ['required'],
            'tax.municipalTaxIncome' => ['required'],
            'tax.municipalTaxEqual' => ['required'],
            'tax.pension' => ['nullable','numeric'],
        ];
    }

    public function messages()
    {
        return [
            'tax.targetYear.required' => '対象年度は必須です',
            'tax.patternFlg.required' => 'パターンは必須です',
            'tax.patternMemo.string' => 'パターンメモは文字列を入力してください',
            'tax.income.required' => '収入は必須です',
            'tax.income.numeric' => '収入は数値を入力してください',
            'tax.expenses.required' => '経費は必須です',
            'tax.expenses.numeric' => '経費は数値を入力してください',
            'tax.declarationFlg' => '申告フラグが有効な数字ではありません',
            'tax.deductions.*.deductionName' => '所得控除の名前は必須です',
            'tax.insuranceFeeDeduction.required' => '住民税基礎年金控除額は必須です',
            'tax.insuranceFeeIncome.required' => '国保所得割率は必須です',
            'tax.insuranceFeeEqual.required' => '国保均等割額は必須です',
            'tax.municipalTaxIncome.required' => '住民税所得割率は必須です',
            'tax.municipalTaxEqual.required' => '住民税均等割率は必須です',
            'tax.pension.numeric' => '月々の年金額は数値を入力してください',
        ];
    }
}
