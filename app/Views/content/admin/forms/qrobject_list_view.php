<?= $this->extend(( $layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('custom_css') ?>
<?= $this->endSection() ?>

<?= $this->section('custom_scripts') ?>
<script>
    $(document).ready(function() {
        $('#users-list').DataTable();
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="m-1 m-md-3 m-lg-5 p-1 p-md-3 p-lg-5 bg-white border border-info rounded d-flex flex-column">

    <div class="container mt-4">
        <div class="d-flex justify-content-end">
            <a href="<?= route_to('App\Controllers\QrObjectController::new') ?>" class="btn btn-success mb-2">Novo</a>
        </div>
        <div class="rounded border border-info ">
            <?php

            echo current_url();

            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
            }
            ?>
        </div>
        <div class="mt-3">
            <table class="table table-bordered" id="users-list">
                <thead>
                    <tr>
                        <th>Object Id</th>
                        <th>Object Uid</th>
                        <th>Owner Uid</th>
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($data) : ?>
                        <?php foreach ($data as $item) : ?>
                            <tr>
                                <td><?= $item->object_id ?></td>
                                <td><?= $item->object_uid ?></td>
                                <td><?= $item->object_owner_uid ?></td>
                                <td><?= $item->description ?></td>
                                <td>
                                    <a href="<?= route_to('App\Controllers\QrObjectController::edit', $item->object_uid) ?>" class="btn btn-primary btn-sm">Editar</a>
                                    <a href="<?= route_to('App\Controllers\QrObjectController::remove', $item->object_uid) ?>" class="btn btn-danger btn-sm">Remover</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</section>
<?= $this->endSection() ?>