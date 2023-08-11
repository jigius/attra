<?php

namespace Local\App\Api\ContactDelete;

use Local\Illuminate\Api;

interface EndpointInterface extends Api\EndpointInterface
{
    public const QUERY_PARAM_UUID = "uuid";
    public const BODY_PARAM_ID = "id";
}
