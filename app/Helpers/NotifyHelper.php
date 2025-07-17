<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Arr;
use App\Mail\LoginAlertEmail;

if (!function_exists('dispatchNotification')) {
    /**
     * @param $result
     * @return bool
     */
    function dispatchNotification($notifyData, $mailable)
    {
        $mailTo = (object)['email' => $notifyData['email'], 'name' => $notifyData['name']];

        switch ($mailable) {
            case 'LoginAlert':
                $mailData = [
                    'name' => $notifyData['name'],
                    'email' => $notifyData['email'],
                    'login_at' => _date($notifyData['login_at']),
                ];
                Mail::to($mailTo)->send(new LoginAlertEmail($mailData));
                break;
        }
    }
}
