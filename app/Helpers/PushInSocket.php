<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

if (!function_exists('PushInSocket')) {
    /**
     * @param $result
     * @return bool
     */
    function PushInSocket($apis, $user_id = 0)
    {
        if ($user_id == 0) {
            $user_id = a_auth('user_id');
        }
        foreach ($apis as $api) {
            if ($api == 'wallet_balance') {
                $response = getAllByFilter($user_id);

                push($response['data'], $api, $user_id);
            } elseif ($api == 'transaction') {
                $funding_transaction = getAllTransactions('Funding', $user_id);
                $earning_transaction = getAllTransactions('Earning', $user_id);
                $affiliates_transaction = getAllTransactions('Affiliates', $user_id);
                $all_transactions = getAllTransactions('all', $user_id, 4);
                $response = ['funding' => $funding_transaction['data'], 'earning' => $earning_transaction['data'], 'affiliates' => $affiliates_transaction['data'], 'all_transactions' => $all_transactions['data']];
                push($response, $api, $user_id);
            } elseif ($api == 'referral') {
                $response = get_referral_by_level($user_id);
                push($response['data'], $api, $user_id);
            } elseif ($api == 'ads-packages-list') {
                $response = getAdsPackagesList();
                push($response['data'], $api, $user_id);
            } elseif ($api == 'profile') {
                $response = getProfile();
                push($response['data'], $api, $user_id);
            }
        }
    }
}
if (!function_exists('Push')) {
    /**
     * @param $result
     * @return bool
     */
    function Push($response, $api, $user_id = 0)
    {
        $apiUrl = 'https://socket.getpaidtasks.com/getpaidtasks';
        $authToken = '4f7d938c63207210ed5a911207c6f45f56c7572815e55da6ef702cdb8898fb3b';
        $postData = [
            'userId' => $user_id ?: a_auth('user_id'),
            'messages' => ['type' => $api, 'data' => $response]
        ];
      $response= Http::withHeaders([
            'Authorization' => $authToken,
            'Accept' => 'application/json',
        ])->post($apiUrl, $postData);

//      Log::info("socket response ".print_r($response->body(), true));
    }
}

