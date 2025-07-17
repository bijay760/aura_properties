<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\PropertiesInterface;
use Illuminate\Http\Request;
use App\Http\Requests\PostPropertyRequest;

class PostPropertyController extends Controller
{
    /**
     * @var PropertiesInterface
     */
    private $property;

    /**
     * @param  PropertiesInterface  $property
     */
    public function __construct(PropertiesInterface $property)
    {
        $this->property = $property;
    }

    /**
     * @return array
     */
    public function __invoke(PostPropertyRequest $request)
    {
        $result = $this->property->postProperty($request);
        return $this->response($result['code'], $result['status'], $result['data'], $result['message']);
    }
}
