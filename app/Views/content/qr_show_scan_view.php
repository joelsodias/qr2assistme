<?= $this->extend(($layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('custom_css') ?>
<style>
    html,
    body {
        height: 100%;

    }

    .logo {
        max-height: 25vh;
        max-width: 80vw;
    }

    .card-title {
        font-size: small;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('custom_scripts') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="container mt-3">
    <header class="text-center">
        <img class="logo" src="/images/logo.png">
    </header>
    <div id="step1">
        <div class="row">
            <div class="col-12 text-center">
                <p>Aqui estão as informações sobre o Objeto associado ao QR Code lido:</p>
            </div>
        </div>


        <div class="row">
            <div class="col-12 col-lg-6 m-auto">
               


                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">Informação</th>
                        </tr>
                    </thead> 
                    <tbody>
                        <tr>
                            <th scope="row">Objeto</th>
                            <td><?= $object->object_name ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Marca</th>
                            <td><?= $object->object_brand ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Modelo</th>
                            <td><?= $object->object_model ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Serial</th>
                            <td><?= $object->object_serial ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Voltagem</th>
                            <td><?= $object->object_voltage ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Instalação</th>
                            <td><?= date("d/m/Y",strtotime($object->object_instalation_date)) ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Última revisão</th>
                            <td><?= date("d/m/Y",strtotime($object->object_last_review_date)) ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Próxima revisão</th>
                            <td><?= date("d/m/Y",strtotime($object->object_next_review_date)) ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Garantia Fabricante</th>
                            <td><?= date("d/m/Y",strtotime($object->object_maker_warranty_exp_date)) ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Site do Fabricante</th>
                            <td><a href="http://<?= $object->object_brand ?>.com.br"><?= $object->object_brand ?></a></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-center"><?= $object->object_uid ?></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>


        <div class="row mt-2">
            <div class="col-12 text-center">
                <a id="gotoStep2" href="/attendee/login/schedule" class="btn btn-danger mt-3 col-xs-12 col-md-6 ">Quero agendar uma visita de <br/>assistência para ele</a>
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