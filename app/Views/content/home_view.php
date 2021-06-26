<?= $this->extend(( $layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('content') ?>
<section class="m-1 m-md-3 m-lg-5 p-1 p-md-3 p-lg-5 bg-white border border-info rounded d-flex flex-column">
<a class="btn btn-outline-dark m-1" href="/home/etiquetas">Gerar etiquetas</a>
<a class="btn btn-outline-dark m-1" href="/home/reader">Ligar com cliente</a>
<a class="btn btn-outline-dark m-1" href="/home/reader">Landpage atendimento</a>
</section>
<?= $this->endSection() ?>