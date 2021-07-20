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

    jQuery.validator.addMethod("notEqual", function(value, element, param) {
        return this.optional(element) || value !== param;
    }, "Favor preencher o campo!");


});






if (jQuery.fn.dataTableExt) {
    jQuery.extend(jQuery.fn.dataTableExt.oSort, {
        "date-euro-pre": function(a) {
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

        "date-euro-asc": function(a, b) {
            return a - b;
        },

        "date-euro-desc": function(a, b) {
            return b - a;
        }
    });


    jQuery.extend(jQuery.fn.dataTableExt.oSort, {
        "time-uni-pre": function(a) {
            var uniTime;

            if (a.toLowerCase().indexOf("am") > -1 || (a.toLowerCase().indexOf("pm") > -1 && Number(a.split(":")[0]) === 12)) {
                uniTime = a.toLowerCase().split("pm")[0].split("am")[0];
                while (uniTime.indexOf(":") > -1) {
                    uniTime = uniTime.replace(":", "");
                }
            } else if (a.toLowerCase().indexOf("pm") > -1 || (a.toLowerCase().indexOf("am") > -1 && Number(a.split(":")[0]) === 12)) {
                uniTime = Number(a.split(":")[0]) + 12;
                var leftTime = a.toLowerCase().split("pm")[0].split("am")[0].split(":");
                for (var i = 1; i < leftTime.length; i++) {
                    uniTime = uniTime + leftTime[i].trim().toString();
                }
            } else {
                uniTime = a.replace(":", "");
                while (uniTime.indexOf(":") > -1) {
                    uniTime = uniTime.replace(":", "");
                }
            }
            return Number(uniTime);
        },

        "time-uni-asc": function(a, b) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },

        "time-uni-desc": function(a, b) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    });

}

function debounce(cb, interval, immediate) {
    var timeout;

    return function() {
        var context = this,
            args = arguments;
        var later = function() {
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
$(function() {

    $('[data-toggle="tooltip"]').tooltip()

    $(document).on('show.bs.modal', '.modal', function(event) {
        var zIndex = 1040 + (10 * $('.modal:visible').not(this).length);
        //var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });

    var MutationObserver = window.WebKitMutationObserver;

    var target = document.querySelector('.observe-me');

    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {

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


// moment.js locale configuration
// locale : brazilian portuguese (pt-br)
// author : Caio Ribeiro Pereira : https://github.com/caio-ribeiro-pereira
if (window.moment) {
    $(function(factory) {
        if (typeof define === 'function' && define.amd) {
            define(['moment'], factory); // AMD
        } else if (typeof exports === 'object') {
            module.exports = factory(require('../moment')); // Node
        } else {
            factory(window.moment); // Browser global
        }
    }(function(moment) {
        return moment.defineLocale('pt-br', {
            months: 'janeiro_fevereiro_março_abril_maio_junho_julho_agosto_setembro_outubro_novembro_dezembro'.split('_'),
            monthsShort: 'Jan_Fev_Mar_Abr_Mai_Jun_Jul_Ago_Set_Out_Nov_Dez'.split('_'),
            weekdays: 'domingo_segunda-feira_terça-feira_quarta-feira_quinta-feira_sexta-feira_sábado'.split('_'),
            weekdaysShort: 'dom_seg_ter_qua_qui_sex_sáb'.split('_'),
            weekdaysMin: 'dom_2ª_3ª_4ª_5ª_6ª_sáb'.split('_'),
            longDateFormat: {
                LT: 'HH:mm',
                L: 'DD/MM/YYYY',
                LL: 'D [de] MMMM [de] YYYY',
                LLL: 'D [de] MMMM [de] YYYY [às] LT',
                LLLL: 'dddd, D [de] MMMM [de] YYYY [às] LT'
            },
            calendar: {
                sameDay: '[Hoje às] LT',
                nextDay: '[Amanhã às] LT',
                nextWeek: 'dddd [às] LT',
                lastDay: '[Ontem às] LT',
                lastWeek: function() {
                    return (this.day() === 0 || this.day() === 6) ?
                        '[Último] dddd [às] LT' : // Saturday + Sunday
                        '[Última] dddd [às] LT'; // Monday - Friday
                },
                sameElse: 'L'
            },
            relativeTime: {
                future: 'em %s',
                past: '%s atrás',
                s: 'segundos',
                m: 'um minuto',
                mm: '%d minutos',
                h: 'uma hora',
                hh: '%d horas',
                d: 'um dia',
                dd: '%d dias',
                M: 'um mês',
                MM: '%d meses',
                y: 'um ano',
                yy: '%d anos'
            },
            ordinal: '%dº'
        });
    }));
}