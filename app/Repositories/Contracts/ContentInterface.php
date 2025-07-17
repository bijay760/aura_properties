<?php


namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface ContentInterface
{
    public function SendOTP(Request $request);
    public function addCities(Request $request);
    public function addLocality(Request $request);
    public function addProject(Request $request);
    public function getCities(Request $request);
    public function getProjects(Request $request);
    public function getLocality(Request $request);
    public function verifyOtp(Request $request);
}
