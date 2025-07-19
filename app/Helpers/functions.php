<?php

use App\Exceptions\ApiException;
use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Http;

/*
Check Json
*/

if (!function_exists('isJson')) {
    function isJson($string)
    {
        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }
}

/*
Execute Database Functions
*/


/**
 * Sanitize and upload image(s) to specified location
 *
 * @param UploadedFile|array $file The file or array of files to upload
 * @param string $location The storage location (e.g., 'public/uploads')
 * @param array $options Additional options:
 *              - 'max_size' => Maximum file size in KB (default: 2048)
 *              - 'allowed_mimes' => Allowed mime types (default: ['image/jpeg', 'image/png', 'image/gif'])
 *              - 'resize' => Optional resize dimensions ['width' => x, 'height' => y]
 *              - 'quality' => Image quality (default: 85)
 * @return array|string Array of filenames for multiple uploads or single filename
 * @throws \Exception
 */
function uploadFiles($files, string $location = 'uploads'): array
{
    $uploadedPaths = [];
    $files = is_array($files) ? $files : [$files];

    // Create directory if it doesn't exist
    $fullPath = public_path($location);
    if (!File::exists($fullPath)) {
        File::makeDirectory($fullPath, 0755, true);
    }

    foreach ($files as $file) {
        if (!($file instanceof UploadedFile)) {
            continue;
        }

        if (!$file->isValid()) {
            throw new \RuntimeException('File upload error: ' . $file->getErrorMessage());
        }

        $extension = $file->getClientOriginalExtension();
        $filename = Str::random(40) . '.' . $extension;

        try {
            // Move file to public directory
            $file->move($fullPath, $filename);

            // Return web-accessible path (no 'public' in path)
            $uploadedPaths[] = "$location/$filename";

        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to store file: ' . $e->getMessage());
        }
    }

    return $uploadedPaths;
}

if (!function_exists('wdb')) {
    /**
     * Return PDO instance for write database
     *
     * @param null $params
     * @param bool $fetch
     * @return mixed
     */
    function wdb($sql = null, $params = null, $fetch = true)
    {
        $pdo = DB::connection('mysql')->getPdo();

        if (is_null($sql)) {
            return $pdo;
        }

        $is_procedure = str_contains($sql, 'CALL') ? true : false;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            if ($fetch) {
                if ($is_procedure) {
                    $json = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $result = $json;
                } else {
                    $json = $stmt->fetch();
                    $result = json_decode($json['result'], true);
                }
            } else {
                $result = [
                    'payload' => 'No fetch',
                    'status' => 'OK',
                ];
            }
        } catch (PDOException $e) {
            $error_info = $e->errorInfo;
            Log::info($error_info);

            if ($error_info[0] == 45000) {
                $result = [
                    'payload' => [
                        'code' => $error_info[1],
                        'message' => $error_info[2],
                    ],
                    'status' => 'ERROR',
                ];
            } else {
                $result = internal_error($e);
            }
        }

        return $result;
    }
}
//if (!function_exists('wdb')) {
//    /**
//     * Return PDO instance for write database
//     *
//     * @param null $params
//     * @param bool $fetch
//     * @return mixed
//     */
//    function wdb($sql = null, $params = null, $fetch = true)
//    {
//        $pdo = DB::connection('mysql::read')->getPdo();
//        if (is_null($sql)) {
//            return $pdo;
//        }
//
//        $is_procedure = str_contains($sql, 'CALL') ? true : false;
//
//        try {
//            $stmt = $pdo->prepare($sql);
//            $stmt->execute($params);
//
//            if ($fetch) {
//                if ($is_procedure) {
//                    $json = $stmt->fetchAll(PDO::FETCH_ASSOC);
//                    $result = $json;
//                } else {
//                    $json = $stmt->fetch();
//                    $result = json_decode($json['result'], true);
//                }
//            } else {
//                $result = [
//                    'payload' => 'No fetch',
//                    'status' => 'OK',
//                ];
//            }
//        } catch (PDOException $e) {
//            $error_info = $e->errorInfo;
//            Log::info($error_info);
//
//            if ($error_info[0] == 45000) {
//                $result = [
//                    'payload' => [
//                        'code' => $error_info[1],
//                        'message' => $error_info[2],
//                    ],
//                    'status' => 'ERROR',
//                ];
//            } else {
//                $result = internal_error($e);
//            }
//        }
//
//        return $result;
//    }
//}

if (!function_exists('internal_error')) {
    /**
     * prepare error
     *
     * @return array
     */
    function internal_error(Exception $e)
    {
        $error = [
            'payload' => [
                'code' => 500,
                'message' => 'Something went wrong',
            ],
            'status' => 'ERROR',
        ];

        if (config('app.debug')) {
            $error['payload']['message'] = $e->getMessage();
        }

        return $error;
    }
}

/*
Get Current Ip Address
*/

if (!function_exists('get_ip_address')) {
    function get_ip_address()
    {
        $headers = getallheaders();
        $ip = null;
        if (isset($headers['X-Connecting-Ip'])) {
            $ip = $headers['X-Connecting-Ip'];
        } elseif (isset($headers['Cf-Connecting-Ip'])) {
            $ip = $headers['Cf-Connecting-Ip'];
        } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = '127.0.0.1';
        }
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
        return $ip;
    }
}

/*
Get Current User Agent
*/
if (!function_exists('getUserAgent')) {
    function getUserAgent()
    {
        $appVersion = Request::header('user-agent');

        $ex = explode('||', $appVersion);
        if (isset($ex[1])) {
            $browser = substr($ex[1], 0, strpos($ex[1], ';')) . ', ' . Browser::deviceModel();
        } else {
            $browser = Browser::deviceModel();
        }
        return $browser;
    }
}

/*
Get Site Info
*/

