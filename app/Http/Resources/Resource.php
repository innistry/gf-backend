<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    protected $responseCode;
    protected $responseLocation;

    public function withCode(int $code)
    {
        $this->responseCode = $code;

        return $this;
    }

    public function withLocation(string $location)
    {
        $this->responseLocation = $location;

        return $this;
    }

    public function withResponse($request, $response)
    {
        if ($this->responseCode) {
            $response->setStatusCode($this->responseCode);
        }

        if ($this->responseLocation) {
            $response->header('Location', $this->responseLocation);
        }
    }
}
