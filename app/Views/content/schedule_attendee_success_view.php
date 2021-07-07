<?= $this->extend(($layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('custom_css') ?>
<style>
    html,
    body {
        height: 100%;

    }

    .logo {
        max-height: 25vh;
        max-width: 40vw;
    }

    .card-title {
        font-size: small;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('custom_scripts') ?>
<script>
    $(() => {

    });
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="container mt-3">
    <header class="text-center">
        <img class="logo" src="/images/logo.png">
    </header>
    <div id="step1">

        <div class="row">
            <div class="col-12 text-center">
                <p>Sua solicitação de agendamento foi enviada para nossa equipe!</p>
                <p>Em breve entraremos em contato para confirmar de seu agendamento.</p>
            </div>
        </div>


        <div class="form-group col-12 ">
            <div class="row">
                <div class="col-12 col-lg-6 m-auto overflow-auto border" style="height: 225px;">
                    <table class="table table-sm ">
                        <tbody>

                            <tr>
                                <th scope="row">Data</th>
                                <td><?= str_replace("-T"," Tarde",str_replace("-M"," Manhã",$scheduleDate ?? "")) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Serviço</th>
                                <td><?= $scheduleService ?? null ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Contato</th>
                                <td><?= $scheduleContact ?? null ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Telefone</th>
                                <td><?= $schedulePhone ?? null ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Observações</th>
                                <td><?= htmlentities($scheduleDescription) ?? null ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <a href="/attendee/finish" class="btn btn-success mt-3 col-xs-12 col-md-6 ">Encerrar Atendimento</a>
        </div>
     

    </div>

</section>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?= $this->endSection() ?>