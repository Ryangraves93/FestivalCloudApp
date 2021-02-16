<?php
require_once '../../utils/functions.php';
require_once '../../classes/Performer.php';
require_once '../../classes/Stage.php';

try {
    $performers = Performer::all();
    $stages = Stage::all();
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
                                <h2>Create show form</h2>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="performer_id" class="col-md-3 control-label">Performer</label>
                            <div class="col-md-6">
                                <select class="form-control" id="performer_id" name="performer_id">
                                    <?php foreach ($performers as $performer) { ?>
                                        <option value="<?= $performer->id . '&' . $query ?>"><?= $performer->title ?></option>
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
                                        <option value="<?= $stage->id . '&' . $query ?>"><?= $stage->title ?></option>
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
                                <input type="datetime-local" class="form-control" id="start_time" name="start_time" value="<?= old('start_time') ?>" />
                            </div>
                            <div class="col-md-3 error">
                                <?php error('start_time'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="end_time" class="col-md-3 control-label">End Time</label>
                            <div class="col-md-6">
                                <input type="datetime-local" class="form-control" id="end_time" name="end_time" value="<?= old('end_time') ?>" />
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