if (!function_exists('site_info')) {
    function site_info($output = 'name')
    {
        $apps = config('app.name');
        $name = config('app.corename');
        $app_url = config('app.url');
        $base_url = url('/');

        $infos = [
            'apps' => $apps,
            'name' => $name,
            'url' => $base_url,
            'url_only' => str_replace(['https://', 'http://'], '', $base_url),
            'url_app' => $app_url,
        ];

        $output = (empty($output)) ? 'name' : $output;
        $return = (($output == 'all') ? $infos : ((isset($infos[$output])) ? $infos[$output] : ''));

        return $return;
    }
}

/*
Get Country by ip_address
*/
if (!function_exists('get_country')) {
    function get_country()
    {
        $headers = getallheaders();
        if (isset($headers['X-Country-Code'])) {
            $countryCode = $headers['X-Country-Code'];
            $countries = config('icoapp.countries');
            if (array_key_exists($countryCode, $countries)) {
                $country = $countries[$countryCode];
            } else {
                $country = 'Unknown or Invalid Region';
            }
        } elseif (isset($headers['Cf-Ipcountry'])) {
            $countryCode = $headers['Cf-Ipcountry'];
            $countries = config('icoapp.countries');
            if (array_key_exists($countryCode, $countries)) {
                $country = $countries[$countryCode];
            } else {
                $country = 'Unknown or Invalid Region';
            }
        } else {
//            $country = config('app.env') == 'local' || config('app.env') == 'testing' ? 'Nepal' : $location->countryName;
            $country = config('app.env') == 'local' || config('app.env') == 'testing' ? 'Nepal' : 'Nepal';
        }

        return $country;
    }
}

/*
Get Country Code By Ip Address
*/
if (!function_exists('get_country_code')) {
    function get_country_code()
    {
        $location = Location::get(get_ip_address());
        $headers = getallheaders();
        if (isset($headers['X-Country-Code'])) {
            $country = $headers['X-Country-Code'];
        } elseif (isset($headers['Cf-Ipcountry'])) {
            $country = $headers['Cf-Ipcountry'];
        } else {
            $country = config('app.env') == 'local' ? 'NP' : $location->countryCode;
        }

        return $country;
    }
}
/*
Get Country By Code
*/
if (!function_exists('getCountryByCode')) {
    function getCountryByCode($code)
    {
        $countries = config('icoapp.countries');
        if (array_key_exists($code, $countries)) {
            $country = $countries[$code];
        } else {
            $country = 'Unknown or Invalid Region';
        }

        return $country;
    }
}

if (!function_exists('getCountryByEmail')) {
    function getCountryByEmail($email)
    {
        $country = DB::table('users')->where('email', '=', $email)->get()->first();
        return isset($country->country_id) ? $country->country_id : null;
    }
}

/*
Get City Code By Ip Address
*/
if (!function_exists('get_city')) {
    function get_city()
    {
        $ip = get_ip_address();
        if ($ip == "127.0.0.1") {
            $ip = "199.241.137.139";
        }
        $location = Location::get($ip);
        if ($location) {
            $city = $location->cityName;

            return $city;
        } else {
            return null;
        }
    }
}

/*
Get Session Data
*/
if (!function_exists('a_auth')) {
    function a_auth($keys = 'user_id')
    {
        $sql = 'SELECT `fn_user_detail`() AS result';
        $result = wdb($sql);
        if ($result['status'] == 1) {
            $result = (array) json_decode($result['data']);
            return $result[$keys];
        }

        return false;
    }
}

function validateTronAddress($address)
{
    // Check that the address is a string of 34 characters starting with "T"
    if (!is_string($address) || strlen($address) != 34 || substr($address, 0, 1) != 'T') {
        return false;
    }

    // Decode the address using Base58Check encoding
    $decoded = base58_decode($address);

    // Check that the decoded string is 21 bytes long
    if (strlen($decoded) != 21) {
        return false;
    }

    // Extract the checksum from the decoded string
    $checksum = substr($decoded, -4);

    // Extract the address without the checksum
    $address_without_checksum = substr($decoded, 0, -4);

    // Calculate the checksum of the address without the checksum
    $hashed_address = hash('sha256', hash('sha256', $address_without_checksum, true));

    // Compare the calculated checksum with the extracted checksum
    if ($checksum !== substr($hashed_address, 0, 4)) {
        return false;
    }

    // The address is valid
    return true;
}

// Helper function to decode Base58Check encoded strings
function base58_decode($data)
{
    $alphabet = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
    $base_count = strlen($alphabet);
    $strlen = strlen($data);
    $num = 0;
    $leading_zeros = 0;
    $j = 0;

    for ($i = 0; $i < $strlen; $i++) {
        if ($data[$i] == '1' && $i == 0) {
            $leading_zeros++;
        } else {
            break;
        }
    }

    for ($i = $leading_zeros; $i < $strlen; $i++) {
        $current = strpos($alphabet, $data[$i]);
        $num = $num * $base_count + $current;
        $j++;
    }

    $return = str_repeat("\0", max(0, $leading_zeros - 1)) . pack('H*', sprintf('%x', $num));

    return $return;
}

/*
Get current Ip Address
*/

function getUserByUserId($user_id)
{
    return DB::table('users')->where('user_id', $user_id)->first();
}

if (!function_exists('get_ip_address')) {
    function get_ip_address()
    {
        $headers = getallheaders();
        $ip = null;
        if (isset($headers['X-Connecting-Ip'])) {
            $ip = $headers['X-Connecting-Ip'];
        } elseif (isset($headers['Cf-Connecting-Ip'])) {
            $ip = $headers['Cf-Connecting-Ip'];
        } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = '127.0.0.1';
        }
        return $ip;
    }
}

/*
Get User Detail By Id
*/
if (!function_exists('userById')) {
    function userById($user_id = null)
    {
        $sql = 'SELECT `fn_get_user_detail`(' . $user_id . ') AS result';
        $result = wdb($sql);

        return $result;
    }
}

