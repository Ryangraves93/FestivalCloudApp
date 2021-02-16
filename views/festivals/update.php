<?php
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
        'city' => 'required|min_len,1|max_len,30',
        'start_date' => 'required',
        'end_date' => 'required'
    );
    $filter_rules = array(
    	'id' => 'trim|sanitize_numbers',
      'title' => 'trim|sanitize_string',
      'description' => 'trim|sanitize_string',
      'city' => 'trim|sanitize_string'
    );

    // Extract token from post ID
    $parts = preg_split('@(?=&)@', $_POST['id']);

    // Split up values
    $id = intval($parts[0]);

    // Removes "&" from beginning of string
    $token = substr($parts[1], 1);

    // Set post id to be token before validation
    $_POST['id'] = $id;

    $validator->validation_rules($validation_rules);
    $validator->filter_rules($filter_rules);
    
    $validated_data = $validator->run($_POST);


    $fileName = time();
    $festival = Festival::find($id);
    

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
        // dd($errors);
        throw new Exception("There were errors. Please fix them.");
    }

    $festival->title = $validated_data['title'];
    $festival->description = $validated_data['description'];
    $festival->city = $validated_data['city'];
    $festival->start_date = $validated_data['start_date'];
    $festival->end_date = $validated_data['end_date'];
    if ($imageFile != null) {
        if ($festival->image_path != null && $festival->image_path != 'uploads/default.png' && file_exists($festival->image_path)) {
            unlink($festival->image_path);
        }
        $festival->image_path = $imageFile;
    }
    $festival->save();

    header("Location: index.php?" . htmlspecialchars($token));

}
catch (Exception $ex) {
    require 'edit.php';
}
?>
