<?= $this->extend(($layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('custom_css') ?>
<link rel="stylesheet" href="/scanner/scanner.css">
<style>
    .schedule-image {
        width: 95%;
        margin: 3px;
    }

    .image-wrapper {
        width: 40%;
        border: 1px solid silver;
    }

    .icon-image-delete {
        position: absolute;
        top: 0px;
        right: 0px;
        border-radius: 50%;
        background-color: white;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('custom_scripts') ?>
<script type="module">
    import B64 from "/js/b64.js";

    import QrScanner from "/scanner/qr-scanner/qr-scanner.min.js";
    QrScanner.WORKER_PATH = '/scanner/qr-scanner/qr-scanner-worker.min.js';

    const videoElement = document.getElementById('qr-video');
    const scanRegionContainer = document.getElementById('scan-region-container');
    const startButton = document.getElementById('start-button');
    const stopButton = document.getElementById('stop-button');
    const helpMessage = document.getElementById('help-message');


    const displayResultPanel = document.getElementById('display-result');
    const displayAttendeeId = document.getElementById('display-custid');
    const displayObjectId = document.getElementById('display-objid');
    const displayDescription = document.getElementById('display-desc');


    function startScan() {
        //scanner.start();

        scanner.start().then(() => {
            scanRegionContainer.appendChild(scannerCanvas);
            startButton.style.display = "none";
        });

        scannerCanvas.style.display = "block";
        startButton.style.display = "none";
        stopButton.style.display = "block";
        helpMessage.style.display = "none";

        // scannerCanvas.addEventListener('click', () => {
        //     stopScan();
        // });
    }

    function stopScan() {
        scanner.stop();
        scannerCanvas.style.display = "hidden";
        stopButton.style.display = "none";
        startButton.style.display = "block";
        helpMessage.style.display = "flex";


        var ctx = scannerCanvas.getContext("2d");
        ctx.fillStyle = "silver";
        ctx.fillRect(0, 0, scannerCanvas.width, scannerCanvas.height);

        helpMessage.addEventListener('click', () => {
            startScan();
        });

    }

    var scanResult = async function(result) {
        stopScan();


        var step1 = result.split("/qrcode/")
        console.log(step1);
        var step2 = atob(step1[1])
        console.log(step2);
        var step3 = step2.split("|")
        console.log(step3);
        var step4 = atob(step3[0])
        console.log(step4);
        var step5 = step4.split("|")
        console.log(step5);
        console.log(step5[2]);

        $("#object_uid-field").val(step5[2]);

        $('#qr-code-modal').modal('hide')




    }

    const scanner = new QrScanner(videoElement, result => scanResult(result), error => {
        //camQrResult.textContent = error;
        //camQrResult.style.color = 'inherit';
    });

    const scannerCanvas = scanner.$canvas;
    scannerCanvas.id = "scannerCanvas";



    startButton.addEventListener('click', () => {
        startScan();
    });

    stopButton.addEventListener('click', () => {
        stopScan();
    });

    scannerCanvas.addEventListener('click', () => {
        startScan();
    });

    // for debugging
    window.scanner = scanner;




    var imageCount = 0;

    function showImage(e) {
        $('#imagepreview').attr('src', $(e.currentTarget).attr('src'));
        // here asign the image to the modal when the user click the enlarge link
        $('#imagemodal').modal('show');
        // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
    }

    $(function() {
        $("#add-new-photo").on("click", function(e) {
            var inputGroup = $("#photo-input-group");
            var imageGroup = $("#photo-image-group");
            //var imageCount = imageGroup.children().length
            imageCount++;
            var img = $('<div id="image-wrapper-' +
                imageCount + '" class="image-wrapper position-relative mt-3"><img id="img-' +
                imageCount + '" class="schedule-image"><i id="icon-delete-' +
                imageCount + '" class="icon-image-delete fas fa-times-circle fa-2x text-danger"></i></div>')


            //var inputCount = inputGroup.children().length
            var input = $('<input id="image-input-' + imageCount + '" target="img-' +
                imageCount + '" class="d-none" type="file" name="image[]" accept="image/*" capture>')


            input.on("change", function(e) {
                console.log(this.files[0]);

                var fReader = new FileReader();
                fReader.item_target = imageCount;
                fReader.readAsDataURL(this.files[0]);

                fReader.onloadend = function(e) {
                    console.log(e);
                    var len = $("#photo-image-group").children().length
                    var imgid = "#img-" + (this.item_target)
                    var img = $(imgid)
                    img.attr("src", event.target.result);
                    img.on("click", e => {
                        console.log("clicou")
                    })
                }
            });


            imageGroup.append(img)
            inputGroup.append(input)
            input.click();
            $(".icon-image-delete").on("click", e => {
                e.preventDefault();
                e.stopImmediatePropagation();
                console.log("delete", e.currentTarget)
                var id = e.currentTarget.id.replace("icon-delete-", "")
                $("#image-wrapper-" + id).remove();
                $("#image-input-" + id).remove();
            })

            $(".schedule-image").on("click", showImage);

        });

        $(".schedule-image").on("click", showImage);

        $("#scan-code").on("click", function (e) {
            $('#qr-code-modal').modal('show')
            startScan()

        })

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
<!-- Creates the bootstrap modal where the image will appear -->
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <!--span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button-->
                    <h4 class="modal-title" id="myModalLabel">Zoom</h4>
            </div>
            <div class="modal-body">
                <img src="" id="imagepreview" style="width: 100%;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<?= $this->openCard("Dados do Agendamento:") ?>
<div class="container mt-2">
    <form method="post" enctype="multipart/form-data" id="formSaveSchedule" name="formSaveSchedule" action="/field/schedule/save">
        <?= csrf_field() ?>
        <input type="hidden" name="schedule_uid" value="<?= $schedule->schedule_uid ?>">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="staticCustomerName">Cliente</label>
                <input type="text" readonly class="form-control" id="staticCustomerName" value="<?= $schedule->customer_name ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="staticContact">Contato para Instalação</label>
                <input type="text" readonly class="form-control" id="staticContact" value="<?= $schedule->schedule_contact_name ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="staticContactPhone">Telefone</label>
                <input type="text" readonly class="form-control" id="staticContactPhone" value="<?= $schedule->schedule_contact_phone ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="staticServiceName">Serviço</label>
                <input type="text" readonly class="form-control" id="staticServiceName" value="<?= $schedule->schedule_service_name ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="staticObject">Equipamento</label>
                <input type="text" readonly class="form-control" id="staticObject" value="<?= $schedule->schedule_object_name ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="object_uid">Etiqueta (Object Uid)</label>
            <div class="input-group mb-3">
                <?php if (!isset($schedule->object_uid)) : ?>
                    <div class="input-group-prepend">
                        <button id="scan-code" class="btn btn-outline-primary" type="button" d-ata-toggle="modal" d-ata-target="#qr-code-modal">
                            <span class="d-none d-lg-inline-block">Inserir</span>
                            <i class="fas fa-qrcode ml-lg-2"></i>
                        </button>
                    </div>
                    <input readonly name="object_uid" id="object_uid-field" type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon1">
                <?php else : ?>
                    <input readonly name="object_uid" id="object_uid-field" type="text" value="<?= $schedule->object_uid ?>" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon1">
                    <div class="input-group-append">
                        <button id="scan-code" class="btn btn-outline-danger" type="button">
                            <span>Mudar</span>
                            <i class="fas fa-qrcode"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="form-group">
            <label for="description">Descrição</label>
            <textarea class="form-control" name="description" id="description" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="description" class="d-none">Fotos</label>
            <button id="add-new-photo" type="button" class="btn btn-outline-primary">
                <i class="fas fa-camera mr-2"></i>Nova foto</button>
            <div id="photo-input-group"></div>
            <div id="photo-image-group" class="mt-3 d-flex justify-content-around flex-wrap">
                <?php
                if (isset($files) && is_array($files) && count($files) > 0) {
                    foreach ($files as $key => $file) {
                        echo
                        '<div id="image-wrapper-stored-' . $key . '" class="image-wrapper position-relative mt-3">
                            <img id="img-stored-' . $key . '" class="schedule-image" src="/images/uploads/' .
                            $file->file_folder . '/' . $file->file_name . '">
                        </div>';
                    }
                }
                ?>
            </div>
        </div>
        <div class="row justify-content-around">
            <input type="submit" name="post_action" value="Salvar" class="btn btn-primary col-5 m-1">
            <input type="submit" name="post_action" value="Concluir" class="btn btn-success col-5 m-1">
        </div>
    </form>
    <?= $this->closeCard() ?>

    
    <?= $this->openModal("qr-code", "Ler QR Code") ?>
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
        <?= $this->closeModal(
        "qr-code",
        [
        //     "close" =>  ["label" => "Fechar", "tag" => "button", "type" => "button", "class" => "btn-secondary", "data-dismiss" => "modal"],
        //     "submit" => ["label" => "Confirmar", "class" => "btn-success", "type" => "submit", "data-dismiss" => ""]
        ]
    );
    ?>


    <?= $this->endSection() ?>