/*
Get Values from settings table
*/
if (!function_exists('get_setting')) {
    function get_setting($field)
    {
        $sql = 'SELECT `GET_SETTING`("' . $field . '") AS result';
        $result = wdb($sql);
        if ($result['status']) {
            return $result['data']['value'];
        } else {
            return false;
        }
    }
}
/*
Add Values to settings table
*/
if (!function_exists('add_setting')) {
    function add_setting($field, $value)
    {
        $sql = 'SELECT `SET_SETTING`("' . $field . '","' . $value . '") AS result';
        $result = wdb($sql);
        if ($result['status']) {
            return $result['value'];
        } else {
            return false;
        }
    }
}

if (!function_exists('getContent_v1')) {
    function getContent_v1($variables, $rawContent)
    {
        foreach ($variables as $key => $value) {
            if ($value !== null) {
                if (is_array($value)) {
                    $value = implode(',', $value); // Convert array to comma-separated string
                }
                $rawContent = str_replace('{{' . $key . '}}', strval($value), $rawContent);
            }
        }
        return $rawContent;

    }
}

if (!function_exists('appendViewParamToLinks')) {
    function appendViewParamToLinks($html)
    {
        return preg_replace_callback(
            '/<a\s+[^>]*?href="([^"]+)"/i',
            function ($matches) {
                $url = $matches[1];

                // Check if URL already has query parameters
                $separator = (strpos($url, '?') !== false) ? '&' : '?';
                $updatedUrl = $url . $separator . 'view=true';

                // Return the modified anchor tag
                return str_replace($url, $updatedUrl, $matches[0]);
            },
            $html
        );
    }
}

/*
Show users below $id
*/
function showBelow($id)
{
    $arr = [];
    $under_ref = DB::table('users')->where('referrer', $id)->whereNotNull('email_confirmed')->orderByRaw('CASE WHEN users.lastLogin IS NULL THEN 0 ELSE 1 END DESC')
        ->orderBy('users.lastLogin', 'DESC')->get();
    foreach ($under_ref as $u) {
        array_push($arr, $u->user_id);
    }

    return $arr;
}

/*
Send Notification to user
*/
//if (!function_exists('sendPushNotification')) {
//    function sendPushNotification(array $notify)
//    {
//        if (config('app.env') != 'local') {
//            $extUserId = isset($notify['user_id']) ? $notify['user_id'] : a_auth('user_id');
//            $message = isset($notify['message']) ? $notify['message'] . " \nThank you. " . config('app.name') : null;
//            $heading = isset($notify['title']) ? $notify['title'] : null;
//            $url = isset($notify['url']) ? $notify['url'] : null;
//            $type = isset($notify['type']) ? $notify['type'] : null;
//            $typeId = isset($notify['typeId']) ? $notify['typeId'] : null;
//            $externalId = isset($notify['externalId']) ? $notify['externalId'] : null;
//
//            try {
//
//                $client = new OneSignalClient(
//                    config('onesignal.app_id'),
//                    config('onesignal.rest_api_key'),
//                    ''
//                );
//
//                $client->sendNotificationToExternalUser(
//                    $message,
//                    (string)$extUserId,
//                    $url,
//                    null,
//                    null,
//                    null,
//                    $heading
//                );
//                // }
//                $data = [
//                    'user_id' => $extUserId,
//                    'title' => $heading,
//                    'content' => $message,
//                    'type' => $type,
//                    'typeId' => $typeId,
//                    'extraId' => $externalId
//                ];
//                $params = [
//                    'json' => json_encode($data)
//                ];
//
//                $sql = 'SELECT `NOTIFICATIONLOG_CREATE`(:json) AS result';
//                $notify = wdb($sql, $params);
//
//                return $notify;
//            } catch (Exception $e) {
//                info('oneSignal', $e->getMessage());
//            }
//        }
//    }
//}

/*
Create wallet while login
*/

if (!function_exists('create_wallet_if_not_found')) {
    function create_wallet_if_not_found()
    {
        user_active();
        try {
            $data = [
                'ip_address' => get_ip_address(),
                'user_agent' => getUserAgent(),
            ];

            $params = [
                'json' => json_encode($data),
            ];

            $sql = 'SELECT `CREATE_USER_WALLET`(:json) AS result';
            $result = wdb($sql, $params);
            if ($result['status'] == 'success') {
                return true;
            }
        } catch (RequestException $e) {
            Log::info($e->getMessage());
        }

        return false;
    }
}

if (!function_exists('user_active')) {
    function user_active()
    {
        $userId = a_auth('user_id');

        if ($userId) {
            return DB::table('users')
                ->where('user_id', $userId)
                ->update(['last_active_at' => now()]);
        }

        return false;
    }
}


/*
Convert seconds to time for play
*/

if (!function_exists('secondsToTime')) {
    function secondsToTime($inputSeconds)
    {
        $secondsInAMinute = 60;
        $secondsInAnHour = 60 * $secondsInAMinute;
        // $secondsInADay = 24 * $secondsInAnHour;

        // Extract days
        // $days = floor($inputSeconds / $secondsInADay);

        // Extract hours
        // $hourSeconds = $inputSeconds % $secondsInADay;
        $hours = floor($inputSeconds / $secondsInAnHour);

        // Extract minutes
        $minuteSeconds = $inputSeconds % $secondsInAnHour;
        $minutes = floor($minuteSeconds / $secondsInAMinute);

        // Extract the remaining seconds
        $remainingSeconds = $minuteSeconds % $secondsInAMinute;
        $seconds = 0;
        if ($hours < 1) {
            $seconds = ceil($remainingSeconds);
        }

        // Format and return
        $timeParts = [];
        $sections = [
            // 'day' => (int)$days,
            'hr' => (int)$hours,
            'min' => (int)$minutes,
            'sec' => (int)$seconds,
        ];

        foreach ($sections as $name => $value) {
            if ($value > 0) {
                $timeParts[] = $value . ' ' . $name . ($value == 1 ? '' : 's');
            }
        }

        return implode(', ', $timeParts);
    }
}

/*
Generate pin code
*/
if (!function_exists('generate_pin_code')) {
    function generate_pin_code($length = 6, $easy_level = 4)
    {
        $sets = range(0, 9);
        shuffle($sets);
        $sets = str_split(str_repeat(substr(implode('', $sets), 0, $easy_level), $length));
        shuffle($sets);
        $pin_code = substr(ltrim(implode('', $sets), '0'), 0, $length);

        return $pin_code;
    }
}

