<?php

use app\models\FundClusterCode;
use app\models\JevReportingPeriod;
use app\models\SubAccounts1;
use app\models\SubAccounts2;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\select2\Select2Asset;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\JevPreparation */

$this->title = $model->jev_number;
$this->params['breadcrumbs'][] = ['label' => 'Jev Preparations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
Select2Asset::register($this);


?>
<div class="jev-preparation-view" style="box-shadow: none;border:none">



    <div class="container ">
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id, 'duplicate' => false], ['class' => 'btn btn-primary']) ?>
            <?= Html::button('<i class="fa fa-print"></i> Print', ['onclick' => 'window.print()', 'class' => 'btn btn-success print']) ?>
            <?= !empty($model->fk_dv_aucs_id) ?  Html::a('DV Link', ['dv-aucs/view', 'id' => $model->fk_dv_aucs_id], ['class' => 'btn btn-link']) : '' ?>
        </p>
        <table id="data_table">
            <thead>
                <tr>
                    <th rowspan="2" colspan="2">
                        <div style="text-align:center; ">
                            <h5>
                                JOURNAL ENTRY VOUCHER
                            </h5>
                        </div>
                        <div style="padding:5px ;align-items:center;">

                            <span>
                                <span><?= !empty($model->payee->account_name) ? 'Payee' : 'Entity Name' ?> :</span>
                                <u>&emsp;<?= $model->payee->account_name ?? 'Department of Trade and Industry' ?> &emsp;</u>

                                <?php

                                // if (!empty($model->cash_disbursement_id)) {
                                //     echo "<span>Payee :</span>";
                                //     echo "<span>{$model->cashDisbursement->dvAucs->payee->account_name}</span>";
                                // } else {
                                //     if (!empty($model->payee_id)) {
                                //         echo "<span>Payee :</span>";
                                //         echo "<span>{$model->payee->account_name}</span>";
                                //     } else {
                                //         echo   "<span>Entity Name :</span>";
                                //         echo   "<span>Department of Trade and Industry</span>";
                                //     }
                                // }

                                ?>
                            </span>
                        </div>
                        <div style="padding:5px ;align-items:center;">
                            <span>Fund Cluster :</span>
                            <u>&emsp;<?= $model->books->name ?? "" ?>&emsp;</u>
                        </div>
                    </th>

                    <th rowspan="1" colspan="3">
                        <span>JEV #:</span>
                        <u>&emsp;<?= $model->jev_number ?>&emsp;</u>
                    </th>

                </tr>
                <tr>
                    <th rowspan="1" colspan="3">
                        <div>
                            <span>
                                Date:
                            </span>
                            <u>&emsp;<?= $model->date ?>&emsp;</u>
                        </div>
                        <div>
                            <span>
                                Reporting Period:
                            </span>
                            <u>&emsp;<?= $model->reporting_period ?>&emsp;</u>
                        </div>
                    </th>

                </tr>
                <tr>
                    <th rowspan="3" style="text-align: center;">
                        Responsibility Center
                    </th>
                    <th rowspan="1" colspan="4" style="text-align: center;">
                        ACCOUNTING ENTRIES
                    </th>
                </tr>
                <tr>
                    <th rowspan="2" style="text-align: center;">
                        Accounts and Explanation
                    </th>
                    <th rowspan="2">
                        UACS Object Code
                    </th>
                    <th colspan="2" class="ctr">
                        Amount
                    </th>

                </tr>
                <tr>
                    <th>
                        Debit
                    </th>
                    <th>
                        Credit
                    </th>
                </tr>

            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php
                        echo ($model->responsibilityCenter) ? $model->responsibilityCenter->name : '';
                        ?>
                    </td>
                    <td style="word-wrap:break-word;width:400px"><?php echo $model->explaination ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>


                <?php
                $total_credit = 0;
                $total_debit = 0;
                foreach ($model->getItems() as $itm) {
                    $total_credit += floatval($itm['debit']);
                    $total_debit += floatval($itm['debit']);
                    echo "<tr>
                            <td></td>
                            <td>{$itm['account_title']}</td>
                            <td>{$itm['object_code']}</td>
                            <td style='text-align:right'>" . number_format($itm['debit'], 2) . " </td>
                            <td style='text-align:right'>" . number_format($itm['credit'], 2) . "</td>         
                        </tr>";
                }
                ?>

                <tr>
                    <td>DV# </td>
                    <td><?= $model->dvAucs->dv_number ?? '' ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <?php
                        if (strtolower($model->check_ada) == 'ada') {
                            echo "LDDAP#";
                        } else if (strtolower($model->check_ada) == 'check') {
                            echo "CHECK#";
                        } else
                            echo '<br>'
                        ?>
                    </td>
                    <td><?= $check_number ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>



                <tr>
                    <td></td>
                    <td></td>
                    <th class="ctr">Total</th>
                    <th class="amount"><?= number_format($total_debit, 2); ?></th>
                    <th class="amount"><?= number_format($total_credit, 2); ?></th>
                </tr>
                <tr>
                    <td colspan="5">
                        <div style='width:50%;float:left;text-align:center;' class="flt-lft">
                            <h6 class="pull-left">
                                Prepared By:
                            </h6>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <select class="employee_select " style="width: 50%;font-weight:bold">
                                <option value=""></option>
                            </select>
                            <br>
                            <span class='position'></span>
                        </div>
                        <div style='width:50%;float:left;text-align:center' class="flt-lft">
                            <h6 class="pull-left">
                                Certified Correct:
                            </h6>
                            <br>
                            <br>
                            <div style="width: 70px;height:50px;margin-left:auto;margin-right:auto">
                                <?= Html::img(Yii::$app->request->baseUrl . '/frontend/web/charles_sign.png', [
                                    'alt' => 'some', 'class' => 'pull-left img-responsive',
                                    'style' => 'width: 80px;height:50px;margin-left:auto'
                                ]); ?>
                            </div>
                            <br>

                            <h5>
                                CHARLIE C. DECHOS, CPA
                            </h5>
                            <h6>
                                Accountant III
                            </h6>
                        </div>


                    </td>

                </tr>
            </tbody>
        </table>



    </div>
    <style>
        .container {
            /* border: 1px solid black; */
            height: auto;
            background-color: white;
            /* box-shadow: 12px; */
            border-radius: 5px;
            padding: 20px;
        }

        .amount {
            text-align: right;
        }

        .ctr {
            text-align: center;
        }

        th,
        td {
            border: 1px solid black;
            padding: 7px;

        }

        .row-2 {
            text-align: center;
            height: auto;
            border: none;
        }

        .date {
            display: flex;
            /* grid-template-columns: 1fr 1fr; */
            /* grid-row: 1fr; */
            position: relative;
            height: 50%;
            padding: 0;
            margin: 0;

        }

        .date>div {
            width: 100%;
            height: 100%;
            padding: 2px;
            text-align: center;
            margin-top: auto;
            margin-bottom: auto;

            ;
        }

        .date>div>span {
            margin: 2px;
        }

        .h-debit-credit {
            display: grid;
            grid-template-columns: 1fr 1fr;
            text-align: center;
            height: 100%;

        }



        .acc-exp-row {
            display: flex;
            width: 100%;
            height: 100%;

        }

        .acc-exp-row>div {
            width: 100px;
            /* border: 1px solid black; */
        }

        .row-2 {
            display: flex;
            width: 100%;

        }

        .row-2>div {
            border: 1px solid black;

            width: 20%;
        }

        .row-1 {
            display: grid;
            height: 100px;
            width: 100%;
            padding: 0;
            margin: 0;
            grid-template-columns: 1fr 1fr;
        }

        .row-1>div {
            width: 100%;
            padding: 0;
            margin: 0;
            border: 1px solid black;
            font-weight: bold;
        }

        h5 {
            font-weight: bold;
        }

        table {
            width: 100%;
        }

        @media print {

            .actions,
            .btn {
                display: none;
            }


            .print {
                display: none;
            }


            .main-footer {
                display: none;
            }

            .select2-selection__arrow {
                display: none !important;
            }

            .select2-container--default .select2-selection--single {
                border: none !important;
                font-weight: bold;
            }

        }
    </style>
