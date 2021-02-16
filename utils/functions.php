<?php

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;
require_once(__DIR__ . '/../classes/Auth.php');




function is_logged_in() {
    start_session();
    return (isset($_SESSION['user']));
}

function start_session() {
    $id = session_id();
    if ($id === "") {
        session_start();
    }
}

function old($index, $default = null) {
    if (isset($_POST) && is_array($_POST) && array_key_exists ($index, $_POST)) {
        echo $_POST[$index];
    }
    else if ($default !== null) {
        echo $default;
    }
}

function error($index) {
    global $errors;

    if (isset($errors) && is_array($errors) && array_key_exists ($index, $errors)) {
        echo $errors[$index];
    }
}

function dd($value) {
    echo '<pre>';
    print_r($value);
    echo '</pre>';
    exit();
}

// function createBucket()
// {
//     $s3client = new S3Client([
//         'profile' => 'default',
//         'version' => Auth::$version,
//         'region' => Auth::$region,

//         'credentials' => [
//             'key' => Auth::$key,
//             'secret' => Auth::$secret,
//             'token' => Auth::$access_token
//         ],
//     ]);

//     //Get the image
//     //Call the api to pass the image to the bucket
//     //$s3Client->getObject(,path)

//     echo createBucket($s3Client, 'my-bucket');
// }

function imageFileUpload($name, $required, $maxSize, $allowedTypes, $fileName) {
    if ($_SERVER['REQUEST_METHOD'] !== "POST") {
        throw new Exception('Invalid request');
    }

    if ($required && !isset($_FILES[$name])) {
        throw new Exception("File " . $name . " required");
    }
    else if (!$required && !isset($_FILES[$name])) {
        return null;
    }

    if ($_FILES[$name]['error'] !== 0) {
        // throw new Exception('File upload error');
        return "uploads/default.png";
    }

    if (!is_uploaded_file($_FILES[$name]["tmp_name"])) {
        throw new Exception("Filename is not an uploaded file");
    }

    $imageInfo = getimagesize($_FILES[$name]["tmp_name"]);
    if ($imageInfo === false) {
        throw new Exception("File is not an image.");
    }

    
    $auth = new Auth();

    $width = $imageInfo[0];
    $height = $imageInfo[1];
    $sizeString = $imageInfo[3];
    $mime = $imageInfo['mime'];

    $target_dir = "../";
    $target_file = $target_dir . basename($_FILES[$name]["name"]);
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    $target_file = $target_dir . "/" . $fileName . "." . $imageFileType;
    

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 755, true);
    }
    if (file_exists($target_file)) {
        throw new Exception("Sorry, file already exists.");
    }

    if ($_FILES[$name]["size"] > $maxSize) {
        throw new Exception("Sorry, your file is too large.");
    }

    if (!in_array($imageFileType, $allowedTypes)) {
        throw new Exception("Sorry, only " . implode(',', $allowedTypes) . " files are allowed.");
    }

         $keyName = 'Test_Example/'. basename($_FILES[$name]["name"]);
         $bucket = 'festivalcloudbucket';
         
         $file = $target_dir.$fileName;

         try {
            //Create a S3Client
            $s3Client = new S3Client([
                
                'region' => Auth::$region,
                'version' => 'latest',

             'credentials' => [
                    'key' => 'ASIASL56UJYSWQF7J3QK',
                    'secret' => Auth::$secret,
                    'token' => Auth::$session_token
             ],
            ]);
            //get command, get object
            $result = $s3Client->putObject([
                'Bucket' => $bucket,
                'Key' => $keyName,
                'SourceFile' => $_FILES[$name]["tmp_name"],
            ]);
        } catch (S3Exception $e) {
            throw new Exception ($e->getMessage() . "\n");
        }
        catch (\Exception $e) {
            throw new Exception($e->getMessage() . "\n");
        }

    return $keyName;
}
?>