/*
Change number format to any digits
*/
if (!function_exists('floor_number_format')) {
    function floor_number_format($number, int $decimals = 0, ?string $decimal_separator = '.', ?string $thousands_separator = ',')
    {
        $place_value = pow(10, $decimals);

        return number_format(floor($number * $place_value) / $place_value, $decimals, $decimal_separator, $thousands_separator);
    }
}

/*
Date difference from today
*/
if (!function_exists('date_diff_from_today')) {
    function date_diff_from_today($date)
    {
        $checkDate = Carbon::parse($date);
        $todayDate = Carbon::today();
        $diffDays = $checkDate->diffInDays($todayDate);

        return $diffDays;
    }
}

/*
Pretty date formatiing
*/
if (!function_exists('_date')) {
    function _date($date, $format = null, $dateonly = false)
    {
        $site_date_f = 'd M Y';
        $site_time_f = 'h:i';

        $setting_format = ($dateonly == true) ? $site_date_f : $site_date_f . ' ' . $site_time_f;

        $_format = (empty($format)) ? $setting_format : $format;
        $result = (!empty($date)) ? $date : now();

        return !empty($date) ? date($_format, strtotime($result)) : null;
    }
}

/*
Email Protection
*/
if (!function_exists('obfuscate_email')) {
    function obfuscate_email($str)
    {
        $email_front_length = strpos($str, '@', 0);
        $email_front = substr($str, 0, $email_front_length - 2);

        if ($email_front_length > 4) {
            //$first = substr($email_front, 0, 3);
            $middle = substr($email_front, 0, $email_front_length - 2);
            $last = substr($email_front, -2, strlen($email_front));
        } else {
            //$first = substr($email_front, 0, 1);
            $middle = substr($email_front, 0, 1);
            $last = substr($email_front, -1, 1);
        }
        return $email_front . '**' . substr($str, $email_front_length);
    }
}

/*
Get User By Transaction Relation
*/
if (!function_exists('getUserByTransaction')) {
    function getUserByTransaction($transaction_id = null)
    {
        $userInfo = DB::table('users as u')->join('wallets as w', 'w.user_id', '=', 'u.user_id')
            ->join('countries as con', 'u.display_country_id', '=', 'con.code')
            ->join('coins as c', 'c.coin_id', '=', 'w.coin_id')
            ->join('transactions as t', 't.wallet_id', '=', 'w.wallet_id')
            ->leftjoin('deposits as d', 'd.deposit_id', '=', 't.transaction_id')
            ->leftjoin('user_wallet_accounts as uw', 'uw.wallet_account_id', '=', 'w.wallet_account_id')
            ->where('t.transaction_id', $transaction_id)
            ->get()
            ->first();

        return [
            'user_id' => $userInfo->user_id,
            'email' => $userInfo->display_email,
            'display_name' => $userInfo->display_name,
            'displayCountry' => $userInfo->display_country_id ?? $userInfo->country_id,
            'display_country_name' => $userInfo->name ?? $userInfo->display_country_id,
            'wallet_account' => $userInfo->wallet_account,
            'coin' => $userInfo->coin,
            'transaction_amount' => $userInfo->transaction_amount,
            'transaction_fee' => $userInfo->transaction_fee,
            'transaction_id' => $userInfo->transaction_id,
            'deposit_wallet' => $userInfo->symbol = 'usd' ? $userInfo->from : strtoupper($userInfo->symbol),
        ];
    }
}

/*
File upload hook
*/

function imageUpload($image, $path)
{
    $hash = Str::random(8);
    $imageName = time() . $hash . '.' . $image->getClientOriginalExtension();
    $destinationPath = public_path($path);

    if (!File::isDirectory($destinationPath)) {
        File::makeDirectory($destinationPath, 0755, true, true);
    }

    // Move the file to the destination directory
    if (File::move($image->getRealPath(), $destinationPath . '/' . $imageName)) {
        return $imageName;
    }
}

