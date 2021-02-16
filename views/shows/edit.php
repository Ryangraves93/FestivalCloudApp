<?php
require_once '../../utils/functions.php';
require_once '../../classes/Show.php';
require_once '../../classes/Stage.php';
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
            throw new Exception("Invalid show id: " . $errors['id']);
        }

        $id = $validated_data['id'];
        $show = Show::find($id);
        
        $performers = Performer::all();
        $stages = Stage::all();
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
                          action="update.php?<?= $query ?>"
                          role="form"
                          class="form-horizontal"
                          enctype="multipart/form-data"
                          >
                          <input type="hidden" name="id" value="<?= $show->id . '&' . $query ?>" />
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <h2>Edit show form</h2>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="performer_id" class="col-md-3 control-label">Performer</label>
                            <div class="col-md-6">
                                <select class="form-control" id="performer_id" name="performer_id">
                                    <?php foreach ($performers as $performer) { ?>
                                        <option <?= ($performer->id === $show->performer_id) ? 'selected' : '' ?> value="<?= $performer->id . '&' . $query ?>"><?= $performer->title ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3 error">
                                <?php error('performer_id'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="stage_id" class="col-md-3 control-label">Stage</label>
                            <div class="col-md-6">
                                <select class="form-control" id="stage_id" name="stage_id">
                                    <?php foreach ($stages as $stage) { ?>
                                        <option <?= ($stage->id === $show->stage_id) ? 'selected' : '' ?> value="<?= $stage->id . '&' . $query ?>"><?= $stage->title ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3 error">
                                <?php error('stage_id'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="start_time" class="col-md-3 control-label">Start Time</label>
                            <div class="col-md-6">
                                <input type="datetime-local" class="form-control" id="start_time" name="start_time" value="<?= old('start_time', date_format(date_create($show->start_time),'Y-m-d\TH:i')) ?>" />
                            </div>
                            <div class="col-md-3 error">
                                <?php error('start_time'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="end_time" class="col-md-3 control-label">End Time</label>
                            <div class="col-md-6">
                                <input type="datetime-local" class="form-control" id="end_time" name="end_time" value="<?= old('end_time', date_format(date_create($show->end_time),'Y-m-d\TH:i')) ?>" />
                            </div>
                            <div class="col-md-3 error">
                                <?php error('end_time'); ?>
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
