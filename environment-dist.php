<?php

return [
    "pdo" => [
        "dsn" => "mysql:host=localhost;port=3306;dbname=attra;charset=utf8",
        "login" => "user",
        "password" => "secret"
    ],
    "userAuth" => [
        "ttl" => 86400 * 7 // 7 days
    ],
    "ui" => [
        "phoneMask" => "9 999 999 99 99"
    ]
];
