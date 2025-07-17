<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Request;
class ResponseHelper implements Json
{
    private $responseStrucuture;
    private $api;

    public function __construct()
    {
        $this->responseStrucuture = [];
        $this->api = config('global.apiRoute');
    }

    public function response(int $status, bool $error, string|array|null $response, string $message): array
    {
        if (!empty($dependencies['dependencies'])) {
            $response = $this->pushHateoas($response, $dependencies['dependencies']);
        }

        return $this->responseStrucuture = [
            "code"    => $status,
            "status"  => true,
            "data"    => $response,
            "message" => $message
        ];
    }

    // PARTIAL //
    private function pushHateoas(array $response, array $dependencies): array
    {
        foreach ($dependencies as $value) {
            for ($index = 0; $index < count($response); $index++) {
                if (!empty($response[$index][$value])) {
                    if (isset($response[$index][$value][0])) {
                        for ($index = 0; $index < count($response); $index++) {
                            for ($i = 0; $i < count($response[$index][$value]); $i++) {
                                $filter = str_replace("_", "-", $this->api . $value . '/' . $response[$index][$value][$i]['id']);
                                $response[$index][$value][$i]['url'] = $filter;
                            }
                        }
                    } else {
                        for ($index = 0; $index < count($response); $index++) {
                            $filter = str_replace("_", "-", $this->api . $value . '/' . $response[$index][$value]['id']);
                            $response[$index][$value]['url'] = $filter;
                        }
                    }
                }
            }
        }
        return $response;
    }
}
