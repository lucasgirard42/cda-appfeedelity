<?php

namespace App\Controller\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class EmptyController
{
    public function __invoke()
    {
        return new HttpFoundationResponse();
    }
}