function getAdsPackagesList(): array
{
    $sql = 'CALL `sp_ads_packages_list`()';
    $packages = wdb($sql);
    Log::info(print_r($packages, true));
    $packages = array_map(function ($q) {
        $contents = json_decode($q['description'], true);
        $content = array_map(function ($v) {
            return $v;
        }, $contents);
        $q['description'] = json_encode($content, JSON_UNESCAPED_UNICODE);
        if ((isset($q['ads_package_id'])) && isset($q['data'])) {
            $data = json_decode($q['data']);
            if ($q['ads_package_id'] == 1) {
                $paid = json_decode($data->paid);
                if (isset($data->paid) && isset($paid->rent_status) && $paid->rent_status == 1) {
                    $paid_max = isset($paid->rented_task) ? $paid->rented_task : 0;
                    $paid_view = isset($paid->rented_task) ? $paid->rented_task : 0;
                } elseif (isset($data->paid) && isset($paid->rent_status) && $paid->rent_status == 0) {
                    $paid_max = isset($paid->total_ads_daily) ? $paid->total_ads_daily : 0;
                    $paid_view = (isset($paid->total_ads_view_daily) ? $paid->total_ads_view_daily : 0);
                } elseif (isset($data->paid) && isset($paid->rent_status) && $paid->rent_status == 2) {
                    $paid_max = (isset($paid->total_ads_daily) ? $paid->total_ads_daily : 0) + (isset($paid->rented_task) ? $paid->rented_task : 0);
                    $paid_view = ((isset($paid->total_ads_view_daily) ? $paid->total_ads_view_daily : 0)) + (isset($paid->rented_task) ? $paid->rented_task : 0);
                } else {
                    $paid_max = 0;
                    $paid_view = 0;
                }
                $free = json_decode($data->free);

                $q['data'] = [
                    'free' => [
                        'max' => $free->max_daily,
                        'viewed' => min($free->total_ads_daily, $free->max_daily),
                        'not_paid_task' => $free->total_ads_count_not_paid,
                        'not_paid_amount' => floor_number_format($free->total_ads_count_not_paid * $free->per_ad_cost, 2),
                        'paid_task' => $free->total_ads_count_paid,
                        'paid_amount' => floor_number_format((min($free->total_ads_daily, $free->max_daily)) * $free->per_ad_cost, 3)
                    ],
                    'paid' => [
                        'max' => $paid_max,
                        'viewed' => $paid_view
                    ], 'earnings' => [
                        'delegated_earning' => $paid?->delegated_earning ?? 0,
                        'delegated_fee' => floor_number_format($paid?->delegated_fee ?? 0, 3),
                        'normal_earning' => $paid?->normal_earning ?? 0,
                    ]

                ];
            } elseif ($q['ads_package_id'] == 7) {
                if (isset($data->paid) && isset($data->paid->rent_status) && $data->paid->rent_status == 1) {
                    $paid_max = isset($data->paid->rented_task) ? $data->paid->rented_task : 0;
                    $paid_view = isset($data->paid->rented_task) ? $data->paid->rented_task : 0;
                } elseif (isset($data->paid) && isset($data->paid->rent_status) && $data->paid->rent_status == 0) {
                    $paid_max = isset($data->paid->total_ai_task_daily) ? $data->paid->total_ai_task_daily : 0;
                    $paid_view = (isset($data->paid->total_ai_task_view_daily) ? $data->paid->total_ai_task_view_daily : 0);
                } elseif (isset($data->paid) && isset($data->paid->rent_status) && $data->paid->rent_status == 2) {
                    $paid_max = (isset($data->paid->total_ai_task_daily) ? $data->paid->total_ai_task_daily : 0) + (isset($data->paid->rented_task) ? $data->paid->rented_task : 0);
                    $paid_view = ((isset($data->paid->total_ai_task_view_daily) ? $data->paid->total_ai_task_view_daily : 0)) + (isset($data->paid->rented_task) ? $data->paid->rented_task : 0);
                } else {
                    $paid_max = 0;
                    $paid_view = 0;
                }
                $free = json_decode($data->free);
                $q['data'] = [
                    'free' => [
                        'max' => $free->max_daily,
                        'viewed' => min($free->total_ai_task_daily, $free->max_daily),
                        'not_paid_task' => $free->total_ai_task_count_not_paid,
                        'not_paid_amount' => floor_number_format($free->total_ai_task_count_not_paid * $free->per_ai_task_cost, 2),
                        'paid_task' => $free->total_ai_task_count_paid,
                        'paid_amount' => floor_number_format((min($free->total_ai_task_daily, $free->max_daily)) * $free->per_ai_task_cost, 3),
                    ],
                    'paid' => [
                        'max' => $paid_max,
                        'viewed' => $paid_view
                    ], 'earnings' => [
                        'delegated_earning' => $data->paid->delegated_earning ?? 0,
                        'delegated_fee' => floor_number_format($data->paid->delegated_fee ?? 0, 3),
                        'normal_earning' => $data->paid->normal_earning ?? 0,
                    ]
                ];
            }
        }

        if ($q['is_rent'] == 0) {
            $q['unpaid'] = 0;
        }
        return $q;
    }, $packages);

    $sql = 'SELECT @found_rows AS result';
    $found_rows = wdb($sql);

    return [
        'status' => true,
        'code' => 200,
        'message' => 'data fetched',
        'data' => $packages,
    ];
}

function getProfile(): array
{
    $sql = 'SELECT `USER_DETAIL`() AS result';
    $result = wdb($sql);
    if ($result['status']) {
        return [
            'status' => $result['status'],
            'code' => $result['code'],
            'message' => $result['message'],
            'data' => $result['data'],
        ];
    } else {
        throw new ApiException($result['message'], $result['code']);
    }
}

function get_referral_by_level($user_id): array
{
    $page = $request->page ?? 1;
    $limit = $request->limit ?? 0;
    $level = $request->level ?? 1;
    $params = [
        'page' => $page,
        'limit' => $limit,
        'level' => $level,
        'user_id' => $user_id,
        'type' => $request->user_type ?? 0,
    ];
    $sql = 'CALL `sp_get_referrals_by_level`(:page,:limit,:level,:user_id,:type)';
    $referrals = wdb($sql, $params);
    $sql = 'SELECT @found_rows AS result';
    $found_rows = wdb($sql);
    $referrals = array_map(function ($q) {
        if (isset($q['commission'])) {
            $q['commission'] = json_decode($q['commission']);
        }
        return $q;
    }, $referrals);
    return [
        'status' => true,
        'code' => 200,
        'message' => 'data fetched',
        'data' => ['referrals' => $referrals, 'total' => $found_rows, 'per_page' => $limit],
    ];
}

function getAllByFilter($user_id): array
{
    create_wallet_if_not_found();
    $params = [
        'type' => null,
        'wallet_id' => null,
        'user_id' => $user_id ?? 0
    ];
    $sql = 'CALL `sp_user_wallet_all`(:type,:wallet_id,:user_id)';
    $wallets = wdb($sql, $params);
    $totalWalletBalance = floor_number_format(array_sum(array_column($wallets, 'wallet_balance')), 3);
    $mappedWallets = array_map(function ($wallet) {
        $wallet['wallet_name_only'] = $wallet['wallet_name'];
        return $wallet;
    }, $wallets);
    return [
        'status' => true,
        'code' => 200,
        'message' => 'data fetched',
        'data' => ['wallets' => $mappedWallets, 'totalWalletBalance' => $totalWalletBalance],
    ];
}

function getAllTransactions($wallet_type, $user_id, $limit = 10): array
{
    $page = $request->page ?? 1;

    $params = [
        'page' => $page,
        'limit' => $limit,
        'wallet_type' => $wallet_type,
        'user_id' => $user_id
    ];
    $sql = 'CALL `sp_alltransaction_pagination`(:page,:limit,:wallet_type,:user_id)';
    $result = wdb($sql, $params);

    $sql = 'SELECT @found_rows AS result';
    $found_rows = wdb($sql);

    return [
        'status' => true,
        'code' => 200,
        'message' => 'data fetched',
        'data' => ['transactions' => $result, 'total' => $found_rows, 'per_page' => $limit],
    ];
}

