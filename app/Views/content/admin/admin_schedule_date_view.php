<?= $this->extend(($layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('custom_css') ?>

<style>
    .avatar {
        max-width: 60px;
        max-height: 60px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('custom_scripts') ?>
<script src="/js/crud.js"></script>
<script>
    function translateStatus(status) {
        switch (status) {
            case "requested":
                return "Solicitado";
                break;
            case "draft":
                return "Rascunho";
                break;
            case "scheduled":
                return "Agendado";
                break;
            case "rescheduled":
                return "Regendado";
                break;
            case "closed":
                return "Concluído";
                break;
            case "canceled":
                return "Cancelado";
                break;
            default:
                return "Indefinido";
                break;
        }
    }

    function chooseBadge(status) {
        switch (status) {
            case "requested":
                return "danger";
                break;
            case "draft":
                return "warning";
                break;
            case "scheduled":
            case "rescheduled":
                return "primary";
                break;
            case "closed":
                return "success";
                break;
            case "canceled":
                return "secondary";
                break;
            default:
                return "secondary";
                break;
        }
    }


    $(function() {

        var send_data = addCSRF([])

        var dt =
            $("#dataTable").DataTable({
                //"responsive": true,
                //"lengthChange": true,
                //"serverSide": true,
                "language": defaultParams.language,
                "lengthMenu": defaultParams.lengthMenu,
                "autoWidth": false,
                "buttons": defaultParams.buttons,
                "ajax": defaultParams.ajaxlist(window.atob('<?= base64_encode(isset($list_url) ? (($list_url) ? base_url($list_url) : "#NotImplemented") : "") ?>'), send_data),

                columnDefs: [
                    // {
                    //     targets: 0,
                    //     type: 'time-uni',
                    // },
                    {
                        targets: 0,
                        // The `data` parameter refers to the data for the cell defined by the
                        // `data` option, which defaults to the column being worked with, in
                        // this case `data: 0`.
                        render: function(data, type, row) {
                            var day = moment(data).format("HH:mm")
                            return '<span class="lead"> ' + day + ' </span>'
                        }
                    },
                    {
                        targets: 1,
                        // The `data` parameter refers to the data for the cell defined by the
                        // `data` option, which defaults to the column being worked with, in
                        // this case `data: 0`.
                        render: function(data, type, row) {
                            //console.log(window.dataTableObject.data().indexOf(row));
                            //console.log(window.dataTableObject.data().indexOf(row));
                            //, \'<button data-uid="' . $schedule->schedule_uid . '" class="start-button btn btn-primary"><i class="fas fa-play-circle fa-2x"></i></button><button class="reschedule-button btn btn-secondary ml-2"><i class="fas fa-clock fa-2x"></i></button><button class="cancel-button btn btn-danger ml-2"><i class="fas fa-times-circle fa-2x"></i></button>\'
                            //console.log(data)
                            return '<div class="text-center">' +
                                '<span class="badge badge-pill badge-' + chooseBadge(data) + '">' + translateStatus(data) + '</span>'

                            <?php if (($allow_start_button ?? true)) : ?>
                                    +
                                    ((["requested", "draft"].includes(data)) ?
                                        '<a href="#" class="row-confirm-button btn btn-outline-success m-2 d-block p-0" data-toggle="modal" data-target="#confirm-modal" data-row-num="' + $("#dataTable").DataTable().data().indexOf(row) + '" data-id="' + row.schedule_uid + '"><span class="fas fa-check-circle"></span><span class="ml-3">Confirmar</span></a>' :
                                        "")
                            <?php endif; ?>

                            <?php if ($allow_reschedule_button ?? true) : ?>
                                    +
                                    ((["requested", "draft", "scheduled", "rescheduled"].includes(data)) ?
                                        '<a href="#" class="row-reschedule-button btn btn-outline-primary m-2 d-block p-0" data-toggle="modal" data-target="#reschedule-modal" data-row-num="' + $("#dataTable").DataTable().data().indexOf(row) + '" data-id="' + row.schedule_uid + '"><span class="fas fa-clock"></span><span class="ml-3">Alterar</span></a>' :
                                        "")
                            <?php endif; ?>

                            <?php if ($allow_cancel_button ?? true) : ?>
                                    +
                                    ((["requested", "draft", "scheduled", "rescheduled"].includes(data)) ?
                                        '<a href="#" class="row-cancel-button btn btn-outline-danger m-2 d-block p-0" data-toggle="modal" data-target="#cancel-modal" data-row-num="' + $("#dataTable").DataTable().data().indexOf(row) + '" data-id="' + row.schedule_uid + '"><span class="fas fa-times-circle"></span><span class="ml-3">Cancelar</span></a>' :
                                        "")
                            <?php endif; ?>

                                +
                                "</div>"
                        },

                    },
                    {
                        targets: 2,
                        render: function(data, type, row) {
                            if (data == null) {
                                return '<div class="w-100 h-100 text-center"><img class="avatar" src="/images/avatar/default.png"></div>'
                            } else {
                                return '<div class="w-100 h-100 text-center"><img class="avatar " src="' + row.worker_avatar + '"><div class="lead"> ' + data + ' </divs></div>'
                            }
                        }
                    },
                    {
                        targets: 3,
                        render: function(data, type, row) {

                            return '<div class="row"><div class="col-6"><label>Serviço:</label> ' + row.schedule_service_name + '</div>' +
                                '<div class="col-6"><label>Objeto:</label> ' + row.schedule_object_name + '</div>' +
                                '<div class="col-12"><label>Cliente:</label> ' + row.customer_name + '</div>' +
                                '<div class="col-12"><label>Email:</label>  ' + row.customer_email + '</div>' +
                                '<div class="col-6"><label>Contato:</label> ' + row.schedule_contact_name + '</div>' +
                                '<div class="col-6"><label>Telefone:</label> ' + row.schedule_contact_phone + '</div>' +
                                '<div class="col-12"><label>Endereço:</label> ' + row.schedule_address + '</div>' +
                                '</div>'


                        }
                    },
                ],
                "columns": [{
                        data: "schedule_date",
                        title: "Hora",
                        //visible: false,
                        width: '50px',
                    },
                    {
                        data: "schedule_status",
                        title: "Status",
                        width: '150px',
                        //visible: false,
                    },
                    {
                        data: "worker_name",
                        title: "Técnico",
                        width: '150px',
                    },
                    {
                        defaultContent: "",
                        title: "Dados"
                    },

                ],

                initComplete: function() {
                    // Apply the search

                }
            }).buttons().container().appendTo('#dataTable_wrapper .col-md-6:eq(0)');

        $("#dataTable").on("click", ".row-confirm-button", function(e) {
            e.preventDefault();
            var row = $("#dataTable").DataTable().row(this.dataset.rowNum).data();

            var str_date = moment(row.schedule_date).format("DD/MM/YYYY")
            var str_time = moment(row.schedule_date).format("HH:mm")
            $("#schedule-date-text").html(str_date);
            $("#schedule-time-field").val(str_time);
            $("#confirm-uid-field").val(row.schedule_uid);
            $("#schedule-worker-field").val(row.worker_uid);

            var html = $($("#dataTable").DataTable().cell(this.dataset.rowNum, 3).node()).html()
            $("#schedule-info").html(html)
        })

        $("#dataTable").on("click", ".row-reschedule-button", function(e) {
            e.preventDefault();
            var row = $("#dataTable").DataTable().row(this.dataset.rowNum).data();

            var str_date = moment(row.schedule_date).format("DD/MM/YYYY")
            var str_time = moment(row.schedule_date).format("HH:mm")
            $("#reschedule-date-field").val(str_date);
            $("#reschedule-time-field").val(str_time);
            $("#reschedule-uid-field").val(row.schedule_uid);
            $("#reschedule-worker-field").val(row.worker_uid);

            var html = $($("#dataTable").DataTable().cell(this.dataset.rowNum, 3).node()).html()
            $("#reschedule-info").html(html)
        })

        $("#dataTable").on("click", ".row-cancel-button", function(e) {
            e.preventDefault();
            var row = $("#dataTable").DataTable().row(this.dataset.rowNum).data();

            var str_date = moment(row.schedule_date).format("DD/MM/YYYY")
            var str_time = moment(row.schedule_date).format("HH:mm")
            $("#cancel-date-text").html(str_date);
            $("#cancel-time-text").html(str_time);
            $("#cancel-uid-field").val(row.schedule_uid);
            $("#cancel-worker-text").html(row.worker_name);
            
            var html = $($("#dataTable").DataTable().cell(this.dataset.rowNum, 3).node()).html()
            $("#cancel-info").html(html)

        })
        $(".schedule-calendar-button").on("click", function(e) {
            $('#datepicker').show().focus().hide();
        })

        $('#datepicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: false,
            minYear: 1901,
            autoApply: true,
            //timePicker: true,
            //timePicker24Hour: true, 
            maxYear: parseInt(moment().format('YYYY'), 10)
        }, function(start, end, label) {
            window.location.href = "/admin/schedule/date/" + moment(start).format("YYYY-MM-DD");
        });

        $(".reschedule-calendar-button").on("click", function(e) {
            $('#reschedule-date-field').show().focus(); //.hide();
        })

        $('#reschedule-date-field').daterangepicker({
            singleDatePicker: true,
            showDropdowns: false,
            minYear: 1901,
            autoApply: true,
            //timePicker: true,
            //timePicker24Hour: true, 
            maxYear: parseInt(moment().format('YYYY'), 10)
        });

        $("form").on("submit", function(e) {
           // e.preventDefault();
           // console.log(e);
           // return false
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
<?php

?>
<?= $this->section('content') ?>
<?= $this->openCard('<h3 class="d-inline-block mr-3">Agenda do dia: </h3>&nbsp;<input id="datepicker" value="' . $current_date->format("d/m/Y") . '" readonly class="border-0 h3 w-25 d-inline text-right"><i class="schedule-calendar-button cursor-pointer ml-3 far fa-calendar-alt fa-2x"></i>') ?>


<table id="dataTable" class="table table-sm"></table>
<form method="POST" action="/admin/schedule/confirm">
<?= csrf_field() ?>
<input id="confirm-uid-field" name="schedule_uid" type="hidden">
<input name="schedule_current_date" type="hidden" value="<?= $current_date->format("Y-m-d") ?>">
<input name="schedule_date" type="hidden" value="<?= $current_date->format("d/m/Y") ?>">
  <?= $this->openModal("confirm", "Confirmar Agendamento", ["modal-header-css" => "bg-success text-white"]) ?>
    <div class="container">
        <p>Selecione o horário e o técnico para o agendamento e clique em confirmar:</p>
        <div class="form-row">
            <div class="form-group col-2">
                <label for="schedule-date-text">Dia</label>
                <span id="schedule-date-text" readonly class="form-control-plaintext border form-control-lg text-center bg-light"><?= $current_date->format("d/m/Y") ?></span>
            </div>
            <div class="form-group col-2">
                <label for="schedule-time-field">Horário</label>
                <select class="form-control form-control-lg" id="schedule-time-field" name="schedule_time">
                    <?php
                    for ($i = 8; $i <= 18; $i++) :
                    ?>
                        <option value="<?= substr("0$i", -2) ?>:00"><?= substr("0$i", -2) ?>:00</option>
                        <option value="<?= substr("0$i", -2) ?>:30"><?= substr("0$i", -2) ?>:30</option>
                    <?php
                    endfor;
                    ?>
                </select>
            </div>
            <div class="form-group col-8">
                <label for="schedule-worker-field">Técnico</label>
                <select class="form-control form-control-lg" id="schedule-worker-field" name="worker_uid">
                    <?php
                    foreach ($workers as $w) :
                    ?>
                        <option value="<?= $w->worker_uid ?>"><?= $w->worker_name ?></option>
                    <?php
                    endforeach;
                    ?>
                </select>
            </div>
        </div>
        <div id="schedule-info"></div>
    </div>
    <?= $this->closeModal(
        "confirm",
        [
            "close" =>  ["label" => "Fechar", "tag" => "button", "type" => "button", "class" => "btn-secondary", "data-dismiss" => "modal"],
            "submit" => ["label" => "Confirmar", "class" => "btn-success", "type" => "submit", "data-dismiss" => ""]
        ]
    );
    ?>
</form>

<form method="POST" action="/admin/schedule/reschedule">
<?= csrf_field() ?>
<input id="reschedule-uid-field" name="schedule_uid" type="hidden">
<input name="schedule_current_date" type="hidden" value="<?= $current_date->format("Y-m-d") ?>">
    <?= $this->openModal("reschedule", "Alterar Agendamento", ["modal-header-css" => "bg-primary text-white"]) ?>
    <div class="container">
        <p>Selecione o dia, horário e o técnico para o agendamento e clique em salvar ou confirmar:</p>
        <div class="form-row">
            <div class="form-group col-2">s
                <label for="reschedule-date-field">Dia</label>
                <input id="reschedule-date-field" name="schedule_date" value="<?= $current_date->format("d/m/Y") ?>" readonly class="form-control-plaintext border form-control-lg text-center">
            </div>
            <div class="form-group col-1">
                <label class="d-block" for="schedule-time-field">&nbsp;</label>
                <i class="reschedule-calendar-button cursor-pointer ml-3 far fa-calendar-alt fa-2x"></i>
            </div>
            <div class="form-group col-2">
                <label for="reschedule-time-field">Horário</label>
                <select class="form-control form-control-lg" id="reschedule-time-field" name="schedule_time">
                    <?php
                    for ($i = 8; $i <= 18; $i++) :
                    ?>
                        <option value="<?= substr("0$i", -2) ?>:00"><?= substr("0$i", -2) ?>:00</option>
                        <option value="<?= substr("0$i", -2) ?>:30"><?= substr("0$i", -2) ?>:30</option>
                    <?php
                    endfor;
                    ?>
                </select>
            </div>
            <div class="form-group col-7">
                <label for="reschedule-worker-field">Técnico</label>
                <select class="form-control form-control-lg" id="reschedule-worker-field" name="worker_uid">
                    <?php
                    foreach ($workers as $w) :
                    ?>
                        <option value="<?= $w->worker_uid ?>"><?= $w->worker_name ?></option>
                    <?php
                    endforeach;
                    ?>
                </select>
            </div>
        </div>
        <div id="reschedule-info"></div>
    </div>
    <?= $this->closeModal(
        "reschedule",
        [
            "close" =>  ["label" => "Fechar", "tag" => "button", "type" => "button", "class" => "btn-secondary", "data-dismiss" => "modal"],
            "submit-save" => ["label" => "Salvar Alterações", "class" => "btn-primary", "type" => "submit", "data-dismiss" => ""],
           // "submit-confirm" => ["label" => "Salvar e Confirmar", "class" => "btn-success", "type" => "submit", "data-dismiss" => ""]
        ]
    );

    ?>
</form>

<form method="POST" action="/admin/schedule/cancel">
<?= csrf_field() ?>
<input id="cancel-uid-field" name="schedule_uid" type="hidden">
<input name="schedule_current_date" type="hidden" value="<?= $current_date->format("Y-m-d") ?>">
    <?= $this->openModal("cancel", "Cancelar Agendamento", ["modal-header-css" => "bg-danger text-white"]) ?>
    <div class="container">
        <p>Tem certeza que deseja realizar o cancelamento do agendamento abaixo?</p>
        <div class="form-row">
            
            <div class="form-group col-2">
                <label for="cancel-date-text">Dia</label>
                <span id="cancel-date-text" readonly class="form-control-plaintext border form-control-lg text-center bg-light"></span>
            </div>

            <div class="form-group col-2">
                <label for="cancel-time-text">Horário</label>
                <span id="cancel-time-text" readonly class="form-control-plaintext border form-control-lg text-center bg-light"></span>
            </div>

            <div class="form-group col-8">
                <label for="cancel-worker-text">Técnico</label>
                <span id="cancel-worker-text" readonly class="form-control-plaintext border form-control-lg pl-3 text-left bg-light"></span>
            </div>
            
        </div>
        <div id="cancel-info"></div>
    </div>
    <?= $this->closeModal(
        "cancel",
        [
            "close" =>  ["label" => "Fechar", "tag" => "button", "type" => "button", "class" => "btn-secondary", "data-dismiss" => "modal"],
            "submit" => ["label" => "Cancelar Agenda", "class" => "btn-danger", "type" => "submit", "data-dismiss" => ""]
        ]
    );

    ?>
</form>



<?= $this->closeCard() ?>
<?= $this->endSection() ?>