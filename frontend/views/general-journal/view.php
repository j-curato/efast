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

$month = DateTime::createFromFormat('Y-m', $model->reporting_period)->format('F Y');
$this->title = 'General Journal ' . $model->book->name . ' As of ' . $month;

$this->params['breadcrumbs'][] = ['label' => 'General Journals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="jev-preparation-index">
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <button id="export" type='button' class="btn-xs btn btn-success" style="margin:1rem;"><i class="glyphicon glyphicon-export"></i></button>
    </p>

    <div class="container panel panel-default">
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

                    <!-- <tr class="footer1" style="border:0;">
                        <td colspan="3" class="br"></td>
                        <td colspan='2' class="br" style="padding-top:2em">
                            CERTIFIED CORRECT:
                        </td>
                        <td></td>
                    </tr>
                    <tr class="footer2">
                        <td colspan="3" class="br"></td>

                        <td colspan='3' style="text-align: center;font-weight:bold;padding-top:4rem"> -->

                            <?php
                            // $reporting_period = DateTime::createFromFormat('Y-m', $model->reporting_period)->format('Y-m');
                            // if ($reporting_period < '2022-03') {
                            //     echo "<span style='font-weight: bold;text-decoration:underline'>JOHN VOLTAIRE S. ANCLA, CPA</span>";
                            //     echo "<br>";
                            //     echo " <span>Accountant III</span>";
                            // } else {

                            //     echo "<span style='font-weight: bold;text-decoration:underline'>CHARLIE C. DECHOS, CPA</span>";
                            //     echo "<br>";
                            //     echo " <span>OIC Accountant III </span>";
                            // }

                            ?>

                        <!-- </td>
                    </tr> -->
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
            font-size: 10px;
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
        .btn {
            display: none;
        }

        .main-footer {
            display: none;
        }
    }
</style>



<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/generalJournalJs.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$csrfToken = Yii::$app->request->csrfToken;
?>
<script>
    $(document).ready(function() {



        const book_id = '<?= $model->book_id ?>';
        const reporting_period = '<?= $model->reporting_period ?>';
        query('<?= $csrfToken ?>', book_id, reporting_period)
        $('#export').click(function(e) {
            e.preventDefault();
            $.ajax({
                container: "#employee",
                type: 'POST',
                url: window.location.pathname + '?r=general-journal/export',
                data: {
                    reporting_period: reporting_period,
                    book_id: book_id
                },
                success: function(data) {
                    var res = JSON.parse(data)
                    console.log(res)
                    window.open(res)
                }

            })
        })
    })
</script>