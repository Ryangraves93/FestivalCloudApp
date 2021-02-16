<?php
require_once '../../classes/Festival.php';
require_once '../../classes/Gump.php';

try {
    $validator = new GUMP();

    $_GET = $validator->sanitize($_GET);

    $validation_rules = array(
        'id' => 'required|integer|min_numeric,1'
    );
    $filter_rules = array(
    	'id' => 'trim|sanitize_numbers'
    );

    $validator->validation_rules($validation_rules);
    $validator->filter_rules($filter_rules);

    $validated_data = $validator->run($_GET);

    if($validated_data === false) {
        $errors = $validator->get_errors_array();
        throw new Exception("Invalid festival id: " . $errors['id']);
    }

    $id = $validated_data['id'];
    $festival = Festival::find($id);
    
    $img_src = $festival->image_path;
    
    if (!strpos($img_src, 'placeimg') && $festival->image_path != null && $festival->image_path != 'uploads/default.png' && file_exists($festival->image_path)) {
        unlink($festival->image_path);
    }
    
    $festival->delete();

    header("Location: index.php?access_token=" . htmlspecialchars($_GET["access_token"]));
}
catch (Exception $ex) {
    die($ex->getMessage());
}
?>
