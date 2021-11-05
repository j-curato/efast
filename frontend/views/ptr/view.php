<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Ptr */

$this->title = $model->ptr_number;
$this->params['breadcrumbs'][] = ['label' => 'Ptrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ptr-view">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->ptr_number], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->ptr_number], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="con">

        <table>
            <thead>
                <tr>
                    <th colspan="3">
                        <span>Entity Name : </span>
                        <span>_________________</span>
                    </th>
                    <th colspan="3">
                        <span>Fund Cluster :</span>
                        <span>_________________</span>
                    </th>
                </tr>
                <tr>
                    <th colspan="3">
                        <span>Fom Accountable Officer/Agency/Fund Cluster:</span>
                        <span>_______________________</span>
                        <br>
                        <span>To Accountable Officer/Agency/Fund CLuster:</span>
                        <span>________________________</span>
                    </th>
                    <th colspan="3">
                        <span>PTR No. :</span>
                        <span>____________________</span>
                        <br>
                        <span>Date: </span>
                        <span>____________________</span>
                    </th>
                </tr>
                <tr>
                    <th style="border: 0;" colspan="2"></th>
                    <th colspan="1" style="border: 0;">


                        <span style="width:100px;margin-right: auto;">
                            <span class="chk_box">


                                <?php
                                $transfer_type = strtolower($model->transferType->type);
                                if ($transfer_type === 'donation') {
                                    echo "<span>&#10003;</span>";
                                } else {
                                    echo "<span class='q'>...</span>";
                                }

                                ?>
                            </span>
                            Donation
                        </span>

                        <!-- <span>&#10003;</span> -->
                        <br>
                        <span style="right: -10px;">

                            <span class="chk_box">
                                <?php
                                if ($transfer_type === 'reassignment') {
                                    echo "<span>&#10003;</span>";
                                } else {
                                    echo "<span class='q'>....</span>";
                                }
                                ?>
                            </span>
                            Reassignment
                        </span>

                    </th>
                    <th colspan="3" style="border: 0;">
                        <span>
                            <span class="chk_box">

                                <?php
                                if ($transfer_type === 'relocate') {
                                    echo "<span>&#10003;</span>";
                                } else {
                                    echo "<span class='q'>...</span>";
                                }
                                ?>

                            </span>
                            Relocate
                        </span>
                        <br>
                        <span>

                            <span class="chk_box" style="width:12px"><span class='q'>....</span></span>
                            Others (Specify) __________________
                        </span>
                    </th>

                </tr>
                <tr>
                    <th colspan="2">Date Acquired</th>
                    <th>Property No.</th>
                    <th>Description</th>
                    <th>Amount </th>
                    <th>Condtion of PPE</th>
                </tr>

            </thead>
            <tbody>

            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6">
                        <span>Reason for Transfer:</span>
                    </th>
                </tr>
                <tr>
                    <td></td>
                    <th>Aprroved By:</th>
                    <th>Released/Issued By:</th>
                    <th>Recieved By:</th>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Signature:</th>
                    <td>______________________</td>
                    <td>______________________</td>
                    <td>______________________</td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <th>Printed Name:</th>
                    <td>______________________</td>
                    <td>______________________</td>
                    <td>______________________</td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <th>Designation:</th>
                    <td>______________________</td>
                    <td>______________________</td>
                    <td>______________________</td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <th>Date:</th>
                    <td>______________________</td>
                    <td>______________________</td>
                    <td>______________________</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>
<style>
    .con {
        background-color: white;
        padding: 20px;
    }

    tfoot>tr>td,
    tfoot>tr>th {
        border: 0;
    }





    table,
    th,
    td {
        border: 1px solid black;
        padding: 12px;
    }

    .q {
        visibility: hidden;
    }

    .chk_box {
        border: 1px solid black;

    }

    @media print {

        @page {
            margin: 0;
        }

        .chk_box {
            color: currentColor !important;
        }

        .main-footer {
            display: none;
        }

        .btn {
            display: none;
        }

        .con {
            padding: 0;
        }

        table {
            margin-left: 0;
            margin-right: 0;
        }

        table,
        th,
        td {
            padding: 5px;
            font-size: 10px;
            width: 100%;
        }

        .main-header {
            display: none;
        }

        .q {
            visibility: hidden;
        }
    }
</style>
<script>
    $('.editable').focusout(() => {
        console.log('qweqwe')
    })
</script>