<?php
require_once '../../classes/Performer.php';
require_once '../../classes/Gump.php';
require_once '../../utils/functions.php';

try {
    $validator = new GUMP();

    $_POST = $validator->sanitize($_POST);

    $validation_rules = array(
        'id' => 'required|integer|min_numeric,1',
        'title' => 'required|min_len,1|max_len,100',
        'description' => 'required|min_len,1|max_len,250',
        'contact_email' => 'required|valid_email',
        'contact_phone' => 'required|phone_number'
    );
    $filter_rules = array(
    	'id' => 'trim|sanitize_numbers',
      'title' => 'trim|sanitize_string',
      'description' => 'trim|sanitize_string',
      'contact_email' => 'trim|sanitize_string',
      'contact_phone' => 'trim|sanitize_string'
    );

      $parts = preg_split('@(?=&)@', $_POST['id']);
      $id = intval($parts[0]);
      $token = substr($parts[1], 1);
      $_POST['id'] = $id;

    $validator->validation_rules($validation_rules);
    $validator->filter_rules($filter_rules);
    
    $validated_data = $validator->run($_POST);
    $id = $_POST['id'];
    $fileName = time();
    $performer = Performer::find($id);
    
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
    
// dd($errors);
    if (!empty($errors)) {
        throw new Exception("There were errors. Please fix them.");
    }

    $performer->title = $validated_data['title'];
    $performer->description = $validated_data['description'];
    $performer->contact_email = $validated_data['contact_email'];
    $performer->contact_phone = $validated_data['contact_phone'];
    if ($imageFile != null) {
        if ($performer->image_path != null && $performer->image_path != 'uploads/default.png' && file_exists($performer->image_path)) {
            unlink($performer->image_path);
        }
        $performer->image_path = $imageFile;
    }
    $performer->save();

    header("Location: index.php?" . htmlspecialchars($token));
}
catch (Exception $ex) {
  // dd();
    require 'edit.php';
    
}
?>
