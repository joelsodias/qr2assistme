<?= $this->extend(($layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('custom_css') ?>
<style>
    .font-xss {
        font-size: xx-small;
    }

    .font-xs {
        font-size: x-small;
    }

    .font-s {
        font-size: small;
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('custom_scripts') ?>
<script type="text/javascript">
    function popuponclick() {
        
        my_window = window.open('', '_blank');
        //window.open('', 'mywindow', 'status=1,width=350,height=150');
        my_window.document.write('<html><head>');
        my_window.document.write('<title>Impressão de Etiquetas QR Code</title>');
        my_window.document.write('<link rel="stylesheet" href="/adminlte/dist/css/adminlte.css">');
        my_window.document.write('</head>');
        my_window.document.write('<body onafterprint="self.close()">');
        my_window.document.write('<div class="container-fluid">');
        my_window.document.write('<div id="printarea" class="row"></div>');
        my_window.document.write('</div>');
        my_window.document.write('</body></html>');
        labels = document.getElementById("labels");
        printarea = my_window.document.getElementById("printarea");
        printarea.innerHTML = labels.innerHTML
        my_window.focus(); 
        //my_window.print();
    }

    $(document).ready(function() {
        $(".print-button").on("click", function (e) {
           popuponclick();
        });
    });

</script>
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<?= $this->openCard($content_header_subtitle ?? "") ?>

    <div class="row d-print-none">
        <button class="print-button btn btn-primary"><i class="fas fa-print mr-2"></i>Visualizar Impressão</button>
    </div>
    <div id="labels" class="row">
        <?php for ($i = 0; $i <= $qtd - 1; $i++) { ?>
            <?php //for ($i2 = 0; $i2 <= 1; $i2++) { 
            ?>
            <div class="col-sm-6 col-md-4 col-lg-4 bg-white ">
                <div class="row p-2">
                    <div class="col-12 border border-primary rounded">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-center text-black flex-column">
                                <span class="text-monospace font-s font-weight-bold text-center text-uppercase"><?= $label_header ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4"><img width="100px" src="<?= $qr[$i] ?>"></div>
                            <div class="col-8 d-flex flex-column justify-content-center align-items-center">
                                <p class="p-1 font-s">
                                    Use a câmera do seu celular para ler o QR Code ao lado ou acesse: <br />
                                    <?=getenv('app.baseURL') ?>
                                </p>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12 d-flex justify-content-center text-black flex-column">
                                <span class="text-monospace font-xs text-center"><?= $id[$i] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php //} 
            ?>
        <?php } ?>
    </div>


<?= $this->closeCard() ?>
<?= $this->endSection() ?>