<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\PropertiesInterface;
use Illuminate\Http\Request;

class PostTempPropertyController extends Controller
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
    public function __invoke(Request $request)
    {
        $result = $this->property->getCategories($request);
        return $this->response($result['code'], $result['status'], $result['data'], $result['message']);
    }
}
