<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyOtpRequest;
use App\Repositories\Contracts\ContentInterface;
use App\Repositories\Contracts\RegisterInterface;
use Illuminate\Http\Request;

class AddLocalitiesController extends Controller
{
    /**
     * @var ContentInterface
     */
    private $locality;

    /**
     * @param  ContentInterface  $locality
     */
    public function __construct(ContentInterface $locality)
    {
        $this->locality = $locality;
    }

    public function __invoke(Request $request)
    {
        $result = $this->locality->addLocality($request);

        return $this->response($result['code'], $result['status'], $result['data'], $result['message']);
    }
}
