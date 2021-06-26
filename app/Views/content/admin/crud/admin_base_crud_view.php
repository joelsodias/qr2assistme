<?php

function dispath_form_method($controller, $form_method, $data)
{
    $class = null;
    $method = null;

    if (strpos($form_method, "::") > 0) {
        [
            $class,
            $method,
        ] = explode('::', $form_method);

        if (class_exists($class)) {
            if ($class == get_class($controller)) {
                if (method_exists($controller, $method)) {
                    if (is_callable(array($controller, $method))) {
                        $result = $controller->$method($data);
                    }
                }
            }
        }
    } else {
        if (method_exists($controller, $form_method)) {
            if (is_callable(array($controller, $form_method))) {
                $result = $controller->$form_method($data);
            }
        } elseif (is_callable($form_method)) {
            $result = $form_method($data);
        }
    }
}

?>

<?= $this->extend(($layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('custom_css') ?>
<style>

</style>
<?= $this->endSection() ?>

<?= $this->section('custom_scripts') ?>
<script src="/js/crud.js"></script>
<script>
    <?php
    if (isset($dataset_array) && count($dataset_array) > 0) {
        echo "var staticDataSet = [ \n";

        foreach ($dataset_array as $line) {
            echo "[";

            foreach ($line as $value) {
                echo "'$value',";
            }
            echo "],";
        }
        echo "];\n";
    }

    if (isset($dataset_visible_fields)) {

        $c = 0;

        echo "var fieldlabels = [\n";

        foreach ($dataset_visible_fields as $key => $value) {
            echo " { field : '$key', label : '$value' } ,\n";
            $c++;
        }

        echo "];\n";
    }


    ?>






    function createDataTable() {

        var send_data = addCSRF([])

        window.dataTableObject =

            $("#dataTable").DataTable({
                "serverSide": true,
                "processing": true,
                "paging": true,
                "ajax": defaultParams.ajaxlist(window.atob('<?= base64_encode(isset($list_url) ? (($list_url) ? base_url($list_url) : "#NotImplemented") : "") ?>'),send_data),
                "lengthChange": true,
                "lengthMenu": defaultParams.lengthMenu,
                "pageLength": 10,
                "responsive": true,
                "searchDelay": 350,
                "buttons": defaultParams.buttons,

                <?php

                if (isset($dataset_array)) {
                    echo "data: staticDataSet, ";
                }

                if (isset($dataset_visible_fields)) {
                    echo "columns:[\n";
                    $count = 0;
                    if (($allow_delete ?? false) || ($allow_update ?? false)) {
                        echo '                     { data : "' . $dataset_key_fieldname . '",  title: "Actions" },', "\n"; //, "defaultContent": "<button onclick=\'edititem();\'>Edit</button>" },';
                    }
                    foreach ($dataset_visible_fields as $key => $value) {
                        if ($count) {
                            echo "                     { data: '$key' },\n";
                        } else {
                            echo "                     { data: '$key' },\n";
                        }
                    }
                    echo "                ],\n";
                }
                ?> "autoWidth": true,



                initComplete: function() {
                    // Apply the search

                    window.dataTableObject.buttons().container()
                        .appendTo($('.col-md-6:eq(0)', window.dataTableObject.table().container()));

                    <?php if ($allow_insert ?? false) : ?>

                        $('#dataTable_wrapper > .row > .col-md-6:eq(1)').append(
                            $('<a href="#" class="btn btn-success mr-3" data-toggle="modal" data-target="#insert-modal"><span class="fa fa-plus mr-2"></span><?= $insert_button_label ?></a>')

                        );

                    <?php endif; ?>

                    defaultParams.setRowButtonsClick()
                    window.dataTableObject.on("draw", defaultParams.setRowButtonsClick)
                    window.dataTableObject.on("page", defaultParams.setRowButtonsClick)


                    $('#dataTable_wrapper > .row > .col-md-6:eq(1)').append(
                        $('<a href="#" class="btn btn-primary mr-3"><span class="fas fa-undo mr-2"></span><?= $reload_button_label ?></a>')
                        .on("click", function(e) {
                            window.dataTableObject.ajax.reload(defaultParams.setRowButtonsClick);
                        })
                    );

                    this.api().columns().every(function() {
                        var that = this;

                        $('.input-search-header', this.header()).on('click', function(e) {
                            e.preventDefault();
                            e.stopImmediatePropagation();
                        });

                        $('.input-search-header', this.header()).on('keyup change clear',
                            debounce(
                                function(e) {
                                    e.preventDefault();
                                    e.stopImmediatePropagation();
                                    console.log("fired");

                                    if (that.search() !== this.value) {
                                        that
                                            .search(this.value)
                                            .draw();
                                    }
                                }, 400)
                        );
                    });
                },
                <?php if (($allow_delete ?? false) || ($allow_update ?? false)) : ?>

                    "columnDefs": [{
                            // The `data` parameter refers to the data for the cell defined by the
                            // `data` option, which defaults to the column being worked with, in
                            // this case `data: 0`.
                            "render": function(data, type, row) {
                                //console.log(window.dataTableObject.data().indexOf(row));
                                //console.log(window.dataTableObject.data().indexOf(row));
                                return ""
                                <?php if ($allow_delete ?? false) : ?>
                                        +
                                        '<a href="#" class="row-delete-button btn btn-outline-danger ml-2" data-toggle="modal" data-target="#delete-modal" data-row-num="' + window.dataTableObject.data().indexOf(row) + '" data-id="' + row.<?= $dataset_key_fieldname ?> + '"><span class="fas fa-trash"></span><span class="<?= ($row_label_buttons ?? false) ? 'ml-3">' . $delete_button_label . '</span>' : '"></span>' ?></a>'
                                <?php endif; ?>
                                <?php if ($allow_update ?? false) : ?>
                                        +
                                        '<a href="#" class="row-edit-button btn btn-outline-primary ml-2" data-toggle="modal" data-target="#update-modal" data-row-num="' + window.dataTableObject.data().indexOf(row) + '" data-id="' + row.<?= $dataset_key_fieldname ?> + '"><span class="fas fa-pen"></span><span class="<?= ($row_label_buttons ?? false) ? 'ml-3">' . $edit_button_label . '</span>' : '"></span>' ?></a>'
                                <?php endif; ?>
                            },
                            "targets": 0,
                        },

                    ],
                <?php endif; ?>

            });
    }



    $(function() {

        // Example starter JavaScript for disabling form submissions if there are invalid fields
        // Source: https://mdbootstrap.com/docs/standard/forms/validation/
        (() => {
            'use strict';

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('form');

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms).forEach((form) => {
                form.addEventListener('submit', (event) => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Setup - add a text input to each footer cell
        $('#dataTable thead th').each(function() {
            var title = $(this).text();
            $(this).html('<div>' + title + '</div><input class="input-search-header" type="text" placeholder="Pesquisar ' + title + '" />');
        });



        // DATATABLE CREATION
        createDataTable();


        <?php if ($allow_insert ?? false) : ?>
            //Save record
            $('#insert-modal-button-submit').on('click', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                send_data = addCSRF($("#insert-modal-form").serializeArray())

                $.ajax({
                    type: "POST",
                    url: window.atob('<?= base64_encode(isset($insert_url) ? (($insert_url) ? base_url($insert_url) : "#NotImplemented") : "") ?>'),
                    dataType: "JSON",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader(window.atob(_csr.hn), getCookie(window.atob(_csr.cn)));
                    },
                    //beforeSend: function(xhr){xhr.setRequestHeader('<?= $this->security->getHeaderName() ?>', window.atob(_csr.h));},
                    data: send_data,
                    success: function(data) {
                        if (data) {

                        }
                        $('#insert-modal').modal('hide');
                        window.dataTableObject.ajax.reload(defaultParams.setRowButtonsClick, false);
                    },
                    error: function(jqXHR, exception, error) {}
                });
                return false;
            });
        <?php endif; ?>

        <?php if ($allow_update ?? false) : ?>
            //update record to database
            $('#update-modal-button-submit').on('click', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                send_data = addCSRF($("#update-modal-form").serializeArray())

                $.ajax({
                    type: "POST",
                    url: window.atob('<?= base64_encode(isset($update_url) ? (($update_url) ? base_url($update_url) : "#NotImplemented") : "") ?>'),
                    dataType: "JSON",
                    data: send_data,
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader(window.atob(_csr.hn), getCookie(window.atob(_csr.cn)));
                    },
                    success: function(data) {
                       
                        if (data.length) {

                        }
                        $('#update-modal').modal('hide');
                        window.dataTableObject.ajax.reload(defaultParams.setRowButtonsClick, false);
                    },
                    error: function(jqXHR, exception, error) {}


                });
                return false;
            });
        <?php endif; ?>

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

<?= $this->openCard($content_header_subtitle ?? "") ?>

<table id="dataTable" class="table table-striped" id="mydata">
    <thead>
        <tr>
            <?php if (($allow_delete ?? false) || ($allow_update ?? false)) : ?>
                <th style="text-align: right;">Actions</th>
            <?php endif; ?>
            <?php

            if (isset($dataset_visible_fields)) {

                foreach ($dataset_visible_fields as $key => $value) {
                    echo " <th>$value</th>\n";
                }
            }

            ?>
        </tr>
    </thead>
    <tbody id="show_data">

    </tbody>
</table>
<!-- <?php if ($custom_html_method ?? false) : ?> -->
<!-- Custom HTML defined by custom_html_method -->
<!-- <?= "" //dispath_form_method($controller, $custom_html_method, null) 
        ?> -->
<?= $this->getCustomHTML() ?>
<!-- <?php endif; ?> -->
<?php if ($allow_insert ?? false) : ?>
    <?= $this->openModal("insert", $insert_modal_label ?? "New") ?>

    <?php if ($insert_form_method ?? false) : ?>
        <!-- Custom form defined by insert_form_method -->
        <!-- <?= "" //dispath_form_method($controller, $insert_form_method, "insert") 
                ?> -->
        <?= $this->getEditForm("insert") ?>
    <?php else : ?>
        NO INSERT FORM METHOD DEFINED
    <?php endif; ?>

    <?= $this->closeModal(
        "insert",
        [
            "close" =>  ["label" => $close_button_label, "tag" => "button", "type" => "button", "class" => "btn-secondary", "data-dismiss" => "modal"],
            "submit" => ["label" => $save_button_label, "class" => "btn-success", "type" => "submit", "data-dismiss" => ""]
        ]
    )
    ?>
<?php endif; ?>

<?php if ($allow_update ?? false) : ?>
    <?= $this->openModal("update", $update_modal_label ?? "Edit") ?>

    <?php if ($update_form_method ?? false) : ?>
        <!-- Custom form defined by update_form_method -->
        <?= "" //dispath_form_method($controller, $update_form_method, "update") 
        ?>
        <?= $this->getEditForm("update") ?>
    <?php else : ?>
        NO UPDATE FORM METHOD DEFINED
    <?php endif; ?>

    <?= $this->closeModal(
        "update",
        [
            "close" =>  ["label" => $close_button_label, "tag" => "button", "type" => "button", "class" => "btn-secondary", "data-dismiss" => "modal"],
            "submit" => ["label" => $save_button_label, "class" => "btn-primary", "type" => "submit", "data-dismiss" => ""]
        ]
    )
    ?>
<?php endif; ?>

<?php if ($allow_delete ?? false) : ?>
    <!--MODAL DELETE-->
    <?= $this->openModal("delete", $insert_modal_label ?? "Delete") ?>

    <?php if ($update_form_method ?? false) : ?>
        <!-- Custom form defined by update_form_method -->
        <!-- <?= "" //dispath_form_method($controller, $update_form_method, "delete") 
                ?> -->
        <?= $this->getEditForm("delete") ?>
    <?php else : ?>
        <strong><?= $delete_confirmation_message ?? "Are you sure to delete this record?" ?></strong>
    <?php endif; ?>

    <?= $this->closeModal(
        "update",
        [
            "close" =>  ["label" => $close_button_label, "tag" => "button", "type" => "button", "class" => "btn-secondary", "data-dismiss" => "modal"],
            "submit" => ["label" => $delete_button_label, "class" => "btn-danger", "type" => "submit", "data-dismiss" => ""]
        ]
    )
    ?>
    <!--END MODAL DELETE-->
<?php endif; ?>




<?= $this->closeCard() ?>

<?= $this->endSection() ?>