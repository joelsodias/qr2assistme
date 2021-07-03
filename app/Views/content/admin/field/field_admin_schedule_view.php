<?= $this->extend(($layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('custom_css') ?>
<style>
    .photo-canvas {
        width: 100px;
        height: 100px;
        border: 1px solid red;
        margin: 3px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('custom_scripts') ?>

<script>
    $(function() {
        $("#add-new-photo").on("click", function(e) {
            var inputGroup = $("#photo-input-group");
            var imageGroup = $("#photo-image-group");
            var imageCount = imageGroup.children().length
            var img = $('<img id="img-' + imageCount + '" class="photo-canvas">')
            // var img = $('<canvas id="cv-' + canvasGroup.children().length + '" class="photo-canvas"></canvas>')
            imageGroup.append(img)

            var inputCount = inputGroup.children().length
            var input = $('<input id="input-' + inputCount + '" target="img-' + imageCount + '" class="d-none" type="file" name="image[]" accept="image/*" capture>')

            input.on("change", function(e) {
                console.log(this.files[0]);
                // var imgid = this.attributes["target"].value
                // var img = $("#" + imgid)
                // img.attr("src", this.value)
                // img.removeClass("d-none")

                var fReader = new FileReader();
                fReader.readAsDataURL(this.files[0]);
                fReader.onloadend = function(event) {
                    console.log("loaded");
                    var len = $("#photo-image-group").children().length
                    var imgid = "#img-" + (len-1)
                    var img = $(imgid)   
                    img.attr("src",event.target.result);
                }


            });

            inputGroup.append(input)
            input.click();

        });

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

<?= $this->openCard("Lista de agendamentos criados para você") ?>


<pre>

<?= print_r($schedule) ?>

</pre>

<div class="container mt-5">
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
                        <button id="scan-code" class="btn btn-outline-primary" type="button">
                            <span>Inserir</span>
                            <i class="fas fa-qrcode"></i>
                        </button>
                    </div>
                    <input readonly name="object_uid" id="object_uid" type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon1">
                <?php else : ?>
                    <input readonly name="object_uid" id="object_uid" type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon1">
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
            <label for="description">Fotos</label>
            <button id="add-new-photo" type="button" class="btn btn-outline-primary">Nova foto</button>
            <div id="photo-input-group"></div>
            <div id="photo-image-group" class="d-flex flex-wrap"></div>
            <div id="imageGroup" class="input-group mb-3">
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




<?= $this->closeCard() ?>
<?= $this->endSection() ?>