</div>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/scripts.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/jquery.dataTables.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    $(document).ready(function() {

        employeeSelect()
        $('#data_table').on('change', '.employee_select', function() {
            const id = $(this).val()
            let position = ''
            const this_pos = $(this).closest('td').find('.position')
            $.ajax({
                url: window.location.pathname + "?r=employee/search-employee",
                data: {
                    id: id
                },
                success: function(data) {
                    position = data.results.position
                    console.log(position)
                    this_pos.text(position)
                }
            })




        })
    })
</script>
<?php
SweetAlertAsset::register($this);
$script = <<< JS
    

            $(".delete").click(function(e){
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover thi data!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Delete',
                    cancelButtonText: "Cancel",
                    closeOnConfirm: true,
                    closeOnCancel: true,
                    timer: 2000,
                },
                function(isConfirm){

                if (isConfirm){
                    swal("Shortlisted!", "Candidates are successfully shortlisted!", "success");
                    
                    var x= $('.delete').val()
                    $.ajax({
                        type: "POST",
                        url: x,
                        // data: data,
                        // success: success,
                        // dataType: dataType
                    });
                    } 
                else {
                    // swal("Cancelled", "Your imaginary file is safe :)", "error");
                        // e.preventDefault();
                    }
                });
            })
JS;
$this->registerJs($script);
?>