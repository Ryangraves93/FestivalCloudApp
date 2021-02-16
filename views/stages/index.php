<?php
require_once '../../classes/Stage.php';
require_once '../../classes/Festival.php';

try {
    $stages = Stage::all();
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
                <h2>Stages <a href="create.php?<?= $query ?>"class="btn btn-primary pull-right">Add</a></h2>
                    <?php if (count($stages) == 0) { ?>
                        <p>There are no stages</p>
                    <?php } else { ?>
                        <table id="table-stages" class="table table-hover">
                            <thead>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Location</th>
                                <th>Festival</th>
                            </thead>
                            <tbody>
                                <?php foreach ($stages as $stage) { 
                                  $festival = Festival::find($stage->festival_id);
                                  ?>
                                    <tr data-id="<?= $stage->id . '&' . $query ?>">
                                        <td><a href="show.php?id=<?= $stage->id . '&' . $query ?>" class="btn-link"><?= $stage->title ?></a></td>
                                        <td><?= $stage->description ?></td>
                                        <td><?= $stage->location ?></td>
                                        <td><a href="../festivals/show.php?id=<?= $festival->id . '&' . $query ?>" class="btn-link"><?= $festival->title ?></a></td>
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
