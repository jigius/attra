<?php

namespace Local\App;

use Local\Illuminate\UserAuthentication\UserAuthenticateCapableInterface;
use Ramsey\Uuid\Uuid;
use RuntimeException;

/**
 * Dumb implementation of the user's authentication with the using of cookies.
 *
 * Authenticates the user's request via the cookie.
 * If the cookie is not defined - generates a new one.
 * The cookie contains user's uuid which is used into future requests data via REST API.
 */
final class UserAuthCookieDumb implements UserAuthenticateCapableInterface
{
    /**
     * @var string
     */
    private string $cookieName;
    /**
     * @var int
     */
    private int $ttl;
    
    /**
     * Cntr
     * @param string $cookieName
     * @param int $ttl
     */
    public function __construct(string $cookieName, int $ttl)
    {
        $this->cookieName = $cookieName;
        $this->ttl = $ttl;
    }
    
    /**
     * @inheritDoc
     * @throws RuntimeException
     */
    public function uuid(): string
    {
        if (isset($_COOKIE[$this->cookieName]) && Uuid::isValid($_COOKIE[$this->cookieName])) {
            $uuid = $_COOKIE[$this->cookieName];
        } else {
            $uuid = (string)Uuid::uuid4();
            if (setcookie($this->cookieName, $uuid, time() + $this->ttl) === false) {
                throw new RuntimeException("could not set a cookie", 500);
            }
        }
        return $uuid;
    }
}
