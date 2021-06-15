<?php

use app\models\Books;
use app\models\DvAucs;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "List of Pending DV's";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index" style="background-color: white;">


    <form id='filter'>
        <div class="row">
            <div class="col-sm-2">
                <label for="reporting_period">Reportinh Period</label>
                <?php

                echo DatePicker::widget([
                    'id' => 'reporting_period',
                    'name' => 'reporting_period',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'startView' => 'months',
                        'minViewMode' => 'months',
                        'format' => 'yyyy-mm'
                    ]
                ])
                ?>
            </div>
            <div class="col-sm-2">
                <label for="province">Province</label>
                <?php
                echo Select2::widget([
                    'name' => 'province',
                    'id' => 'province',
                    'data' => ['adn' => 'ADN', 'sdn' => 'SDN'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'placeholder' => 'Select Province'
                    ]
                ])

                ?>
            </div>
            <div class="col-sm-3">
                    <label for="book">Book</label>
                    <?php
                    echo Select2::widget([
                        'data'=>ArrayHelper::map(Books::find()->asArray()->all(),'id','name'),
                        'id'=>'book',
                        'name'=>'book',
                        'pluginOptions'=>[
                            'placeholder'=>'Select Book'
                        ]
                    ])
                    ?>
            </div>
            <div class="col-sm-3">
            <label for="advance_type">Advance Type</label>
            <?php
            
            echo Select2::widget([
                'data'=>['opex'=>'OPEX', 'sdo'=>'SDO'],
                'name'=>'advance_type',
                'id'=>'advance_type',
                'pluginOptions'=>[
                    'placeholder'=>'Select Advance Type'
                ]
            ])
            ?>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-success" id="generate">Generate</button>
            </div>
        </div>
    </form>

    <?php Pjax::begin(['id' => 'cibr', 'clientOptions' => ['method' => 'POST']]) ?>
    <table>

        <thead>
            <tr>
                <th colspan="15" style="text-align: center;border:1px solid white">CASH IN BANK REGISTER</th>
            </tr>
            <tr>
                <th colspan="15" style="text-align: center;border:1px solid white">

                    <span>
                        For the month of April,2021

                    </span>
                </th>
            </tr>
            <tr>
                <th colspan="12" class="header">
                    <span> Entity Name:Department of Trade and Industry</span>
                </th>
                <th colspan="3" class="header">
                    <span>
                        Sheet No. :_________________
                    </span>
                </th>
            </tr>
            <tr>
                <th colspan="12" class="header">
                    <span> Sub-Office/District/Division: _Provincial Office_</span>
                </th>
                <th colspan="3" class="header">
                    <span>
                        Name of Disbursing Officer: Ferdinand R. Inres
                    </span>
                </th>
            </tr>
            <tr>
                <th colspan="12" class="header">
                    <span> Municipality/City/Province: _Surigao del Norte_</span>
                </th>
                <th colspan="3" class="header">
                    <span>
                        Station: Surigao del Norte
                    </span>
                </th>
            </tr>
            <tr>
                <th colspan="12" rowspan="2" style="border-left:1px solid white;border-right:1px solid white;">
                    <span> Fund Cluster : _Fund 01_</span>
                </th>
                <th colspan="3" class="header" style="border-left:1px solid white;border-right:1px solid white;">
                    <span>
                        Bank : Landbank of the Philippines
                    </span>
                </th>
            </tr>
            <tr>
                <th colspan="3" style="border-left:1px solid white;border-right:1px solid white;">
                    <span>
                        Location: Surigao City
                    </span>
                </th>

            </tr>
            <tr>

                <th rowspan="6">Date</th>
                <th rowspan="6">Check No.</th>
                <th rowspan="6">Particular</th>
                <th rowspan="3" colspan="3">CASH IN BANK</th>
            </tr>

            <tr>
                <th colspan="9">BREAKDOWN </th>
            </tr>
            <tr>
                <th colspan="3">PERSONNEL SERVICES </th>
                <th colspan="3">MAINTENANCE AND OTHER OPERATING EXPENSES </th>
                <th colspan="3">OTHERS</th>
            </tr>


            <tr>
                <th rowspan="3">Deposits</th>
                <th rowspan="3">Withdrawals/Payments</th>
                <th rowspan="3"> Balances</th>
            </tr>
            <tr>
                <th rowspan="1">1</th>
                <th rowspan="1">2</th>
                <th rowspan="1"> 3</th>
                <th rowspan="1"> 4</th>
                <th rowspan="1"> 5</th>
                <th rowspan="1"> 6</th>
                <th rowspan="2"> Account Description</th>
                <th rowspan="2">UACS Code</th>
                <th rowspan="2">Amount</th>
            </tr>
            <tr>
                <th rowspan="1">1</th>
                <th rowspan="1">2</th>
                <th rowspan="1"> 3</th>
                <th rowspan="1"> 4</th>
                <th rowspan="1"> 5</th>
                <th rowspan="1"> 6</th>
            </tr>
        </thead>
        <tbody>

            <?php
            if (!empty($dataProvider)) {
                foreach ($dataProvider as $data) {
                    echo "<tr>
                        <td>" . $data['check_date'] . "</td>
                        <td>" . $data['check_number'] . "</td>
                        <td>" . $data['particular'] . "</td>
                        <td>" . $data['amount'] . "</td>
                        <td>" . $data['withdrawals'] . "</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>" . $data['gl_account_title'] . "</td>
                        <td>" . $data['gl_object_code'] . "</td>
                        <td>" . $data['amount'] . "</td>
                </tr>";
                }
                //     echo "<pre>";
                //         var_dump($dataProvider);
                //    echo" </pre>";
            }


            ?>

        </tbody>

    </table>
    <?php Pjax::end() ?>
</div>
<style>
    table,
    th,
    td {
        border: 1px solid black;
    }

    .header {
        border: 1px solid white
    }
</style>

<script src="/dti-afms-2/frontend/web/js/jquery.min.js" type="text/javascript"></script>
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

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/jquery.dataTables.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<?php
SweetAlertAsset::register($this);
$script = <<< JS

        $('#generate').click(function(e){
            e.preventDefault();
            
            $.pjax({
                container:'#cibr',
                type:'POST',
                url:window.location.pathname +"?r=report/cdr",
                data:$("#filter").serialize()
            })
        })
JS;
$this->registerJs($script);
?>