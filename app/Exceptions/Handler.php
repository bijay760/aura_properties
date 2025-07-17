<?php

namespace App\Exceptions;

use Carbon\Carbon;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        ApiException::class,
        LoginException::class,
        JwtException::class,
        ValidationException::class,
        RegisterException::class
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            $this->sendEmail($e);
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if (
            $e instanceof ApiException ||
            $e instanceof LoginException ||
            $e instanceof JwtException ||
            $e instanceof ValidationException ||
            $e instanceof RegisterException
        ) {
            return $e->getResponse();
        }
        return parent::render($request, $e);
    }

    public function sendEmail(Throwable $exception)
    {
        try {

            $content['message'] = $exception->getMessage();
            $content['file'] = $exception->getFile();
            $content['line'] = $exception->getLine();
            $content['trace'] = $exception->getTrace();

            $content['url'] = request()->url();
            $content['body'] = request()->all();
            $content['ip'] = request()->ip();

            $content['user_id'] = a_auth('user_id');
            $content['timestamp'] = Carbon::now()->toDateTimeString();
            $content['date'] = Carbon::now()->toDateString();
            if (config('app.env') != 'local') {
                $redisKeys = Redis::keys('error:*');
                $todaysMsg = [];
                $mailSend = false;

                if (!empty($redisKeys)) {
                    foreach ($redisKeys as $key) {
                        $ex = explode(':', $key);
                        $stored = Redis::hgetall(str_replace(Str::slug(config('app.name'), '_') . '_database_', '', $key));
                        if (Carbon::parse(substr($ex[1], 2, -3))->isToday()) {
                            if (!in_array($stored['message'], $todaysMsg, true)) {
                                array_push($todaysMsg, $stored['message']);
                            }
                        }
                    }
                    if (!in_array($content['message'], $todaysMsg, true)) {
                        $mailSend = true;
                    }
                } else {
                    $mailSend = true;
                }
                Redis::hmset('error:' . $content['timestamp'], [
                    'message' => $content['message'],
                    'file' => $content['file']
                ]);
                if ($mailSend) {
                    Mail::to(config('site-admins.email'))->send(new ExceptionMail($content));
                }
            }
        } catch (Throwable $exception) {
            Log::error($exception);
        }
    }
}
