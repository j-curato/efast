<?php

use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\JevPreparation */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jev Preparations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="jev-preparation-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?php $t = yii::$app->request->baseUrl . '/index.php?r=jev-preparation/delete&id=' . $model->id; ?>
    <p class="actions" style="margin-left:50px;">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php
        //  Html::a('Delete', ['delete', 'id' => $model->id], [
        //     'class' => 'btn btn-danger delete',

        // 'data' => [
        //     'confirm' => 'Are you sure you want to delete this item?',
        //     'method' => 'post',
        // ],
        // ])

        ?>

        <?= Html::button('Delete', ['value' => Url::to($t), 'class' => 'btn btn-danger delete']) ?>
        <button onclick="window.print()" class="btn btn-primary print">
            Print
        </button>
    </p>

    <?php
    //  DetailView::widget([

    //     'model' => $model,
    //     'attributes' => [
    //         'id', [
    //             'attribute' => 'responsibility_center_id',
    //             'style'=>'border:2px solid red'
    //         ],

    //         'fund_cluster_code_id',
    //         'reporting_period',
    //         'date',
    //         'jev_number',
    //         'dv_number',
    //         'lddap_number',
    //         'entity_name',
    //         'explaination',
    //     ],
    // ]) 

    ?>





    <div class="container">

        <div class="form-wrapper">
            <div class="row-1">
                <div>
                    <div style=" 
                text-align:center;
                ">
                        <h5>
                            JOURNAL ENTRY VOUCHER
                        </h5>
                    </div>
                    <div style="padding:2px ;align-items:center;">
                        <span>
                            Entity Name:
                        </span>
                        <span>
                            <?php echo $model->entity_name ?>
                        </span>
                    </div>
                    <div>
                        <span>
                            Fund Cluster :
                        </span>
                        <span>
                            <?php echo $model->fundClusterCode->id ?>
                        </span>
                    </div>
                </div>
                <div>
                    <div style="border-bottom: 1px solid black; 
                padding:2px;
                text-align:center;
                height:50%;
                ">
                        <span>
                            JEV #:
                        </span>
                        <span>
                            <?php echo $model->jev_number ?>

                        </span>
                    </div>
                    <div class="date">
                        <div style=" border-right:1px solid black;">
                            <span>
                                Date:
                            </span>
                            <span>
                                <?php echo $model->date ?>
                            </span>
                        </div>
                        <div>
                            <span>
                                Reporting Period:
                            </span>
                            <span>
                                <?php echo $model->reporting_period ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-2">
                <div style="text-align: center;">
                    <h5>
                        Responsibility Center
                    </h5>
                </div>
                <div style="flex-grow:1;
            ">
                    <div style="text-align: center;border-bottom:1px solid black;">
                        <h5>
                            ACCOUNTING ENTRIES
                        </h5>
                    </div>
                    <div class="acc-exp-row">
                        <div style="width: 37.5% ;
                    border-right:1px solid black; 
                    text-align:center;">
                            <h5>
                                Accounts and Explanation
                            </h5>
                        </div>
                        <div style=" border-right:1px solid black">
                            <h5>
                                UACS Object Code

                            </h5>
                        </div>
                        <div style="flex-grow: 1;">
                            <div style="border-bottom:1px solid black;text-align:center;
                        padding:0">
                                <h5>
                                    Amount
                                </h5>
                            </div>
                            <div class="h-debit-credit">
                                <div style="border-right:1px solid black;">
                                    Debit
                                </div>
                                <div>
                                    Credit
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-2">
                <div>
                    <h6>
                        <?php echo $model->responsibilityCenter->name ?>
                    </h6>
                </div>
                <div style="flex-grow:1;
            ">
                    <div class="acc-exp-row">
                        <div style="width: 37.5% ; border-right:1px solid black;">
                            <h6>
                                <?php echo $model->explaination ?>
                            </h6>
                        </div>
                        <div style="border-right:1px solid black;">
                        </div>
                        <div style="flex-grow: 1;">

                            <div class="h-debit-credit">
                                <div style="border-right:1px solid black;height:100%">
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ANG MGA ACCOUNT ENTRIES -->
            <?php foreach ($model->jevAccountingEntries as $key => $value) : ?>
                <div class="row-2">
                    <div>
                        <h6>
                        </h6>
                    </div>
                    <div style="flex-grow:1;
            ">
                        <div class="acc-exp-row">
                            <div style="width: 37.5% ; border-right:1px solid black;">
                                <h6>
                                    <?php echo $value->chartOfAccount->general_ledger ?>
                                </h6>
                            </div>
                            <div style="border-right:1px solid black">
                                <h6>
                                    <?php echo $value->chartOfAccount->uacs ?>
                                </h6>
                            </div>
                            <div style="flex-grow: 1;">

                                <div class="h-debit-credit">
                                    <div style="border-right:1px solid black;height:100%">
                                        <h6>
                                            <?php echo number_format($value->debit, 2) ?>
                                        </h6>
                                    </div>
                                    <div>
                                        <h6>
                                            <?php echo number_format($value->credit, 2) ?>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $total_debit = 0;
                $total_credit = 0;
                $total_credit += $value->credit;

                $total_debit += $value->debit;
                ?>
            <?php endforeach; ?>

            <!-- DV ROW -->
            <div class="row-2">
                <div>
                    DV#
                </div>
                <div style="flex-grow:1;">
                    <div class="acc-exp-row">
                        <div style="width: 37.5% ; border-right:1px solid black">
                            <?php echo $model->dv_number ?>
                        </div>
                        <div style="border-right: 1px solid black;">
                        </div>
                        <div style="flex-grow: 1;">

                            <div class="h-debit-credit">
                                <div style="border-right:1px solid black">

                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- LDDAP -->
            <div class="row-2">
                <div>
                    LDDAP#
                </div>
                <div style="flex-grow:1;">
                    <div class="acc-exp-row">
                        <div style="width: 37.5% ; border-right:1px solid black">
                            <?php echo $model->lddap_number ?>
                        </div>
                        <div style="border-right:1px solid black">
                        </div>
                        <div style="flex-grow: 1;">

                            <div class="h-debit-credit">
                                <div style="border-right:1px solid black">
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- TOTAL -->
            <div class="row-2">
                <div>
                </div>
                <div style="flex-grow:1;
            ">

                    <div class="acc-exp-row">
                        <div style="width: 37.5% ; border-right:1px solid black">
                        </div>
                        <div style="border-right:1px solid black;">
                            <h5>Total</h5>
                        </div>
                        <div style="flex-grow: 1;">

                            <div class="h-debit-credit">
                                <div style="border-right:1px solid black">
                                    <h6>
                                        <?php echo number_format($total_debit, 2) ?>

                                    </h6>
                                </div>
                                <div>
                                    <h6>
                                        <?php echo number_format($total_credit, 2) ?>

                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row-1">
                <div>
                    <div>
                        <h6>
                            Prepared By:
                        </h6>
                    </div>
                    <div style="text-align: center;
                ">
                        <h5>
                            CHARLIE C. DECHOS, CPA
                        </h5>
                        <h6>
                            Accountant II
                        </h6>
                    </div>
                </div>
                <div>
                    <div>
                        <h6>
                            Certified Correct:
                        </h6>
                    </div>
                    <div style="text-align: center;
                ">
                        <h5>
                            JHON VOLTAIRE S. ANCLA, CPA
                        </h5>
                        <h6>
                            Accountant III
                        </h6>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <style>
        .container {
            /* border: 1px solid black; */
            height: auto;
        }

        .row-2 {
            text-align: center;
            height: auto;
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
            padding: 3px;
            margin: 0;
            border: 1px solid black;
            font-weight: bold;
        }

        h5 {
            font-weight: bold;
        }

        @media print {
            .actions {
                display: none;
            }

            .form-wrapper {
                margin-top: 20px;
                background-color: red;
            }

            .print {
                display: none;
            }

            @page {
                margin-top: 20cm;
                margin-bottom: 5cm;
            }


        }
    </style>
</div>

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
                    cancelButtonText: "Cancer",
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