<?php


/* @var $this yii\web\View */

use aryelds\sweetalert\SweetAlertAsset;
use dosamigos\chartjs\ChartJsAsset;
use kartik\date\DatePicker;
use yii\helpers\Url;

$this->title = 'Dashboard';
?>
<?= \yii\helpers\Html::csrfMetaTags() ?>
<div class="site-index panel panel-default">
    <div class="body-content container-fluid">

        <div class="row gap-0">

            <?php
            // if (Yii::$app->user->can('super-user')) {
            //     echo "  <div class='col-sm-1' style='padding-left:0'><button class='btn btn-success' id='update_payee'>Update Cloud</button></div>";
            //     echo "  <div class='col-sm-1' style='padding-left:0'><button class='btn btn-warning' id='update_lan'>Update LAN</button></div>";
            // }
            // echo "  <div class='col-sm-1'><button class='btn btn-success' id='update_cloud' style='margin-bottom:12px'>Update Cloud</button> </div>";
            ?>

        </div>
        <div class="row">
            <div class="col-sm-5">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="fa fa-calendar"></i> Calendar of Events </div>
                    <div class="panel-body">
                        <div style="height:350;width:100%" id="calendar"></div>
                    </div>
                </div>
            </div>
            <?php if (YIi::$app->user->can('super-user')) { ?>
                <div class="col-sm-7">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Transmittal</div>
                        <div class="panel-body">
                            <label for="bar_filter">Year</label>
                            <?php
                            echo DatePicker::widget([
                                'id' => 'bar_filter',
                                'name' => 'year',
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy',
                                    'minViewMode' => 'years',
                                    'placeholder' => 'Select Year'

                                ]
                            ]);
                            ?>
                            <div id="chartContainer">
                                <canvas id="myChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>



    </div>

</div>

<div id="dots5">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
</div>
<style>
    canvas {

        height: 343px !important;

    }

    .panel {
        background-color: white;
        box-shadow: 20px;
        margin: 5px;
        border-radius: 10px;
    }

    #dots5 {
        display: none;
    }

    td {
        padding: 12px;
    }

    .fc-day-number {
        font-size: 12px;
    }

    .fc-center h2 {
        font-size: 20px;
    }

    .fc-button-group {
        font-size: 12px;
    }

    .fc .fc-toolbar-title {
        font-size: 1.5em;
    }

    .btn {
        position: relative;
        display: block;
        font-size: 10px;
    }
</style>
<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/dataSync.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/updateCloud.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile("@web/js/fullcalendar/main.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile("@web/js/fullcalendar/main.min.css");
SweetAlertAsset::register($this);
ChartJsAsset::register($this);
$csrfToken = Yii::$app->request->csrfToken;
$csrfName = Yii::$app->request->csrfParam;

?>

<script>
    let x = undefined;
    $('#update_lan').click((e) => {

        try {

            e.preventDefault()
            $('.site-index').hide();
            $('#dots5').show()
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=sync-database/update-lan',
                data: {
                    data: ''
                },
                success: function(data) {
                    $('.site-index').show();
                    $('#dots5').hide()
                }
            })
        } catch (e) {
            console.log(e.message)
            swal({
                title: 'Error',
                text: e.message,
                type: 'error',
                button: false,

            })
        }
    })


    $('#update_cloud').click(function(e) {
        e.preventDefault();
        $('.site-index').hide();
        $('#dots5').show()

        $.post(window.location.pathname + '?r=site/token', {
            data: ''
        }, function(data) {
            localStorage.setItem('token', JSON.parse(data).token)
        })
        let baseUrl = window.location.pathname

        const res = []
        const chartOfAccountApi = new Promise((resolve, reject) => {
            // CHART OF ACCOUNTS API
            $.post(window.location.pathname + '?r=sync-database/chart-of-account', // url
                {
                    myData: ''
                }, // data to be submit
                function(data) { // success callback
                    var d = JSON.parse(data)


                    $.ajax({
                        type: "post",
                        url: 'https://fisdticaraga.com/index.php?r=chart-of-accounts-api/create',
                        contentType: "application/json",
                        data: JSON.stringify(d),
                        dataType: 'json',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`
                        },
                        success: function(newdata) {
                            resolve('chartOfAccountApi ' + newdata)
                        }
                    })
                })
        })
        const subAccount1Api = new Promise((resolve, reject) => {
            // SUB ACCOUNTS 1 API
            $.post(window.location.pathname + '?r=sync-database/sub-account1', // url
                {
                    myData: ''
                }, // data to be submit
                function(data) { // success callback
                    var d = JSON.parse(data)


                    $.ajax({
                        type: "post",
                        url: 'https://fisdticaraga.com/index.php?r=sub-accounts1-api/create',
                        contentType: "application/json",
                        data: JSON.stringify(d),
                        dataType: 'json',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`
                        },
                        success: function(newdata) {
                            resolve('subAccount1Api' + newdata)
                        }
                    })
                })
        })
        const subAccount2Api = new Promise((resolve, reject) => {
            // SUB ACCOUNTS 2 API
            $.post(window.location.pathname + '?r=sync-database/sub-account2', // url
                {
                    myData: ''
                }, // data to be submit
                function(data) { // success callback
                    var d = JSON.parse(data)


                    $.ajax({
                        type: "post",
                        url: 'https://fisdticaraga.com/index.php?r=sub-accounts2-api/create',
                        contentType: "application/json",
                        data: JSON.stringify(d),
                        dataType: 'json',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`
                        },
                        success: function(newdata) {
                            resolve('subAccount2Api' + newdata)
                        }
                    })
                })
        })
        const payeeApi = new Promise((resolve, reject) => {
            $.post(window.location.pathname + '?r=sync-database/payee', // url
                {
                    myData: ''
                }, // data to be submit
                function(data) { // success callback
                    var d = JSON.parse(data)


                    $.ajax({
                        type: "post",
                        url: 'https://fisdticaraga.com/index.php?r=payee-api/create',
                        contentType: "application/json",
                        data: JSON.stringify(d),
                        dataType: 'json',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`
                        },
                        success: function(newdata) {

                            res[0] = {
                                payee: newdata
                            }
                            resolve(newdata)
                        }
                    })
                })
        })
        const transactionApi = new Promise((resolve, reject) => {
            $.post(window.location.pathname + '?r=sync-database/transaction', // url
                {
                    myData: ''
                }, // data to be submit
                function(data) { // success callback
                    var d = JSON.parse(data)
                    $.ajax({
                        type: "post",
                        url: 'https://fisdticaraga.com/index.php?r=transaction-api/create',
                        contentType: "application/json",
                        data: JSON.stringify(d),
                        dataType: 'json',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`
                        },
                        success: function(newdata) {
                            resolve(newdata)
                            console.log('newdata')

                        }
                    })
                })
        });
        transactionApi.then(() => {
            $.post(window.location.pathname + '?r=sync-database/process-ors', // url
                {
                    myData: '',
                }, // data to be submit
                function(data) { // success callback
                    var d = JSON.parse(data)
                    console.log(d)
                    $.ajax({
                        type: "post",
                        url: 'https://fisdticaraga.com/index.php?r=process-ors-api/create',
                        contentType: "application/json",
                        data: JSON.stringify(d),
                        dataType: 'json',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`
                        },
                        success: function(newdata) {
                            console.log(newdata)
                        }
                    })
                })
        })
        const recordAllotmentApi = new Promise((resolve, reject) => {
            // RECORD ALLOTMENT API
            $.post(window.location.pathname + '?r=sync-database/record-allotment', // url
                {
                    myData: ''
                }, // data to be submit
                function(data) { // success callback
                    var d = JSON.parse(data)
                    $.ajax({
                        type: "post",
                        url: 'https://fisdticaraga.com/index.php?r=record-allotment-api/create',
                        contentType: "application/json",
                        data: JSON.stringify(d),
                        dataType: 'json',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`
                        },
                        success: function(newdata) {
                            resolve(newdata)
                        }
                    })
                })
        })
        const trackingSheetApi = new Promise((resolve, reject) => {

            transactionApi.then(() => {


                $.post(window.location.pathname + '?r=sync-database/tracking-sheet', // url
                    {
                        myData: ''
                    }, // data to be submit
                    function(data) { // success callback
                        var d = JSON.parse(data)
                        $.ajax({
                            type: "post",
                            url: 'https://fisdticaraga.com/index.php?r=tracking-sheet-api/create',
                            contentType: "application/json",
                            data: JSON.stringify(d),
                            dataType: 'json',
                            headers: {
                                "Authorization": `Bearer ${localStorage.getItem('token')}`
                            },
                            success: function(newdata) {
                                resolve(newdata)
                            }
                        })
                    })
            })
        })


        const dvAucsApi = new Promise((resolve, reject) => {
            $.post(window.location.pathname + '?r=sync-database/dv-aucs', // url
                {
                    myData: ''
                }, // data to be submit
                function(data) { // success callback
                    var d = JSON.parse(data)
                    try {


                        $.ajax({
                            type: "post",
                            url: 'https://fisdticaraga.com/index.php?r=dv-aucs-api/create',
                            contentType: "application/json",
                            data: JSON.stringify(d),
                            dataType: 'json',
                            headers: {
                                "Authorization": `Bearer ${localStorage.getItem('token')}`
                            },
                            success: function(newdata) {
                                resolve(newdata)
                                // DV AUCS ENTRIES
                                $.post(window.location.pathname + '?r=sync-database/dv-aucs-entries', // url
                                    {
                                        myData: '',
                                        '<?= $csrfName ?>': "<?= $csrfToken ?>"
                                    }, // data to be submit
                                    function(data) { // success callback
                                        var d = JSON.parse(data)
                                        $.ajax({
                                            type: "post",
                                            url: 'https://fisdticaraga.com/index.php?r=dv-aucs-entries-api/create',
                                            contentType: "application/json",
                                            data: JSON.stringify(d),
                                            dataType: 'json',
                                            headers: {
                                                "Authorization": `Bearer ${localStorage.getItem('token')}`
                                            },
                                            success: function(newdata) {
                                                resolve(newdata)
                                            }
                                        })
                                    })
                                // DV ACCOUNTING ENTRIES
                                $.post(window.location.pathname + '?r=sync-database/dv-accounting-entries', // url
                                    {
                                        myData: ''
                                    }, // data to be submit
                                    function(data) { // success callback
                                        var d = JSON.parse(data)
                                        $.ajax({
                                            type: "post",
                                            url: 'https://fisdticaraga.com/index.php?r=dv-accounting-entries-api/create',
                                            contentType: "application/json",
                                            data: JSON.stringify(d),
                                            dataType: 'json',
                                            headers: {
                                                "Authorization": `Bearer ${localStorage.getItem('token')}`
                                            },
                                            success: function(newdata) {
                                                resolve(newdata)
                                            }
                                        })
                                    })
                            }
                        })
                    } catch (e) {
                        console.log(e.message)
                    }
                })
        })
        const cashRecieveApi = new Promise((resolve, reject) => {
            $.post(window.location.pathname + '?r=sync-database/cash-recieve', // url
                {
                    myData: ''
                }, // data to be submit
                function(data) { // success callback
                    var d = JSON.parse(data)

                    $.ajax({
                        type: "post",
                        url: 'https://fisdticaraga.com/index.php?r=cash-recieved-api/create',
                        contentType: "application/json",
                        data: JSON.stringify(d),
                        dataType: 'json',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`
                        },
                        success: function(newdata) {
                            resolve(newdata)
                        }
                    })
                })
        })
        const cashDisbursementApi = new Promise((resolve, reject) => {

            dvAucsApi.then(() => {
                // RECORD ALLOTMENT API
                $.post(window.location.pathname + '?r=sync-database/cash-disbursement', // url
                    {
                        myData: ''
                    }, // data to be submit
                    function(data) { // success callback
                        var d = JSON.parse(data)
                        $.ajax({
                            type: "post",
                            url: 'https://fisdticaraga.com/index.php?r=cash-disbursement-api/create',
                            contentType: "application/json",
                            data: JSON.stringify(d),
                            dataType: 'json',
                            headers: {
                                "Authorization": `Bearer ${localStorage.getItem('token')}`
                            },
                            success: function(newdata) {
                                resolve(newdata)
                            }
                        })
                    })
            })
        })
        const advancesApi = new Promise((resolve, reject) => {
            cashDisbursementApi.then(() => {

                $.post(window.location.pathname + '?r=sync-database/advances', // url
                    {
                        myData: ''
                    }, // data to be submit
                    function(data) { // success callback
                        var d = JSON.parse(data)
                        $.ajax({
                            type: "post",
                            url: 'https://fisdticaraga.com/index.php?r=advances-api/create',
                            contentType: "application/json",
                            data: JSON.stringify(d),
                            dataType: 'json',
                            headers: {
                                "Authorization": `Bearer ${localStorage.getItem('token')}`
                            },
                            success: function(newdata) {
                                resolve(newdata)
                            }
                        })
                    })
            })
        })
        const advancesEntries = new Promise((resolve, reject) => {
            advancesApi.then(() => {
                $.post(window.location.pathname + '?r=sync-database/advances-entries', // url
                    {
                        myData: ''
                    }, // data to be submit
                    function(data) { // success callback
                        var d = JSON.parse(data)

                        $.ajax({
                            type: "post",
                            url: 'https://fisdticaraga.com/index.php?r=advances-entries-api/create',
                            contentType: "application/json",
                            data: JSON.stringify(d),
                            dataType: 'json',
                            headers: {
                                "Authorization": `Bearer ${localStorage.getItem('token')}`
                            },
                            success: function(newdata) {
                                resolve(newdata)
                            }
                        })
                    })
            })
        })
        const jevPreparationApi = new Promise((resolve, reject) => {
            $.post(window.location.pathname + '?r=sync-database/jev-preparation', // url
                {
                    myData: ''
                }, // data to be submit
                function(data) { // success callback
                    var d = JSON.parse(data)

                    $.ajax({
                        type: "post",
                        url: 'https://fisdticaraga.com/index.php?r=jev-preparation-api/create',
                        contentType: "application/json",
                        data: JSON.stringify(d),
                        dataType: 'json',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`
                        },
                        success: function(newdata) {
                            resolve(newdata)
                        }
                    })
                })
        })
        const jevAccountingEntriesApi = new Promise((resolve, reject) => {
            jevPreparationApi.then(() => {
                // RECORD ALLOTMENT API
                $.post(window.location.pathname + '?r=sync-database/jev-accounting-entries', // url
                    {
                        myData: ''
                    }, // data to be submit
                    function(data) { // success callback
                        var d = JSON.parse(data)
                        $.ajax({
                            type: "post",
                            url: 'https://fisdticaraga.com/index.php?r=jev-accounting-entries-api/create',
                            contentType: "application/json",
                            data: JSON.stringify(d),
                            dataType: 'json',
                            headers: {
                                "Authorization": `Bearer ${localStorage.getItem('token')}`
                            },
                            success: function(newdata) {
                                resolve(newdata)
                            }
                        })
                    })
            })
        })
        const fundSourceTypeApi = new Promise((resolve, reject) => {
            $.post(window.location.pathname + '?r=sync-database/fund-source-type', // url
                {
                    myData: ''
                }, // data to be submit
                function(data) { // success callback
                    var d = JSON.parse(data)
                    $.ajax({
                        type: "post",
                        url: 'https://fisdticaraga.com/index.php?r=fund-source-type-api/create',
                        contentType: "application/json",
                        data: JSON.stringify(d),
                        dataType: 'json',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`
                        },
                        success: function(newdata) {
                            resolve(newdata)
                            console.log('newdata')

                        }
                    })
                })
        });

        // const processOrsApi = new Promise((resolve, reject) => {
        //     // PROCESS ORS  API
        //     $.post(window.location.pathname + '?r=sync-database/process-ors', // url
        //         {
        //             myData: ''
        //         }, // data to be submit
        //         function(data) { // success callback
        //             var d = JSON.parse(data)
        //             $.ajax({
        //                 type: "post",
        //                 url: 'https://fisdticaraga.com/index.php?r=process-ors-api/create',
        //                 contentType: "application/json",
        //                 data: JSON.stringify(d),
        //                 dataType: 'json',
        //                 headers: {
        //                     "Authorization": `Bearer ${localStorage.getItem('token')}`
        //                 },
        //                 success: function(newdata) {
        //                     resolve(newdata)
        //                 }
        //             })
        //         })
        // })
        // const processOrsApi = new Promise((resolve, reject) => {F
        // PROCESS ORS  API

        // })
        // processOrsApi.then((data) => {
        //     console.log(data)
        // })
        // At this point, "promiseA" is already settled.

        Promise.all([
            transactionApi,
            payeeApi,
            chartOfAccountApi,
            subAccount1Api,
            subAccount2Api,
            recordAllotmentApi,
            dvAucsApi,
            cashDisbursementApi,
            trackingSheetApi,
            advancesApi,
            advancesEntries,
            cashRecieveApi,
            jevPreparationApi,
            jevAccountingEntriesApi,
            fundSourceTypeApi

        ]).then(values => {
            $('.site-index').show();
            $('#dots5').hide()

            // console.log(values)
            // console.log("We waited until ajax ended: " + values);
            // console.log("My few ajax ended, lets do some things!!")
        }, reason => {
            console.log("Promises failed: " + reason);
        });






        // PAYEE
        // $.post(window.location.pathname + '?r=sync-database/payee', // url
        //     {
        //         myData: ''
        //     }, // data to be submit
        //     function(data) { // success callback
        //         var d = JSON.parse(data)


        //         $.ajax({
        //             type: "post",
        //             url: 'https://fisdticaraga.com/index.php?r=payee-api/create',
        //             contentType: "application/json",
        //             data: JSON.stringify(d),
        //             dataType: 'json',
        //             headers: {
        //                 "Authorization": `Bearer ${localStorage.getItem('token')}`
        //             },
        //             success: function(newdata) {
        //                 console.log(newdata)
        //             }
        //         })
        //     })

        // TRANSACTION API






    })
    $(document).ready(function() {
        // updateCloud()

        $.getJSON(window.location.pathname + '?r=site/q').then(function(data) {
            cal(data)
        })
        console.log('qweqweq')
    })

    async function updateCloud() {
        try {
            const CloudBooks = await updateCloudBooks()
        } catch (err) {
            console.log(err)
        }
    }

    function cal(data) {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            themeSystem: 'bootstrap',
            height: 400,

            headerToolbar: {
                left: 'prev,next,today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: data,
            eventClick: function(info) {

                $('#genericModal').modal('show').find('#modalContent').load(window.location.pathname + '?r=event/update&id=' + info.event.id)
            }
        });
        calendar.render();

    }

    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        ev.dataTransfer.setData("text", ev.target.id);
    }

    function drop(ev) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        ev.target.appendChild(document.getElementById(data));
    }
