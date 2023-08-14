<?php

namespace Local\Illuminate\UserAuthentication;

interface UserAuthenticateCapableInterface
{
    /**
     * Returns user's UUID
     * @return string
     */
    public function uuid(): string;
}
