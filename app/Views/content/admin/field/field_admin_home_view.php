<?= $this->extend(($layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('custom_css') ?>
<style>
    table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before,
    table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control:before {

        content: "\00BB";
        background-color: #0275d8;
    }

    table.dataTable.dtr-inline.collapsed>tbody>tr.parent>td.dtr-control:before,
    table.dataTable.dtr-inline.collapsed>tbody>tr.parent>th.dtr-control:before {
        content: "\00AB";
        background-color: #be31d3;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('custom_scripts') ?>

<script>
    $(function() {

        var dataSet1 = [
            <?php
            if (isset($schedules)) {
                foreach ($schedules as $schedule) {
                    $date = date_create($schedule->schedule_date);
                    //echo '["' . $schedule->schedule_uid . '","' . date_format($date, "d/m/Y") . '", "' . date_format($date, "H:i") . '", "' . $schedule->service_name . '", "Ar Condicionado", "' . $schedule->city . '", "' . $schedule->contact_name . '", "' . $schedule->contact_phone . '", "' . preg_replace("/\r|\n/", "", htmlentities($schedule->address)) . '", \'<button data-uid="' . $schedule->schedule_uid . '" class="start-button btn btn-primary"><i class="fas fa-play-circle fa-2x"></i></button><button class="reschedule-button btn btn-secondary ml-2"><i class="fas fa-clock fa-2x"></i></button><button class="cancel-button btn btn-danger ml-2"><i class="fas fa-times-circle fa-2x"></i></button>\'],' . "\n";
                    echo '["' . $schedule->schedule_uid . '","' . date_format($date, "d/m/Y") . '", "' . date_format($date, "H:i") . '", "' . $schedule->schedule_service_name . '", "Ar Condicionado", "' . $schedule->schedule_city . '", "' . $schedule->schedule_contact_name . '", "' . $schedule->schedule_contact_phone . '", "' . preg_replace("/\r|\n/", " ", htmlentities($schedule->schedule_address)) . '",""],' . "\n";
                    //echo '["' . date_format($date, "d/m/Y") . '", "' . date_format($date, "H:i") . '", "' . $schedule->service_name . '", "Ar Condicionado", "' . $schedule->city . '", "' . $schedule->contact_name . '", "' . $schedule->contact_phone . '", "' . preg_replace("/\r|\n/", "", htmlentities($schedule->address)) . '"],' . "\n";
                }
            }
            ?>
        ];

        var dt =
            $("#dataTable").DataTable({
                "responsive": true,
                "lengthChange": true,
                "lengthMenu": [
                    [5, 10, 25],
                    [5, 10, 25]
                ],
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "data": dataSet1,

                columnDefs: [{
                        visible: false,
                        targets: 0
                    },
                    {
                        type: 'date-euro',
                        targets: 1
                    },
                    {
                        // The `data` parameter refers to the data for the cell defined by the
                        // `data` option, which defaults to the column being worked with, in
                        // this case `data: 0`.
                        "render": function(data, type, row) {
                            //console.log(window.dataTableObject.data().indexOf(row));
                            //console.log(window.dataTableObject.data().indexOf(row));
                            //, \'<button data-uid="' . $schedule->schedule_uid . '" class="start-button btn btn-primary"><i class="fas fa-play-circle fa-2x"></i></button><button class="reschedule-button btn btn-secondary ml-2"><i class="fas fa-clock fa-2x"></i></button><button class="cancel-button btn btn-danger ml-2"><i class="fas fa-times-circle fa-2x"></i></button>\'
                            return ""
                            <?php if ($allow_start_button ?? true) : ?>
                                    +
                                    '<a href="/field/schedule/open/' + row[0] + '" class="row-start-button btn btn-outline-primary ml-2" d-ata-toggle="modal" d-ata-target="#start-modal" data-row-num="' + $("#dataTable").DataTable().data().indexOf(row) + '" data-id="' + row[0] + '"><span class="fas fa-play-circle"></span><span class="<?= ($row_label_buttons ?? false) ? 'ml-3">' . $start_button_label . '</span>' : '"></span>' ?></a>'
                            <?php endif; ?>

                            <?php if ($allow_reschedule_button ?? false) : ?>
                                    +
                                    '<a href="#" class="row-reschedule-button btn btn-outline-secondary ml-2" d-ata-toggle="modal" d-ata-target="#reschedule-modal" data-row-num="' + $("#dataTable").DataTable().data().indexOf(row) + '" data-id="' + row[0] + '"><span class="fas fa-clock"></span><span class="<?= ($row_label_buttons ?? false) ? 'ml-3">' . $reschedule_button_label . '</span>' : '"></span>' ?></a>'
                            <?php endif; ?>

                            <?php if ($allow_cancel_button ?? false) : ?>
                                    +
                                    '<a href="#" class="row-cancel-button btn btn-outline-danger ml-2" d-ata-toggle="modal" d-ata-target="#cancel-modal" data-row-num="' + $("#dataTable").DataTable().data().indexOf(row) + '" data-id="' + row[0] + '"><span class="fas fa-times-circle"></span><span class="<?= ($row_label_buttons ?? false) ? 'ml-3">' . $cancel_button_label . '</span>' : '"></span>' ?></a>'
                            <?php endif; ?>

                        },
                        "targets": 9,
                    }
                ],
                "columns": [{
                        title: "schedule_uid",
                    },
                    {
                        title: "Data",
                    },
                    {
                        title: "Hora"
                    },
                    {
                        title: "Tipo"
                    },
                    {
                        title: "Equipamento"
                    },
                    {
                        title: "Cidade"
                    },
                    {
                        title: "Contato"
                    },
                    {
                        title: "Telefone"
                    },
                    {
                        title: "Endereço"
                    },
                    {
                        title: "Ações",
                        width: '200px',
                        orderable: false,
                    },
                ],

                initComplete: function() {
                    // Apply the search






                }
            }).buttons().container().appendTo('#examples_wrapper .col-md-6:eq(0)');

        // $("#dataTable").on("click", ".row-start-button", function(e) {
        //     e.preventDefault();

        //     e.preventDefault();
        //     var data = $("#dataTable").DataTable().row(this.dataset.uid).data();
        //     console.log("start:", data);
        // })
        $("#dataTable").on("click", ".row-reschedule-button", function(e) {
            e.preventDefault();
            console.log("reschedule:", data);
        })
        $("#dataTable").on("click", ".row-cancel-button", function(e) {
            e.preventDefault();
            console.log("cancel:", data);
        })



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

<?= $this->openCard("Lista de agendamentos criados para você") ?>


<table id="dataTable" class="table table-bordered table-striped" width="100%">



</table>




<?= $this->closeCard() ?>
<?= $this->endSection() ?>