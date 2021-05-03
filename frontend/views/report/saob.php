<?php


use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'SAOB';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">
    <!-- <input type="text" name="" id="sample"> -->

    
    <div class="container panel panel-default">


        <table>
            <thead>
                <tr>
                    <th rowspan="2">PROJECT/program</th>
                    <th rowspan="2">Allotment</th>
                    <th rowspan="1" colspan='3' style="text-align: center;">OBligations</th>
                    <th rowspan="2">Balances</th>
                    <th rowspan="2">Utilization</th>
                </tr>
                <tr>
                    <th>Last Month</th>
                    <th>Last Month</th>
                    <th>Last Month</th>
                </tr>
            </thead>
        </table>

    </div>

</div>


<script src="/dti-afms-2/frontend/web/js/jquery.min.js" type="text/javascript"></script>
<link href="/dti-afms-2/frontend/web/js/maskMoney.js" />
<link href="/dti-afms-2/frontend/web/js/select2.min.js" />
<link href="/dti-afms-2/frontend/web/css/select2.min.css" rel="stylesheet" />
<link href="/dti-afms-2/frontend/web/js/jquery.dataTables.js" />
<link href="/dti-afms-2/frontend/web/css/jquery.dataTables.css" rel="stylesheet" />
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

    table,
    th,
    td {
        border: 1px solid black;
        width: 100%;
        padding: 12px;
    }

    @media print {}
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
JS;
$this->registerJs($script);
?>