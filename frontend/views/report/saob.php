<?php


use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'SAOB';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">
    <!-- <input type="text" name="" id="sample"> -->


    <div class="container card">

        <div class="row action">
            <div class="col-sm-3">
                <label for="reporting_period">Reportion Period</label>
                <?php
                echo DatePicker::widget([
                    'name' => 'reporting_period',
                    'id' => 'reporting_period',
                    'pluginOptions' => [
                        'format' => 'yyyy-mm',
                        'minViewMode' => 'months',
                        'autoclose' => true
                    ]
                ])
                ?>
            </div>
            <div class="col-sm-3">
                <button class="btn btn-success" id="generate">Generate</button>
            </div>
        </div>

        <?php Pjax::begin(['id' => 'container', 'clientOptions' => ['method' => 'POST']]) ?>
        <table>
            <thead>
                <tr>
                    <th rowspan="2">Project/Program</th>
                    <th rowspan="2">Allotment</th>
                    <th rowspan="1" colspan='3' style="text-align: center;">Obligations</th>
                    <th rowspan="2">Balances</th>
                    <th rowspan="2">Utilization</th>
                </tr>
                <tr>
                    <th>Last Month</th>
                    <th>This Month</th>
                    <th>To Date</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $balance = 0;
                if (!empty($query)) {
                    foreach ($query as $key => $data) {

                        echo "<tr>
                        <td>$key</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>";

                        foreach ($data as $val) {
                            $last_month_total = !empty($val["$reporting_period_last_month"]) ? $val["$reporting_period_last_month"] : 0;
                            $this_month_total = !empty($val["$reporting_period_this_month"]) ? $val["$reporting_period_this_month"] : 0;
                            $allotment_amount = $val['allotment_amount'];
                            $balance = $allotment_amount - $last_month_total - $this_month_total;


                            echo "<tr>
                                    <td>{$val['object_code']}  {$val['general_ledger']}</td>
                                    <td class='amount'>" . number_format($allotment_amount, 2) . "</td>
                                    <td class='amount'>" . number_format($last_month_total, 2) . "</td>
                                    <td class='amount'>" . number_format($this_month_total, 2) . "</td>
                                    <td class='amount'>" . number_format($this_month_total, 2) . "</td>
                                    <td class='amount'>" . number_format($balance, 2) . "</td>

                                </tr>";
                        }
                    }
                }


                ?>
            </tbody>
        </table>
        <?php Pjax::end() ?>
    </div>

</div>


<script src="/js/jquery.min.js" type="text/javascript"></script>
<link href="/js/maskMoney.js" />
<link href="/js/select2.min.js" />
<link href="/css/select2.min.css" rel="stylesheet" />
<link href="/js/jquery.dataTables.js" />
<link href="/css/jquery.dataTables.css" rel="stylesheet" />
<!-- 
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({

                processing: true,
                // serverSide: true,
                ajax: {
                    url: window.location.pathname + "?r=report/pending-ors",
                    data: function(data) {
                        data.id = data.id
                    },
                    dataSrc: 'data'
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'transaction_id'
                    },
                    {
                        data: 'reporting_period'
                    },
                    {
                        data: 'serial_number'
                    },
                    {
                        data: 'obligation_number'
                    },
                    {
                        data: 'funding_code'
                    },
                    {
                        data: 'document_recieve_id'
                    },
                    {
                        data: 'mfo_pap_code_id'
                    },
                    {
                        data: 'fund_source_id'
                    },
                    {
                        data: 'book_id'
                    },
                    {
                        data: 'date'
                    },
                ]
            });
        });
    </script> -->
<style>
    .grid-view td {
        white-space: normal;
        width: 100px;
    }
    .amount{
        text-align: right;
    }
    table,
    th,
    td {
        border: 1px solid black;
        width: 100%;
        padding: 12px;
    }

    @media print {
        .action{
            display: none;
        }
        th,td{
            padding: 4px;
            font-size: 10px;

        }
        .panel {
            border: 0;
        }
        .main-footer   {
            display:none
        }

    }
</style>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/jquery.dataTables.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<?php
SweetAlertAsset::register($this);
$script = <<< JS

        $('#sample').maskMoney();
    $('#generate').click(function(){    

        $.pjax({
            container: "#container", 
            url: window.location.pathname + '?r=report/saob',
            type:'POST',
            data:{
                reporting_period:$("#reporting_period").val()
            }
            
        })

    })

JS;
$this->registerJs($script);
?>