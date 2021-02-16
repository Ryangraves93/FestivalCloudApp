<?php
require_once '../../utils/functions.php';
require_once '../../classes/Festival.php';

try {
    $festivals = Festival::all();
}
catch (Exception $ex) {
    die($e->getMessage());
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
                          action="store.php?<?= $query ?>"
                          role="form"
                          class="form-horizontal"
                          enctype="multipart/form-data"
                          >
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <h2>Create stage form</h2>
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
                            <label for="location" class="col-md-3 control-label">Location</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="location" name="location" value="<?= old('location') ?>" />
                            </div>
                            <div class="col-md-3 error">
                                <?php error('location'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="festival_id" class="col-md-3 control-label">Festival</label>
                            <div class="col-md-6">
                                <select class="form-control" id="festival_id" name="festival_id">
                                    <?php foreach ($festivals as $festival) { ?>
                                        <option value="<?= $festival->id . '&' . $query ?>"><?= $festival->title ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3 error">
                                <?php error('festival_id'); ?>
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
    </body>
</html>
