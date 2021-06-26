<?= $this->extend(( $layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

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
</style>
<?= $this->endSection() ?>

<?= $this->section('custom_scripts') ?>
<script>
    $(document).ready(function() {

        $("#step2").hide();

        $("#gotoStep2").click(function() {
            $("#step1").hide();
            $("#step2").show();

        });
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
                <p>Como podemos ajudar você?</p>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 text-center">
                <a id="gotoStep2" href="#" class="btn btn-danger mt-3 col-xs-12 col-md-6 ">Quero solicitar assitência para um equipamento</a>
                <a href="/chat/login" class="btn btn-info mt-3 col-xs-12 col-md-6 ">Desejo outro tipo de atendimento</a>
            </div>
        </div>
    </div>
    <div id="step2" style="display:none;">
        <div class="row mt-2">
            <div class="col-12 text-center">
                <h4>Quero solicitar assistência para um equipamento:</h4>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 text-center">
                <?php
                $requestsrv = service('request');
                $agent = $requestsrv->getUserAgent();
                ?>
                <a <?= ($agent->isMobile()) ? 'href="/leitor"' : 'href="#" data-toggle="collapse" data-target="#intrucoes"' ?> class="btn btn-success  d-flex justify-content-around align-items-center">
                    <span class="w-50">Estou perto do equipamento e vejo a etiqueta com o QR Code</span>
                    <i class="fas fa-qrcode fa-3x"></i>
                </a>
                <div id="intrucoes" class="collapse m-5">
                    <h4>Então é muito simples!</h4>
                    </p>Utilize a câmera do seu celular para ler o QR Code!</p>
                    <p>Se sua câmera não consegue ler QR Codes você pode utilizar nosso leitor online!</p>
                    <p>Acesse em seu celular: <a href="<?=getenv('app.baseURL') ?>leitor"><?=getenv('app.baseURL') ?>leitor</a></p>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 text-center">
                <a href="/chat/login" class="btn btn-danger mt-3 col-12">Não estou perto, não tenho celular ou não encontrei o QR Code!</a>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>