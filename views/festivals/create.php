<?php
namespace AWSCognitoApp;
require_once('../../vendor/autoload.php');
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
require_once '../../utils/functions.php';

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <?php require '../../utils/styles.php'; ?>
        <?php require '../../utils/scripts.php'; ?>
    </head>
    <body>
        <?php require '../../utils/toolbar.php'; ?>

        <?php
if(!isset($_GET["access_token"]))
{
	
?>
<script>
	var url_str = window.location.href;
	//On successful authentication, AWS Cognito will redirect to Call-back URL and pass the access_token as a request parameter. 
	//If you notice the URL, a “#” symbol is used to separate the query parameters instead of the “?” symbol. 
	//So we need to replace the “#” with “?” in the URL and call the page again.
	
	if(url_str.includes("#")){
		var url_str_hash_replaced = url_str.replace("#", "?");
		window.location.href = url_str_hash_replaced;
	}
	
</script>

<?php
}
else{
?>

<?php
$access_token = $_GET["access_token"];

$region = 'us-east-1';
$version = '2016-04-18';

//Authenticate with AWS Acess Key and Secret
$client = new CognitoIdentityProviderClient([
    'version' => $version,
    'region' => $region,
	'credentials' => [
                    'key'    => 'ASIASL56UJYS2BNOUNOQ',
                    'secret' => 'e6GivHb70+UWEMJke1eynqxxbaDqmbwjiqA5bgrg',
                ],
]);

try {
	//Get the User data by passing the access token received from Cognito
    $result = $client->getUser([
        'AccessToken' => $access_token,
	]);
	
	
	//print_r($result);
	
	$user_email = "";
	$user_phone_number = "";
		
	//Iterate all the user attributes and get email and phone number
	$userAttributesArray = $result["UserAttributes"];
	foreach ($userAttributesArray as $key => $val) {
		if($val["Name"] == "email"){
			$user_email = $val["Value"];
		}
		if($val["Name"] == "phone_number"){
			$user_phone_number = $val["Value"];
		}
	}	
	echo '<h2>Logged-In User Attributes</h2>';
	echo '<p>User E-Mail : ' . $user_email . '</p>';
	echo '<p>User Phone Number : ' . $user_phone_number . '</p>';
    echo "<a href='?logout=true&access_token=$access_token'>SIGN OUT</a>";
    
    ?>

<div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php require '../../utils/header.php'; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form method="POST"
                          action="store.php?<?= $query?>"
                          role="form"
                          class="form-horizontal"
                          enctype="multipart/form-data"
                          >
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <h2>Create festival form</h2>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-md-3 control-label">Title</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="title" name="title" value="<?= old('title') ?>" />
                            </div>
                            <div class="col-md-3 error">
                                <?php error('title'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-md-3 control-label">Description</label>
                            <div class="col-md-6">
                              <textarea id="description" name="description" rows="4" cols="50"><?= old('description') ?></textarea>
                            </div>
                            <div class="col-md-3 error">
                                <?php error('description'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="city" class="col-md-3 control-label">City</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="city" name="city" value="<?= old('city') ?>" />
                            </div>
                            <div class="col-md-3 error">
                                <?php error('city'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="start_date" class="col-md-3 control-label">Start Date</label>
                            <div class="col-md-6">
                                <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="<?= old('start_date') ?>" />
                            </div>
                            <div class="col-md-3 error">
                                <?php error('start_date'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="end_date" class="col-md-3 control-label">End Date</label>
                            <div class="col-md-6">
                                <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="<?= old('end_date') ?>" />
                            </div>
                            <div class="col-md-3 error">
                                <?php error('end_date'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="image_path" class="col-md-3 control-label">Image</label>
                            <div class="col-md-6">
                                <input type="file" class="form-control" id="image_path" name="image_path" value="" />
                            </div>

                            <div class="col-md-3 error">
                                <?php error('image_path'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                            <a href="index.php?<?= $query ?>"class="btn btn-default">Cancel</a>
                                <button type="submit" class="btn btn-primary pull-right">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php require '../../utils/footer.php'; ?>
                </div>
            </div>
        </div>

    <?php
	
	if(isset($_GET["logout"]) && $_GET["logout"] == 'true'){
		//This will invalidate the access token
		$result = $client->globalSignOut([
			'AccessToken' => $access_token,
		]);
		
		header("Location: https://festivalcloud.auth.us-east-1.amazoncognito.com/login?client_id=3jf46kc1urfgujpmb5ar23n0l3&response_type=token&scope=aws.cognito.signin.user.admin+email+openid+phone+profile&redirect_uri=http://localhost/demo_cognito_client_app/secure_page.php");
		http://localhost/demo_cognito_client_app/secure_page.php#id_token=eyJraWQiOiJqT0YwRmlQTVlmUHdJUTlFQ3BnNmZ4K0Yyd3dHXC9Zbzc1SWJ5NHFVd3VZVT0iLCJhbGciOiJSUzI1NiJ9.eyJhdF9oYXNoIjoibGs2RDVBQmJkUFg3Z2pCUHd1d2x0QSIsInN1YiI6IjcwZjc4ZmVkLTEzN2YtNDY5YS04M2YxLTRjNzIyYzExYTlmNiIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJpc3MiOiJodHRwczpcL1wvY29nbml0by1pZHAudXMtZWFzdC0xLmFtYXpvbmF3cy5jb21cL3VzLWVhc3QtMV9mNEdPRXBDRW4iLCJwaG9uZV9udW1iZXJfdmVyaWZpZWQiOnRydWUsImNvZ25pdG86dXNlcm5hbWUiOiJ0ZXN0dXNlciIsImF1ZCI6IjNqZjQ2a2MxdXJmZ3VqcG1iNWFyMjNuMGwzIiwidG9rZW5fdXNlIjoiaWQiLCJhdXRoX3RpbWUiOjE2MTIyOTIwODcsInBob25lX251bWJlciI6IisxMTIzNDU2Nzg5MCIsImV4cCI6MTYxMjI5NTY4NywiaWF0IjoxNjEyMjkyMDg3LCJlbWFpbCI6InRlc3RAZ21haWwuY29tIn0.YxfwT6bLuMyQXRTSvR9ej14kMZ4xkJXSRGOP3QOh0A-o5ERY0vZHCfW7Fly0Ykeg-0OKAhAAky5uojdHL6gIDLim-TrjkC_SbdmB6BeAdIFhhQjFcJdCiVi7jv8zbBW7QDig5m2gbQrqwzLOsrfu-5MygZpjOWd8hCbTzR0QvcmOHw59oTkRRJRdTNopUKpXJgFWaYj6-ktLfa_rhJC4uJliIbNKT6GGu_DYaRKt5HBb8FVH2wHPQxNc4Lmios3K1BfIR-c9qu1WoI9OlQA2WywBUdZu1iN1GH0O0o22QX9j27Of3_jXqitl30OGZjxHRlpXgWOQyCMTRcTlAE1J9Q&access_token=eyJraWQiOiJXYk05eXplRlp5eXRMMnBwS3JCcFpmTmxwdUNiXC94S1VnekUwZG5OR2pDND0iLCJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI3MGY3OGZlZC0xMzdmLTQ2OWEtODNmMS00YzcyMmMxMWE5ZjYiLCJ0b2tlbl91c2UiOiJhY2Nlc3MiLCJzY29wZSI6ImF3cy5jb2duaXRvLnNpZ25pbi51c2VyLmFkbWluIHBob25lIG9wZW5pZCBwcm9maWxlIGVtYWlsIiwiYXV0aF90aW1lIjoxNjEyMjkyMDg3LCJpc3MiOiJodHRwczpcL1wvY29nbml0by1pZHAudXMtZWFzdC0xLmFtYXpvbmF3cy5jb21cL3VzLWVhc3QtMV9mNEdPRXBDRW4iLCJleHAiOjE2MTIyOTU2ODcsImlhdCI6MTYxMjI5MjA4NywidmVyc2lvbiI6MiwianRpIjoiNzE2N2MzYjMtODZlZC00ZjJhLWI0ZWQtMjk3YmM1ZmVkNDRiIiwiY2xpZW50X2lkIjoiM2pmNDZrYzF1cmZndWpwbWI1YXIyM24wbDMiLCJ1c2VybmFtZSI6InRlc3R1c2VyIn0.GjGBR1bYFy0xfBe1xwcrCf_GOw2tas_N3uQuX1k9zKjYiOluLs3bOu8bZHPVzcwSki6f4iZtuoM4QD1ZvBKqsxzZg4_ppo-qaGdVh-YENAXRKOyRZLHM0mrwlrNgR5ydwWGvHgnroE84mVOQSkt9TBERd_ZESY2_-mtrgKYAMR16Jk9WWgRpiPc_vFWbyh9M6BOcxt_yitfYDlKv1EBV9LZehOVDG_8XKOwtHDv7MRXEwXGNAUWErzmmlMBnhxKYnyY7WQkLE1AddZO23HVxnPDLx16ZOUeiQWJ-m9_fIKnPWsVd7aIjTjlBZ-0dm1ZMRERgDflpuqbFTiPuwzrunQ&expires_in=3600&token_type=Bearer
	}
	
	
} catch (\Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException $e) {
    echo 'FAILED TO VALIDATE THE ACCESS TOKEN. ERROR = ' . $e->getMessage();
	}
catch (\Aws\Exception\CredentialsException $e) {
    echo 'FAILED TO AUTHENTICATE AWS KEY AND SECRET. ERROR = ' . $e->getMessage();
	}

}
?>


    </body>
</html>
