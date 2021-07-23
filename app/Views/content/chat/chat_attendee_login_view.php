<?= $this->extend(( $layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('custom_css') ?>
<style>
    html,
    body,
    main {
        box-sizing: border-box;
        height: 100%;
        overflow: hidden;
        padding: 0px;
        margin: 0px;
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


    });
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="h-100 w-100 border d-flex justify-content-center align-items-center">
<!-- <div
  class="fb-like"
  data-share="true"
  data-width="450"
  data-show-faces="true">
</div> -->
    <div class="">
    <header class="text-center">
        <img class="logo" src="/images/logo.png">
    </header>
        <div class="row m-3">
            <div class="col-12 d-flex justify-content-center align-items-center">
                Escolha abaixo algum dos logins sociais para podermos continuar...
            </div>
        </div>
        
        <div class="row m-3">
            <div class="col-12 d-flex justify-content-center align-items-center flex-wrap">
                <a href="/attendee/login/<?= $service ?>/google/<?= str_replace(array('+', '/'), array('-', '_'),base64_encode($google["auth_url"])) ?? "#error" ?>" class="btn btn-danger  m-3"><i class="fab fa-google-plus-square fa-3x"></i></a>
                <a href="/attendee/login/<?= $service ?>/facebook/<?= str_replace(array('+', '/'), array('-', '_'),base64_encode($facebook["auth_url"])) ?? "#error" ?>" class="btn btn-primary m-3"><i class="fab fa-facebook-square fa-3x"></i></a>
                <!-- <a href="/attendee/chat/guest" class="btn btn-dark m-3"><i class="fab fa-linkedin fa-3x"></i></a>
                <a href="/attendee/chat/guest" class="btn btn-dark m-3"><i class="fab fa-microsoft fa-3x"></i></i></a>
                <a href="/attendee/chat/guest" class="btn btn-dark m-3"><i class="fab fa-instagram fa-3x"></i></i></a> -->
            </div>
        </div>

        <!--div class="row m-3">
            <div class="col-12 d-flex justify-content-center align-items-center">

                <a href="/attendee/chat/guest" class="text-secondary">Desejo continuar sem identificação</a>
            </div>
        </div-->
    </div>
</section>
<?= $this->endSection() ?>