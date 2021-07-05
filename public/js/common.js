$(function () {

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

    jQuery.validator.addMethod("notEqual", function(value, element, param) {
        return this.optional(element) || value !== param;
      }, "Favor preencher o campo!");


});






if (jQuery.fn.dataTableExt) {
    jQuery.extend(jQuery.fn.dataTableExt.oSort, {
        "date-euro-pre": function (a) {
            var x;

            if (a.trim() !== '') {
                var frDatea = a.trim().split(' ');
                var frTimea = (undefined != frDatea[1]) ? frDatea[1].split(':') : [00, 00, 00];
                var frDatea2 = frDatea[0].split('/');
                x = (frDatea2[2] + frDatea2[1] + frDatea2[0] + frTimea[0] + frTimea[1] + ((undefined != frTimea[2]) ? frTimea[2] : 0)) * 1;
            } else {
                x = Infinity;
            }

            return x;
        },

        "date-euro-asc": function (a, b) {
            return a - b;
        },

        "date-euro-desc": function (a, b) {
            return b - a;
        }
    });
}



function debounce(cb, interval, immediate) {
    var timeout;

    return function () {
        var context = this,
            args = arguments;
        var later = function () {
            timeout = null;
            if (!immediate) cb.apply(context, args);
        };

        var callNow = immediate && !timeout;

        clearTimeout(timeout);
        timeout = setTimeout(later, interval);

        if (callNow) cb.apply(context, args);
    };
};

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function addCSRF(data) {
    var tn = window.atob(_csr.tn)
    data[tn] = getCookie(window.atob(_csr.cn))
    return data
}

// resolve tooltip and modal stack problems
$(function () {

    $('[data-toggle="tooltip"]').tooltip()

    $(document).on('show.bs.modal', '.modal', function (event) {
        var zIndex = 1040 + (10 * $('.modal:visible').not(this).length);
        //var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function () {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });

    var MutationObserver = window.WebKitMutationObserver;

    var target = document.querySelector('.observe-me');

    var observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {

            console.log('CSS OBSERVER: -----------------------------');
            console.log('target:', mutation.target);
            console.log('old:', mutation.oldValue);
            console.log('new:', mutation.target.style);
            console.log('-------------------------------------------');
        });
    });

    var config = {
        attributes: true,
        attributeOldValue: true
    }
    if (target) {

        observer.observe(target, config);
    }

})