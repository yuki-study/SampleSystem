<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Resources\TaxResource;

class CryptoController extends Controller
{
    public function index()
    {
        //return Tax::all();
    }

    /**
     * データ取得
     * @return Cypto
     */
    public function getAllData()
    {
        $client = new Client();
        $url = "https://api.binance.com/api/v3/ticker/24hr";
        // $url = "https://api.coindesk.com/v1/bpi/currentprice.json";
        $response = $client->request(
            'GET',
            $url,
            //['query' => $params] // パラメーターがあれば設定 ?foo=barの場合は $params = ['foo' => 'bar']
        );
        $json = json_decode($response->getBody(), true);
        //\Log::error(print_r($json, true));
        // シンボルの絞り込み
        //　不要キーを除外
        $resonseJson = [
            "BTC" => [],
            "USDT" => [],
        ];
        foreach ($json as $key => $value) {
            if (substr($value['symbol'], -3) == "BTC") {
                if (self::checkPrice($value)) {
                    continue;
                }
                $value['symbolKey'] = "BTC";
                array_push($resonseJson["BTC"], self::processing($value));
            } elseif (substr($value['symbol'], -4) == "USDT") {
                if (self::checkPrice($value)) {
                    continue;
                } else if (strpos($value['symbol'], 'DOWN') !== false) {
                    // USDT建てのDOWN系を排除
                    continue;
                }
                $value['symbolKey'] = "USDT";
                array_push($resonseJson["USDT"], self::processing($value));
            }
        }

        return $resonseJson;
    }

    // バイナンスで取引されていないコインを判定
    private static function processing($value)
    {
        $value['priceChangePercent'] = round($value['priceChangePercent'], 2);
        $value['quoteVolume'] = floor($value['quoteVolume']);
        unset($value['count']);
        unset($value['lastId']);
        unset($value['firstId']);
        unset($value['closeTime']);
        unset($value['openTime']);
        unset($value['volume']);
        unset($value['lowPrice']);
        unset($value['highPrice']);
        unset($value['openPrice']);
        unset($value['askQty']);
        unset($value['askPrice']);
        unset($value['bidQty']);
        unset($value['lastQty']);
        unset($value['lastPrice']);
        unset($value['prevClosePrice']);
        unset($value['weightedAvgPrice']);
        unset($value['priceChange']);
        return $value;
    }


    // バイナンスで取引されていないコインを判定
    private static function checkPrice($value)
    {
        if ($value['bidPrice'] == 0 && $value['askPrice'] == 0) {
            return true;
        }

        return false;
    }

    /**
     * 時価総額などデータを取得
     * @return Cypto
     */
    public function getMarketCap()
    {
        $client = new Client();

        $url = "https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest";

        $headers = [
            'X-CMC_PRO_API_KEY' => 'e1d3ec13-23d1-4da8-aa20-a374ae5ad3f9',
            'Accept' => 'application/json',
        ];

        $response = $client->request(
            'GET',
            $url,
            array('headers' => $headers)
        );

        $json = json_decode($response->getBody(), true);
        // BTCを削除
        unset($json['data']['0']);
        
        return $json;
    }
}
