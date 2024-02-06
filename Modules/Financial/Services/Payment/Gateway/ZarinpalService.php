<?php

namespace Modules\Financial\Services\Payment\Gateway;

use Illuminate\Support\Facades\Http;
use Modules\Financial\Interfaces\GatewayServiceInterface;

class ZarinpalService implements GatewayServiceInterface
{
    const SUCCESS_CODE = 100;
    const MERCHANT_ID = "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx";
    private $baseAddress;

    public function __construct()
    {
//        if (App::environment() != 'production') {
//            $this->baseAddress = 'https://sandbox.zarinpal.com/';
//        } else {
        $this->baseAddress = 'https://api.zarinpal.com/';
//        }
    }

    public function generatePayLink($amount)
    {
        $url = $this->baseAddress . 'pg/v4/payment/request.json';
        $formParameters = [
            "merchant_id" => self::MERCHANT_ID,
            "amount" => $amount,
            "callback_url" => "https://localhost", //todo correct url for verify route + transactionId
            "description" => "Transaction description.",
            /*"metadata"     => [
                "mobile" => '',
                "email"  => ''
            ]*/
        ];
        $headers = [
            'Content-Type' => ' application/json',
            'Accept' => 'application/json'
        ];
        $response = Http::maxRedirects(10)
            ->withHeaders($headers)
            ->post($url, $formParameters);

        $data = $response->json()['data'];

        if ($data['code'] == self::SUCCESS_CODE) {
            return $this->startPay($data['authority']);
        } else {
            throw new \Exception;
        }
    }

    private function startPay($authority)
    {
        $url = $this->baseAddress . 'pg/StartPay/';
        return Http::get($url . $authority);
    }


    public function verify($amount, $authority)
    {
        $url = $this->baseAddress . 'pg/v4/payment/verify.json';
        $formParameters = [
            "merchant_id" => self::MERCHANT_ID,
            "amount" => $amount,
            "authority" => $authority
        ];
        $headers = [
            'Content-Type' => ' application/json',
            'Accept' => 'application/json'
        ];
        $response = Http::timeout(0)
            ->maxRedirects(10)
            ->withHeaders($headers)
            ->post($url, $formParameters);

        $data = $response->json()['data'];

        if ($data['code' == self::SUCCESS_CODE]) {
            return true;
        } else {
            throw new \Exception;
        }
    }
}
