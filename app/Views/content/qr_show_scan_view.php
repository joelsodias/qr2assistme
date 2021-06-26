<?= $this->extend(( $layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('custom_css') ?>
<?= $this->endSection() ?>

<?= $this->section('custom_scripts') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="m-1 m-md-3 m-lg-5 p-1 p-md-3 p-lg-5 border-info">
<header>
Show Scan
</header>
<div>
<?= base64_decode($code) ?>
</div>
</section>
<?= $this->endSection() ?>