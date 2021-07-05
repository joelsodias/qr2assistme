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
                <p>Sua solicitação de agendamento foi enviado para nossa equipe!</p>
                <p>Em breve você receberá a confirmação de seu agendamento.</p>
            </div>
        </div>


        <div class="form-group col-12 ">
            <div class="row">
                <div class="col-12 col-lg-6 m-auto overflow-auto border" style="height: 225px;">
                    <table class="table table-sm ">
                        <tbody>
              
                            <tr>
                                <th scope="row">Data</th>
                                <td><?= $scheduleDate ?? null ?></td>
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
            <div class="invalid-feedback">
                Favor escolher um dia e turno
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 col-lg-6 m-auto ">
                <div class="form-group col-12">
                    <label for="inputService">Tipo de Atendimento</label>
                    <select required name="schedule_service" id="schedule_service" class="form-control">
                        <option value="">Escolha o serviço...</option>
                        <option value="service_install">Instalação</option>
                        <option value="service_repair">Reparo/Conserto</option>
                        <option value="service_review">Revisão</option>
                        <option value="service_cleaning">Limpeza</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 col-lg-6 m-auto ">
                <div class="form-group col-12">
                    <label for="inputFone">Nome de Contato</label>
                    <input required type="tel" id="schedule_contact" name="schedule_contact" class="form-control" value="<?= $user_name ?? "" ?>">
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 col-lg-6 m-auto ">
                <div class="form-group col-12">
                    <label for="inputFone">Telefone de Contato (099) 99999 9999</label>
                    <input required type="tel" id="schedule_phone" name="schedule_phone" class="form-control">
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 col-lg-6 m-auto ">
                <div class="form-group col-12">
                    <label for="inputFone">Observações</label>
                    <textarea class="form-control" id="schedule_description" name="schedule_description" rows="3"></textarea>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 text-center">
                <button id="send-form-button" type="submit" class="btn btn-danger mt-3 col-xs-12 col-md-6 ">Agendar</button>
                <a href="/attendee/login/chat" class="btn btn-info mt-3 col-xs-12 col-md-6 ">Desejo atendimento via chat</a>
            </div>
        </div>

    </div>

</section>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?= $this->endSection() ?>