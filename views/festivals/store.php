<?php
require_once '../../classes/Festival.php';
require_once '../../classes/Gump.php';
require_once '../../utils/functions.php';
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

try {
    $validator = new GUMP();

    $_POST = $validator->sanitize($_POST);

    $validation_rules = array(
        'title' => 'required|min_len,1|max_len,100',
        'description' => 'required|min_len,1|max_len,250',
        'city' => 'required|min_len,1|max_len,30',
        'start_date' => 'required',
        'end_date' => 'required'
    );
    $filter_rules = array(
        'title' => 'trim|sanitize_string',
        'description' => 'trim|sanitize_string',
        'city' => 'trim|sanitize_string'
    );

    $validator->validation_rules($validation_rules);
    $validator->filter_rules($filter_rules);

    $validated_data = $validator->run($_POST);
    
    $fileName = time();

    $bucket = 'elasticbeanstalk-us-east-1-163071675941';
    $keyName = 'Test_Example/';

    if($validated_data === false) {
        $errors = $validator->get_errors_array();
    }
    else {
        $errors = array();
        if (isset($_FILES['image_path'])) {
          try {
              $imageFile = imageFileUpload('image_path', false, 1000000, array('jpg', 'jpeg', 'png', 'gif'), $fileName);
          
          }
          catch (Exception $e) {
              $errors['image_path'] = $e->getMessage();
              var_dump($e);
          }
        }
        else {
          $imageFile = 'uploads/default.png';
        }
    }

    if (!empty($errors)) {
        throw new Exception("There were errors. Please fix them.");
    }

    $festival = new Festival();
    $festival->title = $validated_data['title'];
    $festival->description = $validated_data['description'];
    $festival->city = $validated_data['city'];
    $festival->start_date = $validated_data['start_date'];
    $festival->end_date = $validated_data['end_date'];
    $festival->image_path = $imageFile;

    $festival->save();

    
    header("Location: index.php?access_token=" . htmlspecialchars($_GET["access_token"]));
}
catch (Exception $ex) {
    require 'create.php';
}
?>
