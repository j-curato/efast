<?php

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
<div class="jev-preparation-view" style="overflow:hi">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
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
    $credit = 0;
    $debit = 0;
    ?>

    <div class="container">

        <div class="grid-container">
            <div class="grid-item item-1">
                <div class="item-1-head head-1">
                    <div style="text-align: center;">
                        <h6>JOURNAL ENTRY VOUCHER</h6>
                    </div>
                    <div>
                        <span>Entity Name:</span>
                        <span>
                            <?php echo $model->entity_name ?>
                        </span>
                    </div>
                    <div>
                        <span>
                            Fund Cluster Code:
                        </span>
                        <span>
                            <?php echo $model->fundClusterCode->name ?>
                        </span>
                    </div>
                </div>
                <div class="item-1-head head-2 d-flex">

                    <div style="display: flex;padding:2px">
                        <span>
                            <h6>
                                JEV No.
                            </h6>

                        </span>
                        <span>
                            <h6>
                                <?php echo $model->jev_number ?>

                            </h6>
                        </span>
                    </div>

                    <div style="display: flex; padding:5px">
                        <span>
                            <h6>
                                DATE:
                            </h6>
                        </span>
                        <span>
                            <h6>

                                <?php echo $model->date ?>
                            </h6>
                        </span>
                    </div>

                </div>

            </div>
            <div class="grid-item item-3">

                <div style="height: 20%;">
                    <span>
                        <h6><?php echo $model->responsibilityCenter->name ?></h6>

                    </span>

                </div>
                <div style="height: 10%; margin-top:15rem;">
                    <span>
                        <h6>DV#:</h6>

                    </span>

                </div>
                <div style="height: 10%;">
                    <span>
                        <h6>LDDAP$:</h6>

                    </span>

                </div>
            </div>
            <div class="grid-item item-4">
                <div>

                    <h6>ACCOUNTING ENTRIES</h6>
                </div>
                <div class="bhead">
                    <div>
                        Accounts and Explaination
                    </div>
                    <div>
                        <span>UACS Object Code</span>
                    </div>
                    <div class="amount">
                        <div class="item-1">
                            Amount
                        </div>

                        <div class="item-2">
                            Debit
                        </div>
                        <div class="item-3">
                            Credit
                        </div>


                    </div>
                </div>

                <div class="samp" style="margin: 0; padding:0;">
                    <div>
                        <?php echo $model->responsibilityCenter->name ?>
                    </div>
                    <div class="">
                        <span>
                            Total
                        </span>
                    </div>
                    <div>


                    </div>
                    <div>

                    </div>
                </div>
                <?php foreach ($model->jevAccountingEntries as $key => $value) : ?>
                    <div class="samp" style="margin: 0; padding:0;">
                        <div>
                            <?php echo $value->chartOfAccount->general_ledger ?>
                        </div>
                        <div class="">
                            <?php echo $value->chartOfAccount->uacs ?>
                        </div>
                        <div>

                            <?php echo $value->debit ?>

                        </div>
                        <div>


                            <?php echo $value->credit ?>
                        </div>
                    </div>
                    <?php $credit += $value->credit;
                    $debit += $value->debit;
                    ?>
                <?php endforeach; ?>




                <div class="samp" style="margin: 0; padding:0;margin-top:auto ">
                    <div>

                    </div>
                    <div class="">
                        <span>
                            Total
                        </span>
                    </div>
                    <div>
                        <?php echo number_format($debit, 2) ?>


                    </div>
                    <div>
                        <?php echo number_format($credit, 2) ?>

                    </div>
                </div>
            </div>

            <div class="grid-item footer-1">
                <div class="footer-item footer-item-1">
                    <h6>head1</h6>
                </div>
                <div class="footer-item footer-item-2">
                    <h6>head2</h6>
                </div>
            </div>

        </div>
    </div>


    <style>
        .samp {
            border: 1px solid red;
            display: grid;
            grid-template-columns: 43.2% 100.5px .98fr 1fr;
        }

        .samp>div {
            border: 1px solid red;
        }

        .amount {
            display: grid;
            grid-template-areas: "a-item-1 a-item-1"
                "a-item-2 a-item-3";
        }

        .amount .item-1 {
            grid-area: a-item-1;
        }

        .amount .item-2 {
            grid-area: a-item-2;
            border: 1px solid red;
        }

        .amount .item-3 {
            grid-area: a-item-3;
            border: 1px solid red;
            padding: 0;
        }

        .body-contents {
            display: grid;
            height: 3rem;
            grid-template-columns: 43.2% 100px 4fr;
        }

        .item-3>div>span {
            text-align: center;
        }



        /* .amount>div {
            border: 1px solid yellow;
        } */

        .account-body {
            height: 100%;
            display: grid;
            grid-template-columns: 43.2% 100px 4fr;
        }

        .bhead {
            height: 25%;
            display: grid;
            grid-template-columns: 43.2% 100px 4fr;
        }

        .bhead>div {
            border: 1px solid yellow;
        }

        .account-body>div {
            border: 1px solid yellow;
        }

        .ab-item {
            display: grid;
            grid-template-rows: 10% 1fr;
        }

        .ab-item>div {
            border: 1px solid yellow;
        }

        .bheader {
            display: flex;
            border: 1px solid black;
            height: 100%;
        }

        .debit-credit {
            display: flex;
        }


        .a-entry {
            border: 1px solid green;
            display: flex;

        }

        .a-entry>div {
            border: 1px solid yellow;
            width: 100%;

        }

        .grid-container {
            border: 2px solid black;
            height: 70vh;
            display: grid;
            grid-template-areas:
                "head-1 head-1 head-1 head-1"
                "body1 body-2 body-2 body-2"
                "footer-1 footer-1 footer-1 footer-1"
            ;
            grid-template-rows: 20% 1fr 20%;
            grid-template-columns: 12% 1fr 20% 30%;
        }

        .grid-item {
            background-color: #ccc;
            border: 2px solid orange;
            padding: 20px;
        }

        .col2 {
            grid-area: col2;
            display: flex;
        }

        .col2-item {
            border: 1px solid yellow;
        }


        .item-1-head {
            border: 2px solid yellow;
        }

        .item-1 {
            grid-area: head-1;
            display: grid;
            padding: 0;
            grid-template-areas:

                "item-1-head-1 item-1-head-2";
            grid-template-columns: 2fr 1fr;

        }

        .head-1 {
            grid-area: item-1-head-1;
        }

        .head-2 {
            grid-area: item-1-head-2;
            display: flex;
            flex-direction: column;
        }

        .head-2>div {
            height: 100%;
            border: 1px solid yellow;
        }


        .item-2 {
            grid-area: head-2;
        }

        .item-3 {
            grid-area: body1;
            padding: 0;

        }

        .item-3>div {
            height: 20%;
            width: 100%;
            border: 2px solid yellow;
            padding: 0;
            margin: 0;

        }

        .item-4 {
            grid-area: body-2;
            display: flex;
            flex-direction: column;
            padding: 0;

        }


        /* 
        .bhead {
            height: 15%;
            border: 1px solid green;
        } */

        .item-5 {
            grid-area: body-3;
        }

        .item-6 {
            grid-area: body-4;
        }

        .footer-1 {
            grid-area: footer-1;
            padding: 0;
            display: grid;
            grid-template-areas:
                "footer-item-1 footer-item-2";
            grid-template-columns: 1fr 1fr;
        }

        .footer-item {
            border: 2px solid yellow;
        }

        .footer-item-1 {
            grid-area: footer-item-1;
        }

        .footer-item-2 {
            grid-area: footer-item-2;
        }
    </style>
</div>