



function fillCRUDForm(data, action) {
    var form = $("#" + action + "-modal-form");
    var fields = Object.keys(data);

    fields.forEach(function (value, index, array) {
        var elf = form.find("#" + action + "-field-" + value)
        var elfold = form.find("#" + action + "-field-old-" + value)
        if (elf.length) {
            elf.val(data[value])
        }
        if (elfold.length) {
            elfold.val(data[value])
        }

    });
}

var defaultParams = {}

defaultParams.lengthMenu = [
    [10, 25, 50, 100],
    [10, 25, 50, 100]
]
// "lengthMenu": [
//     [10, 25, 50, 100, -1],
//     [10, 25, 50, 100, "All"]
// ],


defaultParams.ajaxlist = function (target,send_data) {

    return {
        "url": target,
        "type": 'GET',
        data: {},
        beforeSend: function (xhr) {
            xhr.setRequestHeader(window.atob(_csr.hn), send_data[window.atob(_csr.hn)]);
            //xhr.setRequestHeader(window.atob(_csr.hn), getCookie(window.atob(_csr.cn)));
        },
        error: function (jqXHR, exception, error) {
            console.log('Error status: ' + jqXHR.status);
            console.log('Exception: ' + exception);
            console.log('Error message: ' + error);
        },
    }
}

defaultParams.setRowButtonsClick = function () {
    
    // $(".row-edit-button").on("click", function(e) {
    //     e.preventDefault();
    //     var data = window.dataTableObject.row(this.dataset.rowNum).data();

    //     fillCRUDForm(data, "update");

    // });

    // TODO: Add delete action
}

defaultParams.buttons = [{
        text: '<i class="fas fa-sync fa-2x" title="Reload"></i>',
        action: function (e, dt, node, config) {
            dt.ajax.reload();
        }
    },
    {
        extend: 'excel',
        text: '<i class="fas fa-file-excel fa-2x" title="Export Page to Excel"></i>',
        exportOptions: {
            modifier: {
                search: 'applied',
                order: 'applied'
            }
        }
    },
    {
        extend: 'csv',
        text: '<i class="fas fa-file-alt fa-2x" title="Export  Page to CSV"></i>',

    },
    {
        extend: 'pdf',
        text: '<i class="fas fa-file-pdf fa-2x" title="Export  Page to PDF"></i>',

    },
    {
        extend: 'copy',
        text: '<i class="fas fa-copy fa-2x" title="Copy Page Data"></i>',

    },
    {
        extend: 'print',
        text: '<i class="fas fa-print fa-2x" title="Print Page"></i>',

    },
    {
        extend: 'colvis',
        text: '<i class="fas fa-columns fa-2x" title="Select Columns"></i>',

    }
]


defaultParams.language =
 {
    "decimal":        ",",
    "emptyTable":     "Nada para exibir",
    "info":           "Mostrando de _START_ até _END_ de _TOTAL_ registros",
    "infoEmpty":      "Exibindo página 0 de 0 de 0 registros",
    "infoFiltered":   "(filtrado do total de _MAX_ registros)",
    "infoPostFix":    "",
    "thousands":      ".",
    "lengthMenu":     "Exibir _MENU_ registros",
    "loadingRecords": "Carregando...",
    "processing":     "Processando...",
    "search":         "Buscar:",
    "zeroRecords":    "Nenhum resultado encontrado",
    "paginate": {
        "first":      "Primeira",
        "last":       "Última",
        "next":       "Próxima",
        "previous":   "Anterior"
    },
    "aria": {
        "sortAscending":  ": Ordenar A > Z",
        "sortDescending": ": Ordenar Z > A"
    }
}


