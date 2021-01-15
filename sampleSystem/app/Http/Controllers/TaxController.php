<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use App\Http\Requests\storeTaxRequest;

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
        return $data;
    }

    // Taxの登録処理
    // storeTaxRequestとすることでバリデーションチェックがされる
    // エラーの場合呼び出し元へ返却される
    public function store(storeTaxRequest $request)
    {
        return 'test';
    }
}
