<?php
require_once '../../classes/Stage.php';
require_once '../../classes/Festival.php';
require_once '../../classes/Gump.php';
require_once '../../utils/functions.php';

try {
    $validator = new GUMP();

    $_POST = $validator->sanitize($_POST);

    $validation_rules = array(
        'id' => 'required|integer|min_numeric,1',
        'title' => 'required|min_len,1|max_len,100',
        'description' => 'required|min_len,1|max_len,250',
        'location' => 'required|min_len,1|max_len,30',
        'festival_id' => 'required|integer|min_numeric,1'
    );
    $filter_rules = array(
    	'id' => 'trim|sanitize_numbers',
      'title' => 'trim|sanitize_string',
      'description' => 'trim|sanitize_string',
      'location' => 'trim|sanitize_string',
      'festival_id' => 'trim|sanitize_numbers'
    );

       $parts = preg_split('@(?=&)@', $_POST['festival_id']);
       $id = intval($parts[0]);
       $token = substr($parts[1], 1);
       $_POST['festival_id'] = $id;

    $validator->validation_rules($validation_rules);
    $validator->filter_rules($filter_rules);
    
    $validated_data = $validator->run($_POST);
    $id = $_POST['id'];
    $fileName = time();
    $stage = Stage::find($id);
    
    if($validated_data === false) {
        $errors = $validator->get_errors_array();
    }
    else {
        $errors = array();
        
        $festival_id = $validated_data['festival_id'];
        
        $festival = Festival::find($festival_id);
        
        if ($festival === false) {
            $errors['festival_id'] = "Invalid festival";
        }
        
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

    $stage->title = $validated_data['title'];
    $stage->description = $validated_data['description'];
    $stage->location = $validated_data['location'];
    $stage->festival_id = $validated_data['festival_id'];
    if ($imageFile != null) {
        if ($stage->image_path != null && $stage->image_path != 'uploads/default.png' && file_exists($stage->image_path)) {
            unlink($stage->image_path);
        }
        $stage->image_path = $imageFile;
    }
    $stage->save();

    header("Location: index.php?" . htmlspecialchars($token));
}
catch (Exception $ex) {
  // dd();
    require 'edit.php';
    
}
?>
