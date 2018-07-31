<?php
return array(
    'jwt' => array(
        'key'       => 'moorecloud',     // Key for signing the JWT's, I suggest generate it with base64_encode(openssl_random_pseudo_bytes(64))
        'algorithm' => 'HS512' // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
        ),
    'database' => array(
        'user'     => 'mcuser', // Database username
        'password' => 'M_cTue16:45', // Database password
        'host'     => 'localhost', // Database host
        'name'     => 'moorecloud', // Database schema name
        'table'    => 'user_accounts'
    ),
    'serverName' => 'localhost',
);
?>
