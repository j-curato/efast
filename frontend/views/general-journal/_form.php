<?php

use app\models\Books;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'General Journal';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">






    <div class="container card">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row" style="bottom: 20px;">



            <div class="col-sm-5">
                <?php
                echo $form->field($model, 'book_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                    'name' => 'book',
                    'options' => ['placeholder' => 'Select a Book'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
            <div class="col-sm-5">
                <?php
                echo $form->field($model, 'reporting_period')->widget(DatePicker::class, [
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
            <div class="col-sm-1" style="margin-top:2.5rem">
                <button class="btn btn-warning" id="generate" type="button">Generate</button>
            </div>
            <div class="form-group col-sm-1" style="margin-top:2.5rem">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>


        <?php ActiveForm::end(); ?>

        <div id="con">


            <table class="table " style="margin-top:30px" id='data-table'>
                <thead>
                    <tr>
                        <th colspan="6" style="border-bottom: 1px solid black " class="header1">
                            <h5>GENERAL JOURNAL</h5>
                            <h6><?php if (!empty($reporting_period)) {
                                    echo date("F Y", strtotime($reporting_period));
                                }
                                ?></h6>
                        </th>
                    </tr>
                    <tr class="header" style="border: none;">
                        <th colspan="3" style="border:1px solid black;">
                            <span>
                                Entity Name:
                            </span>
                            <span>
                                DEPARTMENT OF TRADE AND INDUSTRY - CARAGA
                            </span>
                        </th>

                        <th colspan="3" style="border-bottom:1px solid black">
                            <span>
                                Book:
                            </span>
                            <span id="fund_cluster" style="text-align: center;">

                                <?php if (!empty($book_name)) {
                                    echo $book_name;
                                }
                                ?>
                            </span>
                        </th>


                    </tr>


                    <tr style="border-top:1px solid black;border-bottom:1px solid black " ">
                    <th  rowspan=" 2" class="header2" style="border-top:1px solid black;border-bottom:1px solid black">
                        Date
                        </th>
                        <th rowspan="2" class="header2" style="border-bottom: 1px solid black;">
                            JEV No.
                        </th>
                        <th rowspan="2" class="header2" style="border-bottom: 1px solid black;">
                            Particulars
                        </th>
                        <th rowspan="2" class="header2" style="border-bottom: 1px solid black;">
                            UACS Object Code
                        </th>
                        <th colspan="3" class="header2" style="border-bottom: 1px solid black;">
                            Amount
                        </th>


                    </tr>
                    <tr>
                        <td style="border-top:1px solid black">
                            Debit
                        </td>
                        <td style="border-top:1px solid black">
                            Credit
                        </td>
                    </tr>
                </thead>

                <tbody id="ledgerTable">


                </tbody>
                <!-- <tfoot>

                    <tr class="footer1" style="border:0;">
                        <td colspan="3" class="br"></td>
                        <td colspan='2' class="br" style="padding-top:2em">
                            CERTIFIED CORRECT:
                        </td>
                        <td></td>
                    </tr>
                    <tr class="footer2">
                        <td colspan="3" class="br"></td>

                        <td colspan='2' class="br">
                            <h5>
                                JOHN VOLTAIRE S. ANCLA,CPA

                            </h5>
                            <h6>
                                Accountant III
                            </h6>
                        </td>
                        <td></td>
                    </tr>
                </tfoot> -->
            </table>

        </div>


    </div>

</div>
<div id="dots5" style="display:none">
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

    .amount {
        text-align: right;
    }


    .header1 {
        text-align: center;
        border-bottom: 1px solid black;
    }

    .header1>h5 {
        font-weight: bold;
    }

    /* tfoot>tr>td {
            border: 1px solid white;
        }
 */


    .header2 {
        border-bottom: 1px solid black;
        text-align: center;
    }


    .footer1>.br {
        border-bottom: 1px solid white;
        border-right: 1px solid white;
    }

    .footer1>td {
        border-bottom: 1px solid white;
        margin: 0;

    }

    .footer2>.br {
        border-right: 1px solid white;
        text-align: center;
    }



    .footer2>td>h5 {
        font-weight: bold;
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

    /* tfoot{
            display: none;
        } */

    @media print {
        .actions {
            display: none;
        }

        /* tfoot{
                display: inline;
            } */
        .br>h5 {
            font-size: 10px;
            color: red;
            margin: 0;
        }

        /* tfoot>tr,
            td {
                border: 0;
                color: red;
            }

            tfoot {
                border: 0;
            } */
        table,
        th,
        td {
            font-size: 10px;
        }

        @page {
            size: auto;
            margin: 0;
            margin-top: 0.5cm;
            margin-bottom: 0.5cm;
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
</style>



<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/generalJournalJs.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$csrfToken = Yii::$app->request->csrfToken;
?>
<script>
    $(document).ready(function() {

        $('#generate').click(function(e) {
            e.preventDefault()

            const book_id = $('#generaljournal-book_id').val()
            const reporting_period = $('#generaljournal-reporting_period').val()
            query('<?= $csrfToken ?>', book_id, reporting_period)
        })
    })
</script>