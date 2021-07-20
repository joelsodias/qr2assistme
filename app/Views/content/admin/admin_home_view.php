<?= $this->extend(($layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('custom_css') ?>

<style>
    #renderRange {
        padding-left: 12px;
        font-size: 19px;
        vertical-align: middle;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('custom_scripts') ?>
<script>
    $(function() {


    });
</script>
<?= $this->endSection() ?>

<?= $this->section('before-sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('after-sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->openCard("Agendamentos x Dia") ?>
Legenda:
<span class="badge badge-pill badge-danger">Pendente</span>
<span class="badge badge-pill badge-primary">Confirmado</span>
<span class="badge badge-pill badge-warning">Rascunho</span>
<span class="badge badge-pill badge-success">Concluído</span>
<span class="badge badge-pill badge-secondary">Cancelado</span>
<table class="table table-bordered">
    <thead>
        <tr>
            <th scope="col"><span class="calendar-day">Dom</span></th>
            <th scope="col"><span class="calendar-day">Seg</span></th>
            <th scope="col"><span class="calendar-day">Ter</span></th>
            <th scope="col"><span class="calendar-day">Qua</span></th>
            <th scope="col"><span class="calendar-day">Qui</span></th>
            <th scope="col"><span class="calendar-day">Sex</span></th>
            <th scope="col"><span class="calendar-day">Sáb</span></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php

            $c = 0;
            $ed =  clone $endDate;
            $date = new DateTime();
            for ($i = clone $startDate; $i <= $endDate; $i->modify("+1 day")) :
                $c++;
                $d = $i->format("Y-m-d");
            ?>
                <td class="position-relative p-0 cursor-pointer text-center">
                    <a class="d-block p-2 <?= ($date->format("Y-m-d") == $i->format("Y-m-d")) ? " border border-2 border-success " : "" ?>" href="/admin/schedule/date/<?= $d ?>">
                        <span class="calendar-day text-black-50 float-left"><?= date("d", $i->getTimestamp()) ?></span><span class="d-block text-center text-xl"><?= $schedules[$d]["total"] ?? 0 ?></span>
                        <?= (($schedules[$d]["requested"] ?? 0) > 0) ? '<span class="badge badge-danger">' . $schedules[$d]["requested"] . '</span>' : '' ?>
                        <?= (($schedules[$d]["scheduled"] ?? 0) > 0) ? '<span class="badge badge-primary">' . $schedules[$d]["scheduled"] . '</span>' : '' ?>
                        <?= (($schedules[$d]["draft"] ?? 0) > 0) ? '<span class="badge badge-warning">' . $schedules[$d]["draft"] . '</span>' : '' ?>
                        <?= (($schedules[$d]["closed"] ?? 0) > 0) ? '<span class="badge badge-success">' . $schedules[$d]["closed"] . '</span>' : '' ?>
                        <?= (($schedules[$d]["canceled"] ?? 0) > 0) ? '<span class="badge badge-secondary">' . $schedules[$d]["canceled"] . '</span>' : '' ?>
                    </a>
                </td>
            <?php
                if ($c > 6) {
                    $c = 0;
                    echo "</tr>";
                    if ($i < $endDate) {
                        echo "<tr>";
                    }
                }
            endfor;
            ?>
        </tr>
    </tbody>
</table>

<?= $this->closeCard() ?>
<?= $this->endSection() ?>