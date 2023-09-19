<?php

use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'General Ledger';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">



    <?php

    $ledger = Yii::$app->db->createCommand("SELECT chart_of_accounts.uacs as object_code, CONCAT(chart_of_accounts.uacs,' - ',chart_of_accounts.general_ledger) as `name` FROM chart_of_accounts")->queryAll();
    $fund = Yii::$app->db->createCommand("SELECT fund_cluster_code.id,fund_cluster_code.name FROM fund_cluster_code")->queryAll();
    $books = Yii::$app->db->createCommand("SELECT books.id,books.name FROM books")->queryAll();

    ?>


    <div class="container card">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row " style="bottom: 20px;">


            <div class="col-sm-3">
                <?= $form->field($model, 'object_code')->widget(Select2::class, [
                    'id' => 'object_code',
                    'data' => ArrayHelper::map($ledger, 'object_code', 'name'),
                    'name' => 'object_code',
                    'options' => ['placeholder' => 'General Ledger Account'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
            <div class="col-sm-3">
                <?php
                echo  $form->field($model, 'book_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map($books, 'id', 'name'),
                    'id' => 'book_id',
                    'name' => 'book_id',
                    'options' => ['placeholder' => 'Select a Book Cluster Code'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
            <div class="col-sm-3">
                <?php
                echo $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                    'id' => 'reporting_period',
                    'name' => 'reporting_period',
                    'type' => DatePicker::TYPE_INPUT,
                    'readonly' => true,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm',
                        'minViewMode' => "months",

                    ]
                ]);
                ?>
            </div>
            <div class="col-sm-1" style="padding-top:25px">
                <button class="btn btn-warning" type='button' id="generate">Generate</button>
            </div>
            <div class="form-group col-sm-1" style="padding-top:25px">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

        </div>
        <?php ActiveForm::end(); ?>


        <div id='con'>

            <br>
            <table id="data_table" style="margin-top:30px">
                <thead>


                    <tr class="document_header1">
                        <th colspan="2">
                            Entity Name:
                        </th>
                        <th>
                            DEPARTMENT OF TRADE AND INDUSTRY
                        </th>
                        <!-- <th>
                    </th> -->
                        <th colspan="2">
                            Fund Cluster Code:
                        </th>
                        <th colspan="2">
                            <?php
                            if (!empty($fund_cluster_code)) {
                                echo $fund_cluster_code;
                            }
                            ?>
                        </th>
                    </tr>


                    <tr class="document_header1">
                        <th colspan="2">
                            Account Title:
                        </th>
                        <th>
                            <?php
                            if (!empty($account_title)) {
                                echo $account_title;
                            }
                            ?>
                        </th>
                        <th colspan="2">
                            UACS Object Code:
                        </th>
                        <!-- <th>
                    </th> -->
                        <th colspan="2">
                            <?php
                            if (!empty($object_code)) {
                                echo $object_code;
                            }
                            ?>


                        </th>
                    </tr>
                    <tr class="head">

                        <th rowspan="3">
                            Reporting Period
                        </th>
                        <th rowspan="3">
                            Date
                        </th>
                        <th rowspan="3">
                            Particulars
                        </th>
                        <th rowspan="3">
                            Reference No.
                        </th>
                        <th rowspan="1" colspan="3">
                            Amount
                        </th>

                    </tr>
                    <tr>
                        <th rowspan="1">
                            Debit
                        </th>
                        <th rowspan="1">
                            Credit
                        </th>
                        <th rowspan="1">
                            Balance
                        </th>

                    </tr>
                    <?php

                    ?>
                </thead>
                <tbody id="ledgerTable">

                </tbody>
            </table>


        </div>

    </div>
    <div id="dots5" style="display:none">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>

<style>
    #reporting_period {
        background-color: white;
        border-radius: 3px;
    }

    .table>thead>tr>th {
        border-bottom: 1px solid black;
        border-top: 1px solid black;
    }


    /* .header{
            border:none;

        }
        .header>td{
            border: none;
        } */

    .table {
        position: relative;
        margin-top: 20px;
    }

    .head>th {
        border-top: 1px solid black
    }

    table,
    th,
    td {
        border: 1px solid black;
        padding: 12px;
        background-color: white;
        text-align: center;
    }



    table {
        border: 1px solid black;
        width: 100%;
    }

    .container {
        margin-top: 5px;
        position: relative;
        padding: 10px;
        /* overflow: scroll; */

        overflow-y: hidden;
        overflow-x: auto;


    }

    .head>th {
        display: table-cell;
        vertical-align: text-top;
        text-align: center;
    }

    thead>tr>td {
        border: 1px solid black;
        padding: 10px;
        font-weight: bold;
    }

    #fund {
        display: none;
    }

    .actions {
        padding: 20px;
        position: relative;
    }

    /* .document_header>th {}

        .document_header {} */

    .document_header1>th {

        padding: 10px;
    }

    .document_header1>th {
        border: 0;
    }

    @media print {
        .actions {
            display: none;
        }

        table,
        th,
        td {
            padding: 5px;
            font-size: 10px;
        }

        thead>tr>td {
            border: 1px solid black;
            padding: 5px;
            font-weight: bold;
        }

        @page {
            size: auto;
            margin: 0cm;
            margin-top: 0.5cm;
        }



        .container {
            margin: 0;
            top: 0;

        }

        .entity_name {
            font-size: 5pt;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 5px;
            background-color: white;
        }

        .container {

            border: none;
        }


        table {
            page-break-after: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        td {
            page-break-inside: avoid;
            page-break-after: auto
        }

        .main-footer {
            display: none;
        }
    }
</style>

<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/generalLedgerJs.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$csrfToken = Yii::$app->request->csrfToken;
$csrfParam = Yii::$app->request->csrfParam;
?>
<script>
    $(document).ready(function() {
        $('#generate').click(function(e) {
            e.preventDefault()
            console.log('qweqwe')
            const reporting_period = $('#generalledger-reporting_period').val()
            const book_id = $('#generalledger-book_id').val()
            const object_code = $('#generalledger-object_code').val()
            $('#data_table tbody').html('')


            query('<?php echo $csrfParam ?>', '<?php echo $csrfToken ?>', object_code, reporting_period, book_id)
        })
    })

    function thousands_separators(num) {

        var number = Number(Math.round(num + 'e2') + 'e-2')
        var num_parts = number.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }
</script>