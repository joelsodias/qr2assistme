<?= $this->extend(($layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('custom_css') ?>
<style>

</style>
<?= $this->endSection() ?>

<?= $this->section('custom_scripts') ?>

<script>
    

    $(function() {
        
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
<?= $this->openCard("Funcionalidade") ?>


<div class="card-body">


<h2>Funcionalidade ainda não implementada no protótipo</h2>

</div>


<?= $this->closeCard() ?>
<?= $this->endSection() ?>