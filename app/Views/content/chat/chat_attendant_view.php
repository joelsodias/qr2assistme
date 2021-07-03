<?= $this->extend(($layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('custom_css') ?>
<link rel="stylesheet" href="/chatjs/chat_common.css">
<?= $this->endSection() ?>


<?= $this->section('custom_scripts') ?>
<script src="/chatjs/chat_common.js" type="text/javascript"></script>
<script src="/chatjs/chat_sync_back.js" type="text/javascript"></script>
<script>
    var current_SUID = "<?= $sessions[0]->session_uid ?>"
    var current_UUID = "<?= $attendant->chat_user_uid ?>"

    $(document).ready(function() {

    });
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section id="chat" class="attendant row">
    <div id="chat-left" class="col-4 p-0 border-right">
        <div id="chat-left-top" class="d-flex bg-secondary border-bottom border-black p-2 flex-row justify-content-between align-items-center">
            <div class="profile">
                <img class="profile-avatar" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=">
            </div>
            <div class="text-white w-100">Atendente</div>
            <div>
                <a href="" class="">
                    <i class="fas fa-bars fa-lg text-white"></i>
                </a>
            </div>
        </div>
        <div id="chat-left-wrapper" class="">

            <?php for ($i = 0; $i < count($sessions); $i++) : ?>
                <div id="chat-session-<?= $sessions[$i]->session_uid ?>" class="chat-session d-flex border-bottom">
                    <img class="session-avatar" src="https://source.unsplash.com/300x300/?face&<?= $i ?>">

                    <div class="details w-100 d-flex flex-column justify-content-center ">
                        <div class="details-top d-flex">
                            <div class="user-name d-inline-block w-100 text-truncate"><?= $sessions[$i]->attendee_name ?></div>
                            <div class="last-message-time "><?= \App\Helpers\CustomHelper::time_ago($sessions[$i]->last_message_time) ?></div>
                        </div>
                        <!--
                        <div class="details-bottom d-flex ">
                            <div class="last-message d-inline-block w-100 text-truncate"><?= $sessions[$i]->last_message ?></div>
                            <div class="message-count badge-pill badge-primary"><?= ($sessions[$i]->attendant_unread_count > 0) ? $sessions[$i]->attendant_unread_count : "" ?></div>
                        </div>
            -->
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>


    <div id="chat-right" class="col-8 ">
        <div id="chat-right-top" class="d-flex bg-light border-bottom p-2 flex-row justify-content-between align-items-center">
            <div class="attendee-profile">
                <img class="attendee-avatar" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=">
            </div>
            <div class="attendee-name w-100"><?= $sessions[0]->attendee_name ?></div>
            <div>
                <a href="" class="">
                    <i class="fas fa-bars fa-lg text-primary"></i>
                </a>
            </div>
        </div>
        <div id="chat-right-messages" class="">
            <div id="chat-message-list" class=" p-3">


            </div>
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