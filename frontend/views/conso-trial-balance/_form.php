<?php

use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Trial Balance';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">

    <?php

    $fund = Yii::$app->db->createCommand("SELECT fund_cluster_code.id,fund_cluster_code.name FROM fund_cluster_code")->queryAll();
    $books = Yii::$app->db->createCommand("SELECT books.id,books.name FROM books")->queryAll();

    ?>

    <div class="container card">
        <div class="row " style="bottom: 20px;">
            <?php $form = ActiveForm::begin(); ?>
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
            <div class="col-sm-3">
                <?php
                $book_type = Yii::$app->db->createCommand("SELECT `type` FROM books GROUP BY `type`")->queryAll();

                echo $form->field($model, 'type')->widget(Select2::class, [
                    'data' => ['mds' => 'MDS', 'lcca' => 'LCCA', 'all' => 'All'],
                    'id' => 'book',
                    'name' => 'book_id',
                    'options' => ['placeholder' => 'Select a Fund Cluster Code'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
            <div class="col-sm-3">
                <?php
                echo $form->field($model, 'entry_type')->widget(Select2::class, [
                    'data' => ['post-closing' => 'Post-Closing', 'pre-closing' => 'Pre-Closing', 'closing' => 'Closing'],
                    'id' => 'entry_type',
                    'name' => 'entry_type',
                    'options' => ['placeholder' => 'Select Entry Type'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
            <div class="col-sm-1">
                <button class=" btn btn-warning" style="margin-top: 25px;" type='button' id="generate">Generate</button>
            </div>
            <div class="form-group col-sm-1" style="margin-top: 25px;">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>



        <table id="data_table">
            <thead>
                <tr class="header" style="border: none;">

                    <td colspan="5">


                        <div style="width: 100%; display:flex;align-items:center; justify-content: center;">
                            <div style="margin-right: 20px;left:-10px">
                                <?= Html::img('frontend/web/dti.jpg', ['alt' => 'some', 'class' => 'pull-left img-responsive', 'style' => 'width: 100px;height:100px;']); ?>
                            </div>
                            <div style="text-align:center;" class="headerItems">
                                <h6>DEPARTMENT OF TRADE AND INDUSTRY</h6>
                                <h6>CARAGA REGIONAL OFFICE</h6>
                                <h6> <span> Consolidated</span> <span class="entry_type_heading"></span> <span> Trial Balance</span> <span class="book_name"></span>

                                </h6>
                                <h6>As of <span id="month"></span>

                            </div>

                        </div>

                    </td>


                </tr>
                <tr class="header" style="border: none;">
                    <td colspan="3" style="border: none;">
                        <span>
                            Entity Name:
                        </span>
                        <span>
                            DEPARTMENT OF TRADE AND INDUSTRY - CARAGA
                        </span>
                    </td>

                    <td colspan="3" style="border: none;">
                        <span>
                            Fund Cluster:
                        </span>
                        <span class="book_name"></span>
                    </td>


                </tr>


                <tr style="border-top:1px solid black">


                    <td style="border-top:1px solid black">
                        Account Name
                    </td>
                    <td style="border-top:1px solid black">
                        Code
                    </td>

                    <td style="border-top:1px solid black">
                        Debit
                    </td>
                    <td style="border-top:1px solid black">
                        Credit
                    </td>

                </tr>
            </thead>
            <tbody></tbody>
        </table>


    </div>
    <div id="dots5" style="display: none;">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <style>
        #reporting_period {
            background-color: white;
            border-radius: 3px;
        }

        .headerItems>h6 {
            font-weight: bold;
        }

        .amount {
            text-align: right;
        }


        .table {
            position: relative;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 12px;
            background-color: white;
        }


        table {
            border: 1px solid black;
            width: 100%;
        }

        .container {
            margin-top: 5px;
            position: relative;
            padding: 10px;

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

        @media print {
            .actions {
                display: none;
            }

            table,
            th,
            td {
                border: 1px solid black;
                padding: 5px;
                font-size: 10px;
            }

            @page {
                size: auto;
                margin: 0;
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

            /* thead {
                display: table-header-group
            } */

            .main-footer {
                display: none;
            }
        }

        #bars1 {
            display: none;
        }

        .table {
            display: none;
        }
    </style>

</div>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/thousands_separator.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/consoTrialBalanceJs.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css");
$csrfToken = Yii::$app->request->csrfToken;
?>
<script>
    $(document).ready(function() {

        $('#generate').click(function(e) {
            $("#data_table tbody").html('')
            e.preventDefault();
            const reporting_period = $('#consotrialbalance-reporting_period').val();
            const book_type = $('#consotrialbalance-type').val();
            const entry_type = $('#consotrialbalance-entry_type').val();
            $('.container').hide()
            $('#dots5').show()
            setTimeout(() => {
                query('<?= $csrfToken ?>', reporting_period, book_type, entry_type)
            }, 2000)
        })
    })
</script>