<?php
require_once '../../classes/Performer.php';

try {
    $performers = Performer::all();
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
                <h2>Performers <a href="create.php?<?= $query ?>"class="btn btn-primary pull-right">Add</a></h2>
                    <?php if (count($performers) == 0) { ?>
                        <p>There are no performers</p>
                    <?php } else { ?>
                        <table id="table-performers" class="table table-hover">
                            <thead>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Email</th>
                                <th>Phone</th>
                            </thead>
                            <tbody>
                                <?php foreach ($performers as $performer) { ?>
                                    <tr data-id="<?= $performer->id . '&' . $query ?>">
                                        <td><a href="show.php?id=<?= $performer->id . '&' . $query ?>" class="btn-link"><?= $performer->title ?></a></td>
                                        <td><?= $performer->description ?></td>
                                        <td><?= $performer->contact_email ?></td>
                                        <td><?= $performer->contact_phone ?></td>
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
