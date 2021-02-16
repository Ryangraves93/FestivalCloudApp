<?php
require_once '../../classes/Show.php';
require_once '../../classes/Stage.php';
require_once '../../classes/Performer.php';
require_once '../../classes/Gump.php';
require_once '../../utils/functions.php';

try {
    $validator = new GUMP();

    $_POST = $validator->sanitize($_POST);

    $validation_rules = array(
        'id' => 'required|integer|min_numeric,1',
        'performer_id' => 'required|integer|min_numeric,1',
        'stage_id' => 'required|integer|min_numeric,1',
        'start_time' => 'required',
        'end_time' => 'required'
    );
    $filter_rules = array(
    	'id' => 'trim|sanitize_numbers',
      'stage_id' => 'trim|sanitize_numbers',
      'performer_id' => 'trim|sanitize_numbers'
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
    $show = Show::find($id);
    
    if($validated_data === false) {
        $errors = $validator->get_errors_array();
    }
    else {
        $errors = array();
        
        $performer_id = $validated_data['performer_id'];
        $performer = Performer::find($performer_id);
        if ($performer === false) {
            $errors['performer_id'] = "Invalid performer";
        }
        
        $stage_id = $validated_data['stage_id'];
        $stage = Stage::find($stage_id);
        if ($stage === false) {
            $errors['stage_id'] = "Invalid stage";
        }
    }
    
// dd($errors);
    if (!empty($errors)) {
        throw new Exception("There were errors. Please fix them.");
    }

    $show->start_time = $validated_data['start_time'];
    $show->end_time = $validated_data['end_time'];
    $show->performer_id = $validated_data['performer_id'];
    $show->stage_id = $validated_data['stage_id'];
    $show->save();

    header("Location: index.php?access_token=" . htmlspecialchars($_GET['access_token']));
}
catch (Exception $ex) {
  // dd();
    require 'edit.php';
    
}
?>
