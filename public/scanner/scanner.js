import B64 from "/js/b64.js";

import QrScanner from "/scanner/qr-scanner/qr-scanner.min.js";
QrScanner.WORKER_PATH = '/scanner/qr-scanner/qr-scanner-worker.min.js';


const videoElement = document.getElementById('qr-video');
const scanRegionContainer = document.getElementById('scan-region-container');
const startButton = document.getElementById('start-button');
const stopButton = document.getElementById('stop-button');
const helpMessage = document.getElementById('help-message');
const displayResultPanel = document.getElementById('display-result');
const displayCustomerId = document.getElementById('display-custid');
const displayObjectId = document.getElementById('display-objid');
const displayDescription = document.getElementById('display-desc');

const scanner = new QrScanner(videoElement, result => scanResult(result), error => {
    //camQrResult.textContent = error;
    //camQrResult.style.color = 'inherit';
});

const scannerCanvas = scanner.$canvas;
scannerCanvas.id = "scannerCanvas";

startButton.addEventListener('click', () => {
    startScanInterno();
});

stopButton.addEventListener('click', () => {
    stopScanInterno();
});

scannerCanvas.addEventListener('click', () => {
    startScanInterno();
});

var scanResult = async function (result) {
    stopScanInterno();
    // var b64 = B64.encode(result);
    // alert("encontrei: " + b64);
    // doPost(b64);
}

var doPost = async function (b64) {

    // // Get the post data
    // var postResp = await fetch('https://qr2assistme.test/admin/api/insertobject/' + b64);

    // var post = await postResp.json();

    // console.log(post);

    // displayCustomerId.textContent = post.customer_id;
    // displayObjectId.textContent = post.object_uuid;
    // displayDescription.textContent = post.description;
    // displayResultPanel.style.display = "block";

    // //displayResultPanel.modal('show');
    // s$('#display-result').modal('show');

};


// for debugging
window.scannerInterno = scanner;
window.B64 = B64;


function startScanInterno() {
    console.log("startScanInterno");
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

// REMOVIDO PARA NAO INICIAR AUTOMATIAMENTE
// scanner.start().then(() => {
//     scanRegionContainer.appendChild(scannerCanvas);
//     startButton.style.display = "none";
// });

function stopScanInterno() {
    console.log("stopScanInterno");
    scanner.stop();
    scannerCanvas.style.display = "hidden";
    stopButton.style.display = "none";
    startButton.style.display = "block";
    helpMessage.style.display = "flex";


    var ctx = scannerCanvas.getContext("2d");
    ctx.fillStyle = "silver";
    ctx.fillRect(0, 0, scannerCanvas.width, scannerCanvas.height);

    helpMessage.addEventListener('click', () => {
        startScanInterno();
    });

}

class ScannerClass {

    constructor(onSuccess, onError) {
        this._onSuccess = onSuccess
        this._onError = onError

        window.scannerInterno._onDecode = this._onSuccess
        window.scannerInterno._onDecodeError = this._onError

    }

    startScan() {
        startScanInterno();
    }
    stopScan() {
        stopScanInterno();
    }
}

//export default scanner
export default ScannerClass