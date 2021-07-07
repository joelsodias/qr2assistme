<?= $this->extend(($layout ?? $_SERVER["app.layout.folder"] . "/" . $_SERVER["app.layout.template"])) ?>

<?= $this->section('custom_css') ?>
<style>

</style>
<?= $this->endSection() ?>

<?= $this->section('custom_scripts') ?>

<script>
    var dataSet1 = [
        ["DS1 Tiger Nixon", "System Architect", "Edinburgh", "5421", "2011/04/25", "$320,800"],
        ["DS1 Garrett Winters", "Accountant", "Tokyo", "8422", "2011/07/25", "$170,750"],
        ["DS1 Ashton Cox", "Junior Technical Author", "San Francisco", "1562", "2009/01/12", "$86,000"],
        ["DS1 Cedric Kelly", "Senior Javascript Developer", "Edinburgh", "6224", "2012/03/29", "$433,060"],
        ["DS1 Airi Satou", "Accountant", "Tokyo", "5407", "2008/11/28", "$162,700"],
        ["DS1 Brielle Williamson", "Integration Specialist", "New York", "4804", "2012/12/02", "$372,000"],
        ["DS1 Herrod Chandler", "Sales Assistant", "San Francisco", "9608", "2012/08/06", "$137,500"],
        ["DS1 Rhona Davidson", "Integration Specialist", "Tokyo", "6200", "2010/10/14", "$327,900"],
        ["DS1 Colleen Hurst", "Javascript Developer", "San Francisco", "2360", "2009/09/15", "$205,500"],
        ["DS1 Sonya Frost", "Software Engineer", "Edinburgh", "1667", "2008/12/13", "$103,600"],
        ["DS1 Jena Gaines", "Office Manager", "London", "3814", "2008/12/19", "$90,560"],
        ["DS1 Quinn Flynn", "Support Lead", "Edinburgh", "9497", "2013/03/03", "$342,000"],
        ["DS1 Charde Marshall", "Regional Director", "San Francisco", "6741", "2008/10/16", "$470,600"],
        ["DS1 Haley Kennedy", "Senior Marketing Designer", "London", "3597", "2012/12/18", "$313,500"],
        ["DS1 Tatyana Fitzpatrick", "Regional Director", "London", "1965", "2010/03/17", "$385,750"],
        ["DS1 Michael Silva", "Marketing Designer", "London", "1581", "2012/11/27", "$198,500"],
        ["DS1 Paul Byrd", "Chief Financial Officer (CFO)", "New York", "3059", "2010/06/09", "$725,000"],
        ["DS1 Gloria Little", "Systems Administrator", "New York", "1721", "2009/04/10", "$237,500"],
        ["DS1 Bradley Greer", "Software Engineer", "London", "2558", "2012/10/13", "$132,000"],
        ["DS1 Dai Rios", "Personnel Lead", "Edinburgh", "2290", "2012/09/26", "$217,500"],
        ["DS1 Jenette Caldwell", "Development Lead", "New York", "1937", "2011/09/03", "$345,000"],
        ["DS1 Yuri Berry", "Chief Marketing Officer (CMO)", "New York", "6154", "2009/06/25", "$675,000"],
        ["DS1 Caesar Vance", "Pre-Sales Support", "New York", "8330", "2011/12/12", "$106,450"],
        ["DS1 Doris Wilder", "Sales Assistant", "Sydney", "3023", "2010/09/20", "$85,600"],
        ["DS1 Angelica Ramos", "Chief Executive Officer (CEO)", "London", "5797", "2009/10/09", "$1,200,000"],
        ["DS1 Gavin Joyce", "Developer", "Edinburgh", "8822", "2010/12/22", "$92,575"],
        ["DS1 Jennifer Chang", "Regional Director", "Singapore", "9239", "2010/11/14", "$357,650"],
        ["DS1 Brenden Wagner", "Software Engineer", "San Francisco", "1314", "2011/06/07", "$206,850"],
        ["DS1 Fiona Green", "Chief Operating Officer (COO)", "San Francisco", "2947", "2010/03/11", "$850,000"],
        ["DS1 Shou Itou", "Regional Marketing", "Tokyo", "8899", "2011/08/14", "$163,000"],
        ["DS1 Michelle House", "Integration Specialist", "Sydney", "2769", "2011/06/02", "$95,400"],
        ["DS1 Suki Burks", "Developer", "London", "6832", "2009/10/22", "$114,500"],
        ["DS1 Prescott Bartlett", "Technical Author", "London", "3606", "2011/05/07", "$145,000"],
        ["DS1 Gavin Cortez", "Team Leader", "San Francisco", "2860", "2008/10/26", "$235,500"],
        ["DS1 Martena Mccray", "Post-Sales support", "Edinburgh", "8240", "2011/03/09", "$324,050"],
        ["DS1 Unity Butler", "Marketing Designer", "San Francisco", "5384", "2009/12/09", "$85,675"]
    ];

    var dataSet2 = [
        ["DS2 Tiger Nixon", "System Architect", "Edinburgh", "5421", "2011/04/25", "$320,800"],
        ["DS2 Garrett Winters", "Accountant", "Tokyo", "8422", "2011/07/25", "$170,750"],
        ["DS2 Ashton Cox", "Junior Technical Author", "San Francisco", "1562", "2009/01/12", "$86,000"],
        ["DS2 Cedric Kelly", "Senior Javascript Developer", "Edinburgh", "6224", "2012/03/29", "$433,060"],
        ["DS2 Airi Satou", "Accountant", "Tokyo", "5407", "2008/11/28", "$162,700"],
        ["DS2 Brielle Williamson", "Integration Specialist", "New York", "4804", "2012/12/02", "$372,000"],
        ["DS2 Herrod Chandler", "Sales Assistant", "San Francisco", "9608", "2012/08/06", "$137,500"],
        ["DS2 Rhona Davidson", "Integration Specialist", "Tokyo", "6200", "2010/10/14", "$327,900"],
        ["DS2 Colleen Hurst", "Javascript Developer", "San Francisco", "2360", "2009/09/15", "$205,500"],
        ["DS2 Sonya Frost", "Software Engineer", "Edinburgh", "1667", "2008/12/13", "$103,600"],
        ["DS2 Jena Gaines", "Office Manager", "London", "3814", "2008/12/19", "$90,560"],
        ["DS2 Quinn Flynn", "Support Lead", "Edinburgh", "9497", "2013/03/03", "$342,000"],
        ["DS2 Charde Marshall", "Regional Director", "San Francisco", "6741", "2008/10/16", "$470,600"],
        ["DS2 Haley Kennedy", "Senior Marketing Designer", "London", "3597", "2012/12/18", "$313,500"],
        ["DS2 Tatyana Fitzpatrick", "Regional Director", "London", "1965", "2010/03/17", "$385,750"],
        ["DS2 Michael Silva", "Marketing Designer", "London", "1581", "2012/11/27", "$198,500"],
        ["DS2 Paul Byrd", "Chief Financial Officer (CFO)", "New York", "3059", "2010/06/09", "$725,000"],
        ["DS2 Gloria Little", "Systems Administrator", "New York", "1721", "2009/04/10", "$237,500"],
        ["DS2 Bradley Greer", "Software Engineer", "London", "2558", "2012/10/13", "$132,000"],
        ["DS2 Dai Rios", "Personnel Lead", "Edinburgh", "2290", "2012/09/26", "$217,500"],
        ["DS2 Jenette Caldwell", "Development Lead", "New York", "1937", "2011/09/03", "$345,000"],
        ["DS2 Yuri Berry", "Chief Marketing Officer (CMO)", "New York", "6154", "2009/06/25", "$675,000"],
        ["DS2 Caesar Vance", "Pre-Sales Support", "New York", "8330", "2011/12/12", "$106,450"],
        ["DS2 Doris Wilder", "Sales Assistant", "Sydney", "3023", "2010/09/20", "$85,600"],
        ["DS2 Angelica Ramos", "Chief Executive Officer (CEO)", "London", "5797", "2009/10/09", "$1,200,000"],
        ["DS2 Gavin Joyce", "Developer", "Edinburgh", "8822", "2010/12/22", "$92,575"],
        ["DS2 Jennifer Chang", "Regional Director", "Singapore", "9239", "2010/11/14", "$357,650"],
        ["DS2 Brenden Wagner", "Software Engineer", "San Francisco", "1314", "2011/06/07", "$206,850"],
        ["DS2 Fiona Green", "Chief Operating Officer (COO)", "San Francisco", "2947", "2010/03/11", "$850,000"],
        ["DS2 Shou Itou", "Regional Marketing", "Tokyo", "8899", "2011/08/14", "$163,000"],
        ["DS2 Michelle House", "Integration Specialist", "Sydney", "2769", "2011/06/02", "$95,400"],
        ["DS2 Suki Burks", "Developer", "London", "6832", "2009/10/22", "$114,500"],
        ["DS2 Prescott Bartlett", "Technical Author", "London", "3606", "2011/05/07", "$145,000"],
        ["DS2 Gavin Cortez", "Team Leader", "San Francisco", "2860", "2008/10/26", "$235,500"],
        ["DS2 Martena Mccray", "Post-Sales support", "Edinburgh", "8240", "2011/03/09", "$324,050"],
        ["DS2 Unity Butler", "Marketing Designer", "San Francisco", "5384", "2009/12/09", "$85,675"]
    ];


    $(function() {
        var dt = 
        $("#example").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "data": dataSet1,
            "columns": [{
                    title: "Name"
                },
                {
                    title: "Position"
                },
                {
                    title: "Office"
                },
                {
                    title: "Extn."
                },
                {
                    title: "Start date"
                },
                {
                    title: "Salary"
                }
            ]
        }).buttons().container().appendTo('#examples_wrapper .col-md-6:eq(0)');

        $("#changedata").on("click",function (e){

            var dt = $("#example").DataTable()
           
            dt.destroy()

            dt = 
            $("#example").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "data": dataSet2,
            "columns": [{
                    title: "Name"
                },
                {
                    title: "Position"
                },
                {
                    title: "Office"
                },
                {
                    title: "Extn."
                },
                {
                    title: "Start date"
                },
                {
                    title: "Salary"
                }
            ]
        }).buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
        })

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
<?= $this->openCard("Card Title") ?>


<div class="card-body">
    <button id="changedata" class="btn btn-primary">change</button>

    <table id="example" class="table table-bordered table-striped" width="100%"></table>

    <table id="example1" class="table table-bordered table-striped"></table>
</div>


<?= $this->closeCard() ?>
<?= $this->endSection() ?>