function removeImage($image, $path)
{
    $filePath = public_path($path . '/' . $image);

    if (File::exists($filePath)) {
        File::delete($filePath);

        if (!File::exists($filePath)) {
            return true; // File successfully deleted
        } else {
            return false; // Failed to delete the file
        }
    }

    return true;
}

if (!function_exists('get_user_status')) {
    function get_user_status($user_id)
    {
        $result = DB::table('ads_purchases')
            ->where('user_id', $user_id)
            ->whereIn('status', [1, 5])->get()->toArray();
        if ($result) {
            $userType = 'paid'; // Record exists, user is paid
        } else {
            $userType = 'free'; // Record does not exist, user is free
        }
        return $userType;
    }
}

if (!function_exists('file_upload')) {
    function file_upload($file, $path = null)
    {
        $dir_path = base_path('public/uploads/');

        if (!is_null($path)) {
            $dir_path = $dir_path . $path . '/';
        }

        $prefix = $path ? 'uploads/' . $path : 'uploads/';

        $ext = $file->extension();

        $unique_id = substr(str_pad(base_convert(strrev(substr(time(), 5) . substr(microtime(), 2, 6) . mt_rand(1000, 9999)), 10, 36), 12, mt_rand(10000, 99999), STR_PAD_RIGHT), 0, 12);
        $parts = str_split($unique_id, 3);

        $doc_name = array_pop($parts) . '.' . $ext;

        if (strlen($file->getClientOriginalName()) > 50) {
            $vars = [
                'status' => 'ERROR',
                'message' => 'Filename should not be greater than 50 characters.',
            ];

            return $vars;
        }

        if ($path == 'tickets') {
            $doc_name = $file->getClientOriginalName();
        }

        $filename = '';

        foreach ($parts as $part) {
            $dir_path = $dir_path . $part;
            $filename = $filename . $part;
            if (!is_dir($dir_path)) {
                mkdir($dir_path);
            }
            $dir_path = $dir_path . '/';
            $filename = $filename . '/';
        }

        $filename = $filename . $doc_name;
        $file_full_path = $dir_path . $doc_name;

        $img_object = @imagecreatefromstring(file_get_contents($file));

        if ($img_object !== false) {
            switch (strtolower($ext)) {
                case 'jpeg':
                case 'jpg':
                    imagejpeg($img_object, $file_full_path);
                    break;
                case 'png':
                    imagealphablending($img_object, false);
                    imagesavealpha($img_object, true);
                    imagepng($img_object, $file_full_path);
                    break;
                case 'gif':
                    imagegif($img_object, $file_full_path);
                    break;
                default:
                    break;
            }

            $img_object = null;

            if (file_exists($file_full_path) === true) {
                $vars = [
                    'status' => 'SUCCESS',
                    'filename' => $prefix . '/' . $filename,
                ];
            } else {
                $vars = [
                    'status' => 'ERROR',
                    'message' => 'File already exist',
                ];
            }
        } else {
            $vars = [
                'status' => 'ERROR',
                'message' => 'Invalid file type',
            ];
        }

        return $vars;
    }
}

/*
Remove file
*/
if (!function_exists('removeFile')) {
    function removeFile($path)
    {
        return file_exists($path) && is_file($path) ? @unlink($path) : false;
    }
}

if (!function_exists('getUserPackageByTransaction')) {
    function getUserPackageByTransaction($transaction_id = null)
    {
        $userInfo = DB::table('users as u')->join('wallets as w', 'w.user_id', '=', 'u.user_id')
            ->join('coins as c', 'c.coin_id', '=', 'w.coin_id')
            ->join('transactions as t', 't.wallet_id', '=', 'w.wallet_id')
            ->join('user_packages as up', 'up.transaction_id', 't.transaction_id')
            ->where('t.transaction_id', $transaction_id)
            ->first();
        return $userInfo->amount . ' USDT';
    }
}

if (!function_exists('getReferralLevelById')) {
    function getReferralLevelById($user_id = null, $referral = null)
    {
        $level = DB::select(
            'SELECT CASE
                WHEN JSON_SEARCH(l1, "one", ' . $referral . ') IS NOT NULL THEN "1"
                WHEN JSON_SEARCH(l2, "one", ' . $referral . ') IS NOT NULL THEN "2"
                WHEN JSON_SEARCH(l3, "one", ' . $referral . ') IS NOT NULL THEN "3"
                WHEN JSON_SEARCH(l4, "one", ' . $referral . ') IS NOT NULL THEN "4"
                WHEN JSON_SEARCH(l5, "one", ' . $referral . ') IS NOT NULL THEN "5"
                END AS level from `user_referrals` where `user_id` = ' . $user_id . ' limit 1'
        );
        if ($level) {
            return $level[0]->level;
        } else {
            return false;
        }
    }
}

if (!function_exists('getCommissionByLevel')) {
    function getCommissionByLevel($level = null)
    {
        $bonus = DB::table('levels')->where('levelId', $level)->first();
        if ($bonus) {
            return $bonus->referral_bonus . '%';
        } else {
            return false;
        }
    }
}

//check user restrictions
if (!function_exists('check_restriction')) {
    function check_restriction($restrictionType = null, $ecosystem = null)
    {
        $sql = 'SELECT `USER_CHECK_RESTRICTION`(:json) AS result';
        $result = wdb($sql, [
            'json' => json_encode([
                'restrictionType' => $restrictionType,
                'ecosystem' => $ecosystem,
            ]),
        ]);

        return $result;
    }
}

if (!function_exists('getNextChargerTransactionLogID')) {
    function getNextChargerTransactionLogID()
    {
        $charge = new \App\Models\ChargeSequence();
        $charge->save();

        return $charge->id;
    }
}

if (!function_exists('getCurrencyCodeById')) {
    function getCurrencyCodeById($currencyId)
    {
        $currency = DB::table('v2_charge_countries')->where('iso_un_code', $currencyId)->first();
        if ($currency) {
            return $currency->currency_code;
        }

        return 0;
    }

    function GetExchangeRateByCurrencyCode($currencyCode)
    {
        $exchangeRate = DB::select('SELECT rate FROM currencies WHERE currency_code = ?', [$currencyCode]);
        if (!empty($exchangeRate)) {
            return $exchangeRate[0]->rate;
        }
    }

    function getCountryDialCode($countryCode)
    {
        $dialCodeConfig = config('dial_code');
        foreach ($dialCodeConfig['dial_code'] as $countryInfo) {
            if ($countryInfo['code'] === $countryCode) {
                return $countryInfo['dial_code'];
            }
        }
        return null;
    }

    function getMaxLimit($wallets, $ad)
    {
        $maxLimit = $ad->max;

        if ($ad->type == 2) {
            $userWallet = $wallets->where('crypto_currency_id', $ad->crypto_currency_id)->first();
            $rate = getRate($ad);
            $userMax = $userWallet->balance * $rate;
            $maxLimit = $ad->max < $userMax ? $ad->max : $userMax;
        }

        return $maxLimit;
    }


    function checkIframeCAP($url, $embeddingDomain = null)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json(['error' => 'Invalid URL', 'status' => 0], 400);
        }
        try {
            $response = Http::withOptions(['allow_redirects' => false])->head($url);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch the URL: ' . $e->getMessage(), 'status' => 0], 500);
        }

        $headers = $response->headers();
        $headers = array_change_key_case($headers, CASE_LOWER);
        if (isset($headers['x-frame-options'])) {
            $xFrameOptions = strtolower($headers['x-frame-options'][0]);
            if ($xFrameOptions == 'deny' || $xFrameOptions == 'sameorigin') {
                return response()->json(['error' => "The site cannot be opened in an iframe due to X-Frame-Options: $xFrameOptions", 'status' => 0]);
            }
        }

        if (isset($headers['content-security-policy'])) {
            $csp = strtolower($headers['content-security-policy'][0]);
            if (strpos($csp, 'frame-ancestors') !== false) {
                if (preg_match('/frame-ancestors\s+(.*)/', $csp, $matches)) {
                    $frameAncestors = $matches[1];
                    $allowedDomains = explode(' ', trim($frameAncestors));
                    $allowed = false;
                    foreach ($allowedDomains as $domain) {
                        // Check if the embedding domain matches any allowed domain
                        if (preg_match('/^\'?(\*\.?[^\' ]+)\'?$/', $domain, $matches)) {
                            $allowedDomain = $matches[1];
                            // Convert wildcard to regex pattern
                            $pattern = str_replace('\*', '.*', preg_quote($allowedDomain));
                            if ($embeddingDomain && preg_match("/^$pattern$/", $embeddingDomain)) {
                                $allowed = true;
                                break;
                            }
                        }
                    }
                    if (!$allowed) {
                        return response()->json(['error' => "The site cannot be opened in an iframe due to Content-Security-Policy: frame-ancestors not allowing embedding on this domain.", 'status' => 0]);
                    }
                }
            }
        }

        return response()->json(['result' => "The site can be opened in an iframe.", 'status' => 1]);
    }

    function checkIframeCompatibility($url, $embeddingDomain = null)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json(['error' => 'Invalid URL'], 400);
        }
        try {
            $response = Http::withOptions(['allow_redirects' => false])->head($url);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch the URL: ' . $e->getMessage()], 500);
        }

        $headers = $response->headers();
        $headers = array_change_key_case($headers, CASE_LOWER);
        if (isset($headers['x-frame-options'])) {
            $xFrameOptions = strtolower($headers['x-frame-options'][0]);
            if ($xFrameOptions == 'deny' || $xFrameOptions == 'sameorigin') {
                return response()->json(['result' => "The site cannot be opened in an iframe due to X-Frame-Options: $xFrameOptions", 'status' => 0]);
            }
        }

        if (isset($headers['content-security-policy'])) {
            $csp = strtolower($headers['content-security-policy'][0]);
            if (strpos($csp, 'frame-ancestors') !== false) {
                if (preg_match('/frame-ancestors\s+(.*)/', $csp, $matches)) {
                    $frameAncestors = $matches[1];
                    $allowedDomains = explode(' ', trim($frameAncestors));
                    $allowed = false;
                    foreach ($allowedDomains as $domain) {
                        // Check if the embedding domain matches any allowed domain
                        if (preg_match('/^\'?(\*\.?[^\' ]+)\'?$/', $domain, $matches)) {
                            $allowedDomain = $matches[1];
                            // Convert wildcard to regex pattern
                            $pattern = str_replace('\*', '.*', preg_quote($allowedDomain));
                            if ($embeddingDomain && preg_match("/^$pattern$/", $embeddingDomain)) {
                                $allowed = true;
                                break;
                            }
                        }
                    }
                    if (!$allowed) {
                        return response()->json(['result' => "The site cannot be opened in an iframe due to Content-Security-Policy: frame-ancestors not allowing embedding on this domain.", 'status' => 0]);
                    }
                }
            }
        }

        return response()->json(['result' => "The site can be opened in an iframe.", 'status' => 1]);
    }

    function remove_image($type, $image_name)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://upload.coinminingpool.com/sync/remove/', [
            'image_name' => $image_name,
            'type' => $type,
            'api_key' => 'ujKN02Uytmn51dhQwiHDKGriRzKFQ4Rc4YRS7AIt+0s='
        ]);
        if ($response->successful()) {
            $return = $response->json();
            return response()->json($return, 200); // Return successful response
        } else {
            // Handle error response
            return response()->json([
                'error' => 'Failed to remove image',
                'details' => $response->body()
            ], $response->status()); // Return error with status code
        }
    }

    function removeCaptcha($type, $image_name)
    {
        if ($type == 1) {
            $repository = 'captchas';
        } else {
            $repository = 'captchas/free';
        }

        $filePath = public_path($repository . '/' . $image_name);
        if (file_exists($filePath)) {
            unlink($filePath);
            return "File deleted successfully.";
        } else {
            return "File not found.";
        }
    }

    function getRate($data)
    {
        $type = $data->type;
        $cryptoRate = $data->crypto->rate;
        $fiatRate = $data->fiat->rate;
        $margin = $data->margin;
        $fixed = $data->fixed_price ?? 0;
        $amount = $cryptoRate * $fiatRate;

        if ($fixed > 0) {
            $rate = $fixed;
        } else {
            $percentValue = $amount * $margin / 100;
            $rate = $type == 1 ? $amount - $percentValue : $amount + $percentValue;
        }

        return round($rate, 2);
    }

//    function translateText($text)
//    {
//        $to = App::getLocale();
//        $tr = new GoogleTranslate($to);
//        return $tr->translate($text);
//    }
//    function translateTextPreservingHtml($html)
//    {
//        $to = App::getLocale();
//        $tr = new GoogleTranslate($to);
//
//        $dom = new DOMDocument('1.0', 'UTF-8');
//        libxml_use_internal_errors(true);
//
//        // Load HTML content with UTF-8 encoding
//        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
//        libxml_clear_errors();
//
//        $xpath = new DOMXPath($dom);
//
//        // Translate all non-whitespace text nodes
//        foreach ($xpath->query('//text()[normalize-space()]') as $textNode) {
//            $original = $textNode->nodeValue;
//            $translated = $tr->translate($original);
//            $textNode->nodeValue = $translated; // No decoding needed now
//        }
//
//        // Convert output back to UTF-8
//        return mb_convert_encoding($dom->saveHTML(), 'UTF-8', 'HTML-ENTITIES');
//    }
    function generateCaptchaCode($length = 6)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$&*%';
        $charactersLength = strlen($characters);
        $captchaCode = '';
        for ($i = 0; $i < $length; $i++) {
            $captchaCode .= $characters[rand(0, $charactersLength - 1)];
        }
        return $captchaCode;
    }

    function getNewCaptchaTest($type, $savePath = 'captcha.png')
    {
        $captchaCode = generateCaptchaCode();
        $width = 120;
        $height = 30;

        $image = imagecreatetruecolor($width, $height);

        // Background and text colors
        $bgColor = imagecolorallocate($image, rand(100, 255), rand(100, 255), rand(100, 255));
        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

        // Add noise: lines
        for ($i = 0; $i < 5; $i++) {
            $lineColor = imagecolorallocate($image, rand(180, 220), rand(180, 220), rand(180, 220));
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $lineColor);
        }

        // Add noise: random dots
        for ($i = 0; $i < 100; $i++) {
            $dotColor = imagecolorallocate($image, rand(200, 255), rand(200, 255), rand(200, 255));
            imagesetpixel($image, rand(0, $width), rand(0, $height), $dotColor);
        }

        // Set path to your .ttf font
        $font = storage_path('fonts/regular.ttf');
        for ($i = 0; $i < strlen($captchaCode); $i++) {
            $fontSize = rand(18, 20);
            $angle = rand(-10, 10);
            $x = 10 + $i * 17;
            $y = rand(20, 21);
            $textColor = imagecolorallocate($image, rand(0, 100), rand(0, 100), rand(0, 100));
            imagettftext($image, $fontSize, $angle, $x, $y, $textColor, $font, $captchaCode[$i]);
        }

        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        $response = Http::attach(
            'image', $imageData, 'generated_image.png'
        )->post('https://upload.coinminingpool.com/sync/upload/', [
            'type' => 1,
            'api_key' => 'ujKN02Uytmn51dhQwiHDKGriRzKFQ4Rc4YRS7AIt+0s='
        ]);
        imagedestroy($image);
        if ($response->successful()) {
            $return = $response->json();
            $data = [
                'image' => $return['data']['image_name'],
                'captcha_code' => $captchaCode
            ];
        } else {
            $data = [
                'image' => null,
                'captcha_code' => null
            ];
        }
        return $data;
    }


    function getNewCaptcha($type)
    {
        $captchaCode = generateCaptchaCode();
        $width = 120;
        $height = 30;

        $image = imagecreatetruecolor($width, $height);

        // Background and text colors
        $bgColor = imagecolorallocate($image, rand(100, 255), rand(100, 255), rand(100, 255));
        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

        // Add noise: lines
        for ($i = 0; $i < 5; $i++) {
            $lineColor = imagecolorallocate($image, rand(180, 220), rand(180, 220), rand(180, 220));
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $lineColor);
        }

        // Add noise: random dots
        for ($i = 0; $i < 100; $i++) {
            $dotColor = imagecolorallocate($image, rand(200, 255), rand(200, 255), rand(200, 255));
            imagesetpixel($image, rand(0, $width), rand(0, $height), $dotColor);
        }

        // Set path to your .ttf font
        $font = storage_path('fonts/regular.ttf');
        for ($i = 0; $i < strlen($captchaCode); $i++) {
            $fontSize = rand(18, 20);
            $angle = rand(-10, 10);
            $x = 10 + $i * 17;
            $y = rand(20, 21);
            $textColor = imagecolorallocate($image, rand(0, 100), rand(0, 100), rand(0, 100));
            imagettftext($image, $fontSize, $angle, $x, $y, $textColor, $font, $captchaCode[$i]);
        }

        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        $response = Http::attach(
            'image', $imageData, 'generated_image.png'
        )->post('https://upload.coinminingpool.com/sync/upload/', [
            'type' => 1,
            'api_key' => 'ujKN02Uytmn51dhQwiHDKGriRzKFQ4Rc4YRS7AIt+0s='
        ]);
        imagedestroy($image);
        if ($response->successful()) {
            $return = $response->json();
            $data = [
                'image' => $return['data']['image_name'],
                'captcha_code' => $captchaCode
            ];
        } else {
            $data = [
                'image' => null,
                'captcha_code' => null
            ];
        }
        return $data;
    }
}
