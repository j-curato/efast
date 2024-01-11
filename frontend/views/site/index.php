<?php


/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use dosamigos\chartjs\ChartJsAsset;
use aryelds\sweetalert\SweetAlertAsset;
use common\models\User;

$this->title = 'Dashboard';
$user_data = User::getUserDetails();
?>
<?= \yii\helpers\Html::csrfMetaTags() ?>
<div class="site-index ">
    <?php
    if (strtolower($user_data->employee->office->office_name) == 'ro') :
    ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Links</h3>
                            </div>
                        </div>
                        <div class="card-body m-0 pt-0">
                            <div class="row">
                                <div class="col-1">
                                    <?= Html::a(
                                        'NAS Drive',
                                        Url::to('http://dtinas/drive', true),
                                        ['target' => '_blank', 'class' => 'btn btn-danger']
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>
        <div class="row">



            <?php if (YIi::$app->user->can('ro_accounting_admin') || YIi::$app->user->can('ro_cash_admin')) { ?>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">DV Transmittals</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div>
                                <label for="bar_filter">Year</label>
                                <?= DatePicker::widget([
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
                </div>
            <?php } ?>
            <div class="col-lg-6">
                <div class="card">
                    <?= $this->render('@app/views/po-transmittal/monthly_transmittal_chart', ['defaultData' => []]) ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Calendar of Events</h3>
                            <a href="javascript:void(0);">View Report</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <div style="height:350;width:100%" id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
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

    .site-index {
        padding: 2rem;
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
        let chartContainer = document.getElementById("chartContainer")
        if(  chartContainer ){
            chartContainer.innerHTML = '&nbsp;';
        chartContainer.innerHTML = '<canvas id="myChart"></canvas>';
        }

        let ctx = document.getElementById('myChart');
        if(ctx){

        ctx.getContext('2d')
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.reporting_period,
                datasets: [{
                    label: '# of DV`s',
                    data: data.total_dv,
                    backgroundColor: 
                    '#668cff',
                      
                    borderColor: 
                    'rgb(0, 82, 204)',
                    borderWidth: 1
                },{
                    label: '# of Dv AT RO',
                    data: data.dv_at_ro,
                    backgroundColor: 
                    '#ff8080',
                    borderColor: 
                    'rgb(179, 0, 0)',
                    borderWidth: 1
                },{
                    label: '# of Dv AT COA',
                    data: data.dv_at_coa,
                    backgroundColor: 
                    '#5cd65c',
                    borderColor: 'rgb(0, 128, 43)',
              
                    borderWidth: 1
                }
            ]
            },
                options: {
                    // This chart will not respond to mousemove, etc
                    onClick(e) {
                        const activePoints = myChart.getElementsAtEvent(e)[0];
                 
                       if (activePoints !=undefined){
               
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
            var activePoints = myLineChart.getElementsAtEvent(evt);
            // => activePoints is an array of points on the canvas that are at the same position as the click event.
        };
    }

   
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
              var url = window.location.pathname + '?r=event/create&date='+date
            $('#genericModal').modal('show').find('#modalContent').load(url);
        })

JS;
$this->registerJs($script);

?>