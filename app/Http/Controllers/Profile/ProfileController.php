<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProfileInterface;

class ProfileController extends Controller
{
    /**
     * @var ProfileInterface
     */
    private $profile;

    /**
     * @param  ProfileInterface  $profile
     */
    public function __construct(ProfileInterface $profile)
    {
        $this->profile = $profile;
    }

    /**
     * @return array
     */
    public function __invoke()
    {
        $result = $this->profile->getProfile();

        return $this->response($result['code'], $result['status'], $result['data'], $result['message']);
    }
}
