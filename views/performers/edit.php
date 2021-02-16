<?php
require_once '../../utils/functions.php';
require_once '../../classes/Performer.php';
require_once '../../classes/Gump.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
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
            throw new Exception("Invalid performer id: " . $errors['id']);
        }

        $id = $validated_data['id'];
        $performer = Performer::find($id);
        
        $img_src = $performer->image_path;
        
        if(!strpos($img_src, 'placeimg')) {
          $img_src = "../../" . $performer->image_path;
        }
        
        // dd(date_format(date_create($performer->start_date),"d/m/Y, H:i"));
        
        

    }
}
catch (Exception $ex) {
    die($ex->getMessage());
}
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
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php require '../../utils/header.php'; ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <form method="POST"
                          action="update.php"
                          role="form"
                          class="form-horizontal"
                          enctype="multipart/form-data"
                          >
                          <input type="hidden" name="id" value="<?= $performer->id . '&' . $query ?>" />
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <h2>Edit performer form</h2>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="image_path" class="col-md-3 control-label">Image</label>
                            <div class="col-md-6">
                                <img src="<?= $img_src ?>" height="100px" />
                                <input type="file" class="form-control" id="image_path" name="image_path" />
                            </div>

                            <div class="col-md-3 error">
                                <?php error('image_path'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-md-3 control-label">Title</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="title" name="title" value="<?= old('title', $performer->title) ?>" />
                            </div>
                            <div class="col-md-3 error">
                                <?php error('title'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-md-3 control-label">Description</label>
                            <div class="col-md-6">
                              <textarea id="description" name="description" rows="4" cols="50"><?= old('description', $performer->description) ?></textarea>
                            </div>
                            <div class="col-md-3 error">
                                <?php error('description'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="contact_email" class="col-md-3 control-label">Contact Email</label>
                            <div class="col-md-6">
                                <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?= old('contact_email', $performer->contact_email) ?>" />
                            </div>
                            <div class="col-md-3 error">
                                <?php error('contact_email'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="contact_phone" class="col-md-3 control-label">Contact Phone</label>
                            <div class="col-md-6">
                                <input type="tel" class="form-control" id="contact_phone" name="contact_phone" value="<?= old('contact_phone', $performer->contact_phone) ?>" />
                            </div>
                            <div class="col-md-3 error">
                                <?php error('contact_phone'); ?>
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
    </body>
</html>
