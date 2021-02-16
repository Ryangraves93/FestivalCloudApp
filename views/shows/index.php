<?php
require_once '../../classes/Show.php';
require_once '../../classes/Stage.php';
require_once '../../classes/Performer.php';

try {
    $shows = Show::all();
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
                <h2>Shows <a href="create.php?<?= $query ?>"class="btn btn-primary pull-right">Add</a></h2>
                    <?php if (count($shows) == 0) { ?>
                        <p>There are no shows</p>
                    <?php } else { ?>
                        <table id="table-shows" class="table table-hover">
                            <thead>
                                <th>Performer</th>
                                <th>Stage</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                <?php foreach ($shows as $show) { 
                                  $stage = Stage::find($show->stage_id);
                                  $performer = Performer::find($show->performer_id);
                                ?>
                                    <tr data-id="<?= $show->id ?>">
                                    <td><a href="../performers/show.php?id=<?= $performer->id . '&' . $query ?>" class="btn-link"><?= $performer->title ?></a></td>
                                        <td><a href="../stages/show.php?id=<?= $stage->id . '&' . $query ?>" class="btn-link"><?= $stage->title ?></a></td>
                                        <td><?= $show->start_time ?></td>
                                        <td><?= $show->end_time ?></td>
                                        <td>
                                        <a href="edit.php?id=<?= $show->id . '&' . $query ?>" class="btn btn-warning pull-right">Edit</a>
                                          <a href="delete.php?id=<?= $show->id . '&' . $query ?>" class="btn btn-danger pull-right">Delete</a>
                                    </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
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
