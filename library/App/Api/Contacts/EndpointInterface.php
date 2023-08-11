<?php

namespace Local\App\Api\Contacts;

use Local\Illuminate\Api;

interface EndpointInterface extends Api\EndpointInterface
{
    public const QUERY_PARAM_UUID = "uuid";
}
