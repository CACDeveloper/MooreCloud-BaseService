<?php
// James DO

chdir(dirname(__DIR__));

require_once('vendor/autoload.php');

use Zend\Config\Config;
use Zend\Config\Factory;
use Zend\Http\PhpEnvironment\Request;
use Firebase\JWT\JWT;
Predis\Autoloader::register();

define('ALMOST_EXPIRED_GAP',3600);

function MCAuthorization($jwt){
    
    $response = "";
    if(valid($jwt)){
        if(almost_expired($jwt)){
            $newjwt = renew($jwt);
            echo json_encode([$newjwt, $response]);
        }
        else{
            // business logic do_things;
            echo json_encode([$jwt, $response]);
        }
    } else {
        header('HTTP/1.0 401 Unauthorized');
    }
}


// check almost expired time
function almost_expired($jwt) {
    //$ALMOST_EXPIRED_RENEW = 3600;
    $almost_expired_gap = ALMOST_EXPIRED_GAP;

    // if we use class we dont need to repeat get configuration
    $config = Factory::fromFile('config/config-moorecloud.php', true);
    $secretKey = base64_decode($config->get('jwt')->get('key'));
    $algorithm = $config->get('jwt')->get('algorithm');
    JWT::$leeway = 60; 
        $decoded = JWT::decode($jwt,
        $secretKey,
        [$algorithm]
    );
    $decoded_array = (array) $decoded;
    
    $expire = $decoded_array['exp'];
    $gap = $expire - time();
    if ($gap > 0 && $gap <= $almost_expired_gap) {
        echo "need renew";
        return true;
    } else {
        echo "good token";
    }

    return false;
}


function valid($jwt){
    //try decode
    //if decoded
        // see if:
            // 1 token expired
            // 2 mark need reissue (redis check)
        //all pass, return true
    //else
        // return false
    $config = Factory::fromFile('config/config-moorecloud.php', true);
    $secretKey = base64_decode($config->get('jwt')->get('key'));
    $algorithm = $config->get('jwt')->get('algorithm');
   // $decoded = [];
    try{
        JWT::$leeway = 60; 
        $decoded = JWT::decode($jwt,
            $secretKey,
            [$algorithm]
        );
    } catch (Exception $e) {
        echo $e;
        //return false;
    } 
    
    $decoded_array = (array) $decoded;
    $userInfo = (array) $decoded_array['data'];
    // decode works
    // echo "Decode:\n" . print_r($decoded_array, true) . "\n"; 


    // redis mark check;

    $uid = $userInfo["userId"];
    $client = new Predis\Client();
    $return = json_decode($client->get($uid));
    $return_array = (array) $return;
    if ($return_array['Mark'] == 'need_reissued') {
        echo "reissued";
        return false;
    } else {
        echo "not Mark";
    }

    return true;
}

function renew($jwt) {

}










function test() {
    $config = Factory::fromFile('config/config-moorecloud.php', true);

    $secretKey = base64_decode($config->get('jwt')->get('key'));
    $algorithm = $config->get('jwt')->get('algorithm');
    //var_dump($config->get('jwt')->get('algorithm'));
    

    $tokenId    = base64_encode(openssl_random_pseudo_bytes(32));
    $issuedAt   = time();
    $notBefore  = $issuedAt + 10;  //Adding 10 seconds
    $expire     = $notBefore + 4000; // Adding 60 seconds
    $serverName = $config->get('serverName');

    $uid = '01';

    /*
        * Create the token as an array
        */
    $data = [
        'iat'  => $issuedAt,         // Issued at: time when the token was generated
        'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
        'iss'  => $serverName,       // Issuer
        'nbf'  => $notBefore,        // Not before
        'exp'  => $expire,           // Expire
        'data' => [                  // Data related to the signer user
            'userId'   => $uid, // userid from the users table
            'userEmail' => 'jd@jd.com', // User name
        ],
    ];

    $jwt = JWT::encode(
        $data,      //Data to be encoded in the JWT
        $secretKey, // The signing key
        $algorithm  // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
    );


    $client = new Predis\Client();
    $rlist = ["jwt" => $jwt, "Mark" => "need_reissued"];
    $rlist = json_encode($rlist);
    $client->set($uid, $rlist);
    almost_expired($jwt);
    //valid($jwt);
    //MCAuthorization($jwt);


}

test();