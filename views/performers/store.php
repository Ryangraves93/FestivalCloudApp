<?php
require_once '../../classes/Performer.php';
require_once '../../classes/Gump.php';
require_once '../../utils/functions.php';

try {
    $validator = new GUMP();

    $_POST = $validator->sanitize($_POST);

    $validation_rules = array(
        'title' => 'required|min_len,1|max_len,100',
        'description' => 'required|min_len,1|max_len,250',
        'contact_email' => 'required|valid_email',
        'contact_phone' => 'required|phone_number'
    );
    $filter_rules = array(
        'title' => 'trim|sanitize_string',
        'description' => 'trim|sanitize_string',
        'contact_email' => 'trim|sanitize_string',
        'contact_phone' => 'trim|sanitize_string'
    );

    $validator->validation_rules($validation_rules);
    $validator->filter_rules($filter_rules);

    $validated_data = $validator->run($_POST);
    
    $fileName = time();

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
          }
        }
        else {
          $imageFile = 'uploads/default.png';
        }
    }

    if (!empty($errors)) {
        throw new Exception("There were errors. Please fix them.");
    }

    $performer = new Performer();
    $performer->title = $validated_data['title'];
    $performer->description = $validated_data['description'];
    $performer->contact_email = $validated_data['contact_email'];
    $performer->contact_phone = $validated_data['contact_phone'];
    $performer->image_path = $imageFile;

    $performer->save();

    header("Location: index.php?access_token=" . htmlspecialchars($_GET["access_token"]));
}
catch (Exception $ex) {
    require 'create.php';
}
?>
