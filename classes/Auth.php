<?php

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
require_once(__DIR__ . '/../vendor/autoload.php');

class Auth {
    private static $key = "ASIASL56UJYS2BNOUNOQ";
    private static $secret = "e6GivHb70+UWEMJke1eynqxxbaDqmbwjiqA5bgrg";
    private static $region = "us-east-1";
    private static $version = "2016-04-18";
    public static $url = "https://festival-app.auth.us-east-1.amazoncognito.com/login?client_id=788pbdt0lnh6o7taenjn40g8h3&response_type=token&scope=aws.cognito.signin.user.admin+email+openid+profile&redirect_uri=http://localhost:8888/festivalCloud/index.php";

    public $access_token;
    public $email;
    public $name;
    public $phone;
    public $isLoggedIn=false;    


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
            // echo '<h2>Logged-In User Attributes</h2>';
            // echo '<p>User E-Mail : ' . $user_email . '</p>';
            // echo '<p>User Phone Number : ' . $user_phone_number . '</p>';
            // echo "<a href='?logout=true&access_token=$access_token'>SIGN OUT</a>";
            
        
            if(isset($_GET["logout"]) && $_GET["logout"] == 'true'){
                //This will invalidate the access token
                $result = $client->globalSignOut([
                    'AccessToken' => $access_token,
                ]);
                
                header("Location: " + Auth::$url);
                
            }
            
            
        } catch (\Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException $e) {
            echo 'FAILED TO VALIDATE THE ACCESS TOKEN. ERROR = ' . $e->getMessage();
        } catch (\Aws\Exception\CredentialsException $e) {
            echo 'FAILED TO AUTHENTICATE AWS KEY AND SECRET. ERROR = ' . $e->getMessage();
        }
    }
}

