<?= $this->extend(($layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('custom_css') ?>
<link rel="stylesheet" href="/chatjs/chat_common.css">
<?= $this->endSection() ?>


<?= $this->section('custom_scripts') ?>

<script src="/chatjs/chat_common.js" type="text/javascript"></script>
<script src="/chatjs/chat_sync_client.js" type="text/javascript"></script>

<script type="text/javascript">
    var current_SUID = "<?= $session->session_uid ?>"
    var current_UUID = "<?= $session->attendee_uid ?>"

    $(document).ready(function() {

    });
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section id="chat" class="attendee h-100 d-flex flex-column bg-white ">
    <div id="chat-right" class="col-12 ">
        <div id="chat-right-top" class="d-flex bg-light border-bottom p-2 flex-row justify-content-between align-items-center">
            <div class="profile">
                <img class="avatar" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=">
            </div>
            <div class="w-100">Conversa com AIRCON SERVICES</div>
            <div>
                <a href="" class="">
                    <img class="attendee-avatar" src="<?= $attendee->google_avatar ?>">
                </a>
            </div>
        </div>
        <div id="chat-right-messages" class="small">
            <div id="chat-message-list" class="small p-3"></div>
        </div>
        <div id="chat-right-bottom" class="bg-light border-top">
            <div id="chat-footer" class="d-flex justify-content-end align-items-center p-2">
                <textarea id="input-message" rows="3" class="small rounded border-black w-100"></textarea>
                <div class="text-center m-3">
                    <a id="submit-message" href="#" class="bg-primary rounded-circle text-center p-3">
                        <i class="fas fa-play-circle fa-lg text-white"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>
<?= $this->endSection(); ?>