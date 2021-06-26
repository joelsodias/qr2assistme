<?= $this->extend(($layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('custom_css') ?>
<style>
table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control:before,
table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control:before {
    
    content: "\00BB";
    background-color: #0275d8;
}
table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td.dtr-control:before,
table.dataTable.dtr-inline.collapsed > tbody > tr.parent > th.dtr-control:before {
    content: "\00AB";
  background-color: #be31d3;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('custom_scripts') ?>

<script>
    $(function() {

        var dataSet1 = [
            ["18/06/2021", "08:00", "Instalação", "Ar Condicionado", "Cachoeirinha", "Fulano", "(51) 99999-9999", "Rua Osorio Correia, 250, apto 204 bloco B", '<button class="start-button btn btn-primary"><i class="fas fa-play-circle fa-2x"></i></button><button class="reschedule-button btn btn-secondary ml-2"><i class="fas fa-clock fa-2x"></i></button><button class="cancel-button btn btn-danger ml-2"><i class="fas fa-times-circle fa-2x"></i></button>'],
            ["18/06/2021", "09:00", "Instalação", "Ar Condicionado", "Cachoeirinha", "Fulano", "(51) 99999-9999", "Rua Osorio Correia, 250, apto 204 bloco B", '<button class="start-button btn btn-primary"><i class="fas fa-play-circle fa-2x"></i></button><button class="reschedule-button btn btn-secondary ml-2"><i class="fas fa-clock fa-2x"></i></button><button class="cancel-button btn btn-danger ml-2"><i class="fas fa-times-circle fa-2x"></i></button>'],
            ["18/06/2021", "10:00", "Instalação", "Ar Condicionado", "Cachoeirinha", "Fulano", "(51) 99999-9999", "Rua Osorio Correia, 250, apto 204 bloco B", '<button class="start-button btn btn-primary"><i class="fas fa-play-circle fa-2x"></i></button><button class="reschedule-button btn btn-secondary ml-2"><i class="fas fa-clock fa-2x"></i></button><button class="cancel-button btn btn-danger ml-2"><i class="fas fa-times-circle fa-2x"></i></button>'],
            ["18/06/2021", "08:00", "Instalação", "Ar Condicionado", "Cachoeirinha", "Fulano", "(51) 99999-9999", "Rua Osorio Correia, 250, apto 204 bloco B", '<button class="start-button btn btn-primary"><i class="fas fa-play-circle fa-2x"></i></button><button class="reschedule-button btn btn-secondary ml-2"><i class="fas fa-clock fa-2x"></i></button><button class="cancel-button btn btn-danger ml-2"><i class="fas fa-times-circle fa-2x"></i></button>'],
            ["18/06/2021", "08:00", "Instalação", "Ar Condicionado", "Cachoeirinha", "Fulano", "(51) 99999-9999", "Rua Osorio Correia, 250, apto 204 bloco B", '<button class="start-button btn btn-primary"><i class="fas fa-play-circle fa-2x"></i></button><button class="reschedule-button btn btn-secondary ml-2"><i class="fas fa-clock fa-2x"></i></button><button class="cancel-button btn btn-danger ml-2"><i class="fas fa-times-circle fa-2x"></i></button>'],
            ["18/06/2021", "08:00", "Instalação", "Ar Condicionado", "Cachoeirinha", "Fulano", "(51) 99999-9999", "Rua Osorio Correia, 250, apto 204 bloco B", '<button class="start-button btn btn-primary"><i class="fas fa-play-circle fa-2x"></i></button><button class="reschedule-button btn btn-secondary ml-2"><i class="fas fa-clock fa-2x"></i></button><button class="cancel-button btn btn-danger ml-2"><i class="fas fa-times-circle fa-2x"></i></button>'],
            ["18/06/2021", "08:00", "Instalação", "Ar Condicionado", "Cachoeirinha", "Fulano", "(51) 99999-9999", "Rua Osorio Correia, 250, apto 204 bloco B", '<button class="start-button btn btn-primary"><i class="fas fa-play-circle fa-2x"></i></button><button class="reschedule-button btn btn-secondary ml-2"><i class="fas fa-clock fa-2x"></i></button><button class="cancel-button btn btn-danger ml-2"><i class="fas fa-times-circle fa-2x"></i></button>'],
            ["18/06/2021", "08:00", "Instalação", "Ar Condicionado", "Cachoeirinha", "Fulano", "(51) 99999-9999", "Rua Osorio Correia, 250, apto 204 bloco B", '<button class="start-button btn btn-primary"><i class="fas fa-play-circle fa-2x"></i></button><button class="reschedule-button btn btn-secondary ml-2"><i class="fas fa-clock fa-2x"></i></button><button class="cancel-button btn btn-danger ml-2"><i class="fas fa-times-circle fa-2x"></i></button>'],
            ["18/06/2021", "08:00", "Instalação", "Ar Condicionado", "Cachoeirinha", "Fulano", "(51) 99999-9999", "Rua Osorio Correia, 250, apto 204 bloco B", '<button class="start-button btn btn-primary"><i class="fas fa-play-circle fa-2x"></i></button><button class="reschedule-button btn btn-secondary ml-2"><i class="fas fa-clock fa-2x"></i></button><button class="cancel-button btn btn-danger ml-2"><i class="fas fa-times-circle fa-2x"></i></button>'],
            ["18/06/2021", "08:00", "Instalação", "Ar Condicionado", "Cachoeirinha", "Fulano", "(51) 99999-9999", "Rua Osorio Correia, 250, apto 204 bloco B", '<button class="start-button btn btn-primary"><i class="fas fa-play-circle fa-2x"></i></button><button class="reschedule-button btn btn-secondary ml-2"><i class="fas fa-clock fa-2x"></i></button><button class="cancel-button btn btn-danger ml-2"><i class="fas fa-times-circle fa-2x"></i></button>'],
            ["18/06/2021", "08:00", "Instalação", "Ar Condicionado", "Cachoeirinha", "Fulano", "(51) 99999-9999", "Rua Osorio Correia, 250, apto 204 bloco B", '<button class="start-button btn btn-primary"><i class="fas fa-play-circle fa-2x"></i></button><button class="reschedule-button btn btn-secondary ml-2"><i class="fas fa-clock fa-2x"></i></button><button class="cancel-button btn btn-danger ml-2"><i class="fas fa-times-circle fa-2x"></i></button>'],
            ["18/06/2021", "08:00", "Instalação", "Ar Condicionado", "Cachoeirinha", "Fulano", "(51) 99999-9999", "Rua Osorio Correia, 250, apto 204 bloco B", '<button class="start-button btn btn-primary"><i class="fas fa-play-circle fa-2x"></i></button><button class="reschedule-button btn btn-secondary ml-2"><i class="fas fa-clock fa-2x"></i></button><button class="cancel-button btn btn-danger ml-2"><i class="fas fa-times-circle fa-2x"></i></button>'],
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
                "columns": [{
                        title: "Data"
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
                        title: "Ações"
                    },
                ],

                initComplete: function() {
                    // Apply the search






                }
            }).buttons().container().appendTo('#examples_wrapper .col-md-6:eq(0)');

        $("#dataTable").on("click", ".start-button", function(e) {
            e.preventDefault();
            alert("start");
        })
        $("#dataTable").on("click", ".reschedule-button", function(e) {
            e.preventDefault();
            alert("reschedule");
        })
        $("#dataTable").on("click", ".cancel-button", function(e) {
            e.preventDefault();
            alert("cancel");
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

<?= $this->openCard("Agendamentos") ?>


<table id="dataTable" class="table table-bordered table-striped" width="100%">



</table>




<?= $this->closeCard() ?>
<?= $this->endSection() ?>