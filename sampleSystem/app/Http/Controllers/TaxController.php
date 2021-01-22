<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use App\Http\Requests\storeTaxRequest;
use App\Http\Resources\TaxResource;

class TaxController extends Controller
{
    public function index()
    {
        //return Tax::all();
    }

    /**
     * データ取得
     * @param string $target_year
     * @param string $pattern_flg
     * @return Photo
     */
    public function show(string $target_year, string $pattern_flg)
    {

        $data = Tax::where('year', $target_year)->where('pattern', $pattern_flg)
            ->first();
        // $dataが存在する場合APIリソースに渡す、しない場合undefined
        return $data ? new TaxResource($data) : $data;
    }

    // Taxの登録処理
    // storeTaxRequestとすることでバリデーションチェックがされる
    // エラーの場合呼び出し元へ返却される
    public function store(storeTaxRequest $request)
    {
        // 対象年度、パターンでデータが存在すれば更新処理、存在しなければ新規作成処理を実施する
        try {
            Tax::updateOrCreate(
                ['year' => $request->tax['targetYear'], 'pattern' => $request->tax['patternFlg']],
                [
                    'pattern_memo' => $request->tax['patternMemo'],
                    'income' => $request->tax['income'],
                    'expenses' => $request->tax['expenses'],
                    'declaration_flg' => $request->tax['declarationFlg'],
                    'deductions' => $request->tax['deductions'],
                    'insurance_fee_deduction' => $request->tax['insuranceFeeDeduction'],
                    'insurance_fee_income' => $request->tax['insuranceFeeIncome'],
                    'insurance_fee_equal' => $request->tax['insuranceFeeEqual'],
                    'municipal_tax_income' => $request->tax['municipalTaxIncome'],
                    'municipal_tax_equal' => $request->tax['municipalTaxEqual'],
                    'pension' => $request->tax['pension']
                ]
            );
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json('error', 500);
        }

        return response()->json('success', 200);
    }
}
