<?= $this->extend(( $layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('custom_css') ?>
<link rel="stylesheet" href="/scanner/scanner.css">
<style>
    html,
    body {
        height: 100%;
    }

    .overlay {
        display: none;
    }

    .show-modal .overlay {
        display: block;
        z-index: 100000;
        position: fixed;
        left: 0;
        bottom: 0;
        right: 0;
        top: 0;
        background-color: aqua;
    }

    .error {
        display: block;
        padding-top: 5px;
        font-size: 14px;
        color: red;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('custom_scripts') ?>

<script type="module">
    import ScannerClass from "/scanner/scanner.js";

    var scanSuccess = async function(result) {
        //stopScanInterno();
        // var b64 = B64.encode(result);
        // alert("encontrei: " + b64);
        // doPost(b64);
        var s = result.split("/qr/")
        var step = s[s.length-1]
        var code = atob(step)
        $("#object_uid").val(code)
        $("body").removeClass("show-modal");


    }

    var scanError = async function(result) {
        //stopScanInterno();
        // var b64 = B64.encode(result);
        // alert("encontrei: " + b64);
        // doPost(b64);
    }


    const scanner = new ScannerClass(scanSuccess, scanError);




    $(document).ready(function() {



        $("#scan-code").on("click", e => {
            $("body").addClass("show-modal");
            scanner.startScan();
        })


        if ($("#add_create").length > 0) {
            $("#add_create").validate({
                rules: {
                    object_uid: {
                        required: true,
                    },
                    object_owner_uid: {
                        required: true,
                    },
                    description: {
                        required: true,
                        maxlength: 60,
                        //email: true,
                    },
                },
                messages: {
                    object_uid: {
                        required: "Object is required.",
                    },
                    object_owner_uid: {
                        required: "Owner is required.",
                    },
                    description: {
                        required: "Email is required.",
                        //email: "It does not seem to be a valid email.",
                        maxlength: "The email should be or equal to 60 chars.",
                    },
                },
            })
        }



    });
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="overlay">
    <div class="p-3">
        <h3 class="text-center">Use sua câmera</h3>
        <p class="text-center">para ler a etiqueta com o QR Code</p>


        <div id="display-result" class="modal fade">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Resultado</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">OBJECT ID</div>
                            <div class="col-sm-12 col-md-6" id="display-objid"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">Attendee ID</div>
                            <div class="col-sm-12 col-md-6" id="display-custid"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">Descrição</div>
                            <div class="col-sm-12 col-md-6" id="display-desc"></div>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="d-flex justify-content-center flex-column">
            <video style="display:none" id="qr-video"></video>


            <div class="d-flex justify-content-center align-content-center">
                <div id="scan-region-container" class="position-relative">

                    <div id="help-message" class="bg-info justify-content-center align-items-center flex-column">

                        <i class="bi bi-play-circle"></i>
                        <span>Reiniciar leitura </span>
                        <span>do QR Code</span>

                    </div>

                </div>
            </div>
            <br>
            <div class="d-flex justify-content-around">
                <button type="button" class="btn btn-info" id="start-button">
                    <i class="bi bi-play-circle"></i> Reiniciar
                </button>
                <button type="button" class="btn btn-danger" id="stop-button">
                    <i class="bi bi-stop-circle"></i> Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
<section class="m-1 m-md-3 m-lg-5 p-1 p-md-3 p-lg-5 bg-white border border-info rounded d-flex flex-column">

    <div class="container mt-5">
        <form method="post" id="add_create" name="add_create" action="<?= route_to('App\Controllers\QrObjectController::save') ?>">
            <div class="form-group">
                <label for="object_uid">Etiqueta (Object Uid)</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <button id="scan-code" class="btn btn-outline-secondary" type="button">
                            <span>Scan QR Code</span>
                            <i class="fas fa-qrcode"></i>
                        </button>
                    </div>
                    <input name="object_uid" id="object_uid" type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon1">
                </div>
            </div>
            <div class="form-group">
                <label for="object_owner_uid">Proprietário (Owner Uid)</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <button id="scanQrCode" class="btn btn-outline-secondary" type="button">
                            <span>Buscar</span>
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <input name="object_owner_uid" id="object_owner_uid" type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon1">
                </div>
            </div>


            <div class="form-group">

                <label for="description">Descrição</label>
                <textarea class="form-control" name="description" id="description" rows="3"></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Salvar</button>
            </div>
        </form>
    </div>


</section>
<?= $this->endSection() ?>