<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyOtpRequest;
use App\Repositories\Contracts\ContentInterface;
use App\Repositories\Contracts\RegisterInterface;
use Illuminate\Http\Request;

class GetProjectsController extends Controller
{
    /**
     * @var ContentInterface
     */
    private $cities;

    /**
     * @param  ContentInterface  $cities
     */
    public function __construct(ContentInterface $cities)
    {
        $this->cities = $cities;
    }

    public function __invoke(Request $request)
    {
        $result = $this->cities->getProjects($request);

        return $this->response($result['code'], $result['status'], $result['data'], $result['message']);
    }
}