</script>
<?php

$url = Url::toRoute(['report/detailed-transmittal-summary', 'reporting_period' => '']);
$script = <<<JS
     const csrfToken = $('meta[name="csrf-token"]').attr("content");
    async function BarChart(year =''){
        const data = await getData(year)
     
        document.getElementById("chartContainer").innerHTML = '&nbsp;';
        document.getElementById("chartContainer").innerHTML = '<canvas id="myChart"></canvas>';
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.reporting_period,
                datasets: [{
                    label: '# of DV`s',
                    data: data.total_dv,
                    backgroundColor: 
                    'rgb(0, 82, 204)',
                      
                    borderColor: 
                    'rgb(0, 82, 204)',
                    borderWidth: 1
                },{
                    label: '# of Dv AT RO',
                    data: data.dv_at_ro,
                    backgroundColor: 
                    'rgb(179, 0, 0)',
                    borderColor: 
                    'rgb(179, 0, 0)',
                    borderWidth: 1
                },{
                    label: '# of Dv AT COA',
                    data: data.dv_at_coa,
                    backgroundColor: 
                    'rgb(0, 128, 43)',
              
                    borderWidth: 1
                }
            ]
            },
                options: {
                    // This chart will not respond to mousemove, etc
                    onClick(e) {
                        const activePoints = myChart.getElementsAtEvent(e)[0];
                 
                       if (activePoints !=undefined){
                        console.log(myChart.data)
                        console.log(myChart.data.labels[activePoints._index])
                        const reporting_period = myChart.data.labels[activePoints._index]
                        window.location.href = '$url' + reporting_period
                       }
                    }
                }
   
        });
        function clickHandler(evt) {
            const points = myChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
            if (points.length) {
                const firstPoint = points[0];
                const label = myChart.data.labels[firstPoint.index];
                const value = myChart.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];
            }
        }
        ctx.onClick = clickHandler;
        ctx.onclick = function(evt){
            console.log('qwe')
            var activePoints = myLineChart.getElementsAtEvent(evt);
            // => activePoints is an array of points on the canvas that are at the same position as the click event.
        };
   
    }
   
     async function getData(year=''){
        const reporting_period = []
        const dv_at_ro = []
        const dv_at_coa = []
        const total_dv = []
       await $.ajax({
            type:'POST',
            url:window.location.pathname + '?r=report/dv-transmittal-summary',
            data:{year:year, _csrf : csrfToken},
           success:function(data){
            const res = JSON.parse(data)
            $.each(res,function(key,val){
                reporting_period.push(val.reporting_period)
                dv_at_ro.push(val.dv_count_at_ro)
                dv_at_coa.push(val.dv_count_at_coa)
                total_dv.push(val.total_dv)
            })
           }
        })
        return {reporting_period,dv_at_ro,dv_at_coa,total_dv}
    }

    $(document).ready(function(){
       
        $('#update_payee').click(async (e)=>{
            e.preventDefault()
            $('.site-index').hide();
            $('#dots5').show()
            await updateCloudPayeeApi()
            await updateCloudTransactionsApi()
            await updateCloudRecordAllotmentApi()

            await updateCloudChartOfAccount()
            await updateCloudSubAccount1()
            await updateCloudSubAccount2()

            await updateCloudProcessOrsApi()
            await updateCloudDvAucsApi()
            await updateCloudDvAucsEntriesApi()
            await updateCloudDvAccountingEntriessApi()
            await updateCloudCashDisbursementApi()
            await updateCloudAdvancesApi()
            await updateCloudAdvancesEntriesApi()

            $('.site-index').show();
            $('#dots5').hide()
        })
        $('.fc-prev-button').attr('class','fc-prev-button btn-xs btn-primary')
        BarChart()
        $('#bar_filter').change(function(){
      
            BarChart($(this).val())
        })

    })
        $(document).on('click','.fc-daygrid-day-number',function(){
            var date = $(this).closest('td').attr('data-date');
            //   console.log( $(this).closest('.fc-day'))
              var url = window.location.pathname + '?r=event/create&date='+date
            $('#genericModal').modal('show').find('#modalContent').load(url);
        })

JS;
$this->registerJs($script);

?>