<?php

namespace Local\App\Api\ContactAdd;

use Local\Illuminate\Api;

interface EndpointInterface extends Api\EndpointInterface
{
    public const QUERY_PARAM_UUID = "uuid";
    public const BODY_PARAM_NAME = "name";
    public const BODY_PARAM_PHONE = "phone";
}
