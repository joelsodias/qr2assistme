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
<script type="text/javascript" src="/js/cleave/cleave.js"></script>
<script type="text/javascript" src="/js/cleave/cleave-phone.i18n.js"></script>
<!-- <script type="text/javascript" src="https://nosir.github.io/cleave.js/dist/cleave-phone.i18n.js"></script> -->
<script>
    $(() => {
        var cleave = new Cleave('#schedule_phone', {
            phone: true,
            phoneRegionCode: 'BR'
        });

        $("#schedule_form").validate({
            rules: {
                schedule_contact: {
                    required: true,
                  
                },
                schedule_service: {
                    required: true,
                    notEqual: "",
                },
                schedule_phone: {
                    required: true,
                    minlength: 9
                },
                schedule_description: {
                    required: false,
                },
            },
            messages: {
                schedule: {
                    required: "",
                },
                schedule_contact: {
                    required: "Favor informar um nome de contato",
                },
                schedule_service: {
                    required: "Favor escolher o tipo de serviço",
                },
                schedule_phone: {
                    required: "Favor informar um telefone para contato",
                    minlength: "digite no mínimo 8 digitos",
                },
            },
        })



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
                <p>Escolha uma data disponível para seu agendamento:</p>
            </div>
        </div>

        <form id="schedule_form" method="POST" action="/attendee/schedule/identified">
            <?= csrf_field() ?>
            <div class="form-group col-12 ">
                <div class="row">
                    <div class="col-12 col-lg-6 m-auto overflow-auto border" style="height: 225px;">
                        <table class="table table-sm ">

                            <tbody>
                                <?php
                                $scheduleDate   = new DateTime();

                                $week = array(
                                    '1' => 'Seg',
                                    '2' => 'Ter',
                                    '3' => 'Qua',
                                    '4' => 'Qui',
                                    '5' => 'Sex',
                                    '6' => 'Sáb',
                                    '7' => 'Dom',
                                );

                                for ($i = 0; $i <= 20; $i++) :

                                    $scheduleDate->modify('+1 day');
                                    if ($scheduleDate->format('N') >= 7) {
                                        continue;
                                    }
                                ?>
                                    <tr>
                                        <th scope="row"><?= $scheduleDate->format('d/m/Y') . '(' . $week[$scheduleDate->format('N')] . ')'; ?></th>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input required class="form-check-input" type="radio" name="schedule" id="schedule-<?= $scheduleDate->format('Y-m-d') ?>-M" value="<?= $scheduleDate->format('Y-m-d') ?>-M">
                                                <label class="form-check-label" for="inlineRadio1">manhã</label>
                                            </div>
                                            <?php if ($scheduleDate->format('N') < 6) : ?>
                                                <div class="form-check form-check-inline">
                                                    <input required class="form-check-input" type="radio" name="schedule" id="schedule-<?= $scheduleDate->format('Y-m-d') ?>-T" value="<?= $scheduleDate->format('Y-m-d') ?>-T">
                                                    <label class="form-check-label" for="inlineRadio2">tarde</label>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endfor; ?>

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
        </form>
    </div>

</section>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?= $this->endSection() ?>