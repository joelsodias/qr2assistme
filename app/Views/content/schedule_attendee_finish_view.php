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
                <p>Muito obrigado por entrar em contato!</p>
                <p>Tenha uma ótima semana.</p>
            </div>
        </div>

    </div>

</section>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?= $this->endSection() ?>