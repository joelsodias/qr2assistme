<?= $this->extend(($layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('custom_css') ?>
<link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.css" />
<!-- If you use the default popups, use this. -->
<link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.css" />
<link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.css" />

<!-- <link rel="stylesheet" href="/tui-calendar/tui-calendar.css"> -->
<style>
    #renderRange {
        padding-left: 12px;
        font-size: 19px;
        vertical-align: middle;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('custom_scripts') ?>
<script src="https://uicdn.toast.com/tui.code-snippet/v1.5.2/tui-code-snippet.min.js"></script>
<script src="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.min.js"></script>
<script src="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.min.js"></script>
<script src="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.js"></script>
<!-- <script src="/tui-calendar/tui-calendar.js"></script> -->
<script>
    //    import "/tui-calendar/tui-calendar.js";
    //    import "/tui-calendar/tui-calendar.css";
    (function(window, Calendar) {

        var cal = new tui.Calendar('#calendar', {
            defaultView: 'month', // monthly view option
            disableClick: true,
            useDetailPopup: true,
        });

        function onClickNavi(e) {
            var action = getDataAction(e.target);

            switch (action) {
                case 'move-prev':
                    cal.prev();
                    break;
                case 'move-next':
                    cal.next();
                    break;
                case 'move-today':
                    cal.today();
                    break;
                default:
                    return;
            }

            setRenderRangeText();
            //setSchedules();
        }

        function currentCalendarDate(format) {

            var currentDate = moment([cal.getDate().getFullYear(), cal.getDate().getMonth(), cal.getDate().getDate()]);
            currentDate.locale('pt-br');
            return currentDate.format(format);
        }

        function setRenderRangeText() {
            var renderRange = document.getElementById('renderRange');
            var options = cal.getOptions();
            var viewName = cal.getViewName();

            var html = [];
            if (viewName === 'day') {
                html.push(currentCalendarDate('YYYY.MM.DD'));
            } else if (viewName === 'month' &&
                (!options.month.visibleWeeksCount || options.month.visibleWeeksCount > 4)) {
                html.push(currentCalendarDate('MMM / YYYY'));
            } else {
                html.push(moment(cal.getDateRangeStart().getTime()).format('YYYY.MM.DD'));
                html.push(' ~ ');
                html.push(moment(cal.getDateRangeEnd().getTime()).format(' MM.DD'));
            }
            renderRange.innerHTML = html.join('');
        }

        function setEventListener() {
            $('#menu-navi').on('click', onClickNavi);
            //$('.dropdown-menu a[role="menuitem"]').on('click', onClickMenu);
            //$('#lnb-calendars').on('change', onChangeCalendars);

            //$('#btn-save-schedule').on('click', onNewSchedule);
            //$('#btn-new-schedule').on('click', createNewSchedule);

            //$('#dropdownMenu-calendars-list').on('click', onChangeNewScheduleCalendar);

            window.addEventListener('resize', resizeThrottled);
        }

        function getDataAction(target) {
            return target.dataset ? target.dataset.action : target.getAttribute('data-action');
        }

        resizeThrottled = tui.util.throttle(function() {
            cal.render();
        }, 50);

        window.cal = cal;

        setEventListener();
        setRenderRangeText();
    })(window, tui.Calendar);

    $(function() {
        lastClickSchedule = null

        window.cal.on('clickSchedule', function(event) {
            console.log('clickSchedule', event);

            var schedule = event.schedule;

            // focus the schedule
            if (lastClickSchedule) {
                window.cal.updateSchedule(lastClickSchedule.id, lastClickSchedule.calendarId, {
                    isFocused: false
                });
            }
            window.cal.updateSchedule(schedule.id, schedule.calendarId, {
                isFocused: true
            });

            lastClickSchedule = schedule;


        });

        window.cal.createSchedules([{
                id: '1',
                calendarId: '1',
                title: 'my schedule',
                category: 'time',
                dueDateClass: '',
                start: '2021-07-18T22:30:00+09:00',
                end: '2021-07-19T02:30:00+09:00',
                isReadOnly: false
            },
            {
                id: '2',
                calendarId: '1',
                title: 'second schedule',
                category: 'time',
                dueDateClass: '',
                start: '2021-07-17T17:30:00+09:00',
                end: '2021-07-17T17:31:00+09:00',
                isReadOnly: true // schedule is read-only
            }
        ]);

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
<?= $this->openCard("Calendário de agendamentos") ?>



<table class="table table-bordered">
    <thead>
        <tr>
            <th scope="col"><span class="calendar-day">Seg</span></th>
            <th scope="col"><span class="calendar-day">Ter</span></th>
            <th scope="col"><span class="calendar-day">Qua</span></th>
            <th scope="col"><span class="calendar-day">Qui</span></th>
            <th scope="col"><span class="calendar-day">Sex</span></th>
            <th scope="col"><span class="calendar-day">Sáb</span></th>
            <th scope="col"><span class="calendar-day">Dom</span></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td ><span class="calendar-day small float-left">1</span><span class="d-block text-center text-lg">99</span></td>
            <td ><span class="calendar-day small float-left">2</span><span class="d-block text-center text-lg">99</span></td>
            <td ><span class="calendar-day small float-left">3</span><span class="d-block text-center text-lg">99</span></td>
            <td ><span class="calendar-day small float-left">4</span><span class="d-block text-center text-lg">99</span></td>
            <td ><span class="calendar-day small float-left">5</span><span class="d-block text-center text-lg">99</span></td>
            <td ><span class="calendar-day small float-left">6</span><span class="d-block text-center text-lg">99</span></td>
            <td ><span class="calendar-day small float-left">7</span><span class="d-block text-center text-lg">99</span></td>
        </tr>
    </tbody>
</table>




<div id="menu">
    <span id="menu-navi">
        <button type="button" class="btn btn-default btn-sm move-today" data-action="move-today">Hoje</button>
        <button type="button" class="btn btn-default btn-sm move-day" data-action="move-prev">
            <i class="calendar-icon ic-arrow-line-left fas fa-angle-left" data-action="move-prev"></i>
        </button>
        <button type="button" class="btn btn-default btn-sm move-day" data-action="move-next">
            <i class="calendar-icon ic-arrow-line-right fas fa-angle-right" data-action="move-next"></i>
        </button>
    </span>
    <span id="renderRange" class="render-range"></span>
</div>

<div id="calendar"></div>


<?= $this->closeCard() ?>
<?= $this->endSection() ?>