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
            'targetYear' => 'required',
            'patternFlg' => 'required',
            'patternMemo' => ['nullable','string'],
            'income' => ['bail','required','numeric'],
            'expenses' => ['bail','required','numeric'],
            'declarationFlg' => ['in:1,2'],
            'deductions.*.deductionName' => ['required'],
            'insuranceFeeDeduction' => ['required'],
            'insuranceFeeIncome' => ['required'],
            'insuranceFeeEqual' => ['required'],
            'municipalTaxIncome' => ['required'],
            'municipalTaxEqual' => ['required'],
            'pension' => ['nullable','numeric'],
        ];
    }

    public function messages()
    {
        return [
            'targetYear.required' => '対象年度は必須です',
            'patternFlg.required' => 'パターンは必須です',
            'patternMemo.string' => 'パターンメモは文字列を入力してください',
            'income.required' => '収入は必須です',
            'income.numeric' => '収入は数値を入力してください',
            'expenses.required' => '経費は必須です',
            'expenses.numeric' => '経費は数値を入力してください',
            'declarationFlg' => '申告フラグが有効な数字ではありません',
            'deductions.*.deductionName' => '所得控除の名前は必須です',
            'insuranceFeeDeduction.required' => '住民税基礎年金控除額は必須です',
            'insuranceFeeIncome.required' => '国保所得割率は必須です',
            'insuranceFeeEqual.required' => '国保均等割額は必須です',
            'municipalTaxIncome.required' => '住民税所得割率は必須です',
            'municipalTaxEqual.required' => '住民税所得割率は必須です',
            'pension.numeric' => '月々の年金額は数値を入力してください',
        ];
    }
}
