<?php

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
require_once(__DIR__ . '/../vendor/autoload.php');

class Auth {
    public static $key = "ASIASL56UJYSWQF7J3QK";
    public static $session_token = "FwoGZXIvYXdzEE0aDHA/HKZVGyQ1cI18wCLJAcD2Luy+O21PJIGdtpkGNE1wOS65Tf6JcYP7dQ4Q6M1gK4XvJxoSwdoT+qcCBuUzGxLw7FVOJnywYBL+OMF4iyjfRgRDSqJg4KbZnv8D7nqTpCuVoZJqfnxcn/71pEV8Mff22X7xZ4AdKwVVisFsYoWi4c1JTB2fZRZH35wU/MfkbvR8eh+s7LRdR0oZHkcc4Xcui7JPCYemWe9WxQMHXcxjGv/PkDrC1eJiPTQHsBvjoxUCnFOkybT84VpLWM/vQOWJG61m7nofvijA0a6BBjItNBCcYGaxV6sJATvkKv1DiXMnea6Hje4SqaWkWj7EmzGsTonWbTE0fIKkhQBd";
    public static $secret = "LG8BuDT+o/hTqQuR1eCmxJkjKEdPS817i4BRffzf";
    public static $region = "us-east-1";
    public static $version = "2016-04-18";
    public static $url = "https://festivalcloud.auth.us-east-1.amazoncognito.com/login?client_id=3jf46kc1urfgujpmb5ar23n0l3&response_type=token&scope=aws.cognito.signin.user.admin+email+openid+phone+profile&redirect_uri=http://localhost/festivalCloud/index.php";

    public $access_token;
    public $email;
    public $name;
    public $phone;
    public $isLoggedIn=false;    

    public function redirect($msg) {
        $url = BASE_URL . "?error=" . $msg;
        
        header("Location: " . $url, true);
        exit();
    }

    public function __construct() {
        if(isset($_GET["access_token"])) {
            $this->authenticate();
        }
    }

    public function loggedIn() {
        return $this->isLoggedIn;
    }

    public static function getSignInURL() {
        return Auth::$url;
    }

    public function getAccessToken() {
        return $this->access_token;
    }

    public function authenticate() {
        $this->access_token = $_GET["access_token"];
         $region = 'us-east-1';
         $version = '2016-04-18';
        
        //Authenticate with AWS Acess Key and Secret
        $client = new CognitoIdentityProviderClient([
            'version' => Auth::$version,
            'region' => Auth::$region,
            'credentials' => [
                            'key'    => Auth::$key,
                            'secret' => Auth::$secret,
                        ],
        ]);
        
        try {
            //Get the User data by passing the access token received from Cognito
            $result = $client->getUser([
                'AccessToken' => $this->access_token,
            ]);
            
            
            //print_r($result);
            
            // $user_email = "";
            // $user_phone_number = "";
                
            //Iterate all the user attributes and get email and phone number
            $userAttributesArray = $result["UserAttributes"];
            foreach ($userAttributesArray as Auth::$key => $val) {
                if($val["Name"] == "email"){
                    $this->email = $val["Value"];
                }
                if($val["Name"] == "phone_number"){
                    $this->phone = $val["Value"];
                }
            }	

            $this->isLoggedIn = true;
            
            if(isset($_GET["logout"]) && $_GET["logout"] == 'true'){
                //This will invalidate the access token
                $result = $client->globalSignOut([
                    'AccessToken' => $this->access_token,
                ]);
                
                $this->isLoggedIn = false;
                var_dump(BASE_URL);
                header("Location: " . BASE_URL, true);
                
            }
            
            
        } catch (\Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException $e) {
            echo 'FAILED TO VALIDATE THE ACCESS TOKEN. ERROR = ' . $e->getMessage();
        } catch (\Aws\Exception\CredentialsException $e) {
            echo 'FAILED TO AUTHENTICATE AWS KEY AND SECRET. ERROR = ' . $e->getMessage();
        }
    }
}

