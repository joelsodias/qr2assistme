<?= $this->extend(( $layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section("custom_css") ?>
<style>
    #scan-region-container {
        background: #fff;
        border: 3px solid #000;
        border-radius: 15px;
        overflow: hidden;
        width: 400px;
    }

    #scan-region-container>canvas {
        width: 100%;
        height: 100%;
        position: relative;
    }

    #help-message {
        display: none;
        position: absolute;
        z-index: 10;
        left: 0;
        right: 0;
        bottom: 0;
        top: 0;
    }

    #help-message>.bi {
        font-size: 3rem;
        color: white;
    }

    #display-result {
        display: none;

    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
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
        scanner.start();
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

    var doPost = async function(b64) {

        // Get the post data
        var postResp = await fetch('<?=getenv('app.baseURL') ?>/admin/api/insertobject/' + b64);

        var post = await postResp.json();

        console.log(post);

        displayAttendeeId.textContent = post.attendee_id;
        displayObjectId.textContent = post.object_uuid;
        displayDescription.textContent = post.description;
        displayResultPanel.style.display = "block";
        
        //displayResultPanel.modal('show');
        s$('#display-result').modal('show');

    };


    var scanResult = async function(result) {
        stopScan();

        window.location.href = result;

        // var b64 = B64.encode(result);
        // alert("encontrei: " + b64);
        // doPost(b64);
    }

    const scanner = new QrScanner(videoElement, result => scanResult(result), error => {
        //camQrResult.textContent = error;
        //camQrResult.style.color = 'inherit';
    });

    const scannerCanvas = scanner.$canvas;
    scannerCanvas.id = "scannerCanvas";

    scanner.start().then(() => {
        scanRegionContainer.appendChild(scannerCanvas);
        startButton.style.display = "none";
    });

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
</script>
<?= $this->endSection() ?>