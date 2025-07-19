<?php


namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface PropertiesInterface
{
    public function getCategories(Request $request):array;
    public function postProperty(Request $request):array;
    public function getProperty():array;
}
