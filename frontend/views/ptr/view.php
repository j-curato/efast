<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
/* @var $this yii\web\View */
/* @var $model app\models\Ptr */

$this->title = $model->ptr_number;
$this->params['breadcrumbs'][] = ['label' => 'Ptrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$received_by = MyHelper::getEmployee($model->fk_received_by, 'one');
$actual_user = !empty($model->fk_actual_user) ? MyHelper::getEmployee($model->fk_actual_user, 'one') : '';
$issued_by = MyHelper::getEmployee($model->fk_issued_by, 'one');
$approved_by = MyHelper::getEmployee($model->fk_approved_by, 'one');
?>
<div class="ptr-view">
    <div class="container">
        <p>
            <?= Yii::$app->user->can('update_ptr') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'lrgModal btn btn-primary']) : '' ?>
            <?= Html::a('PAR', ['par/view', 'id' => $model->par->id], ['class' => 'btn btn-link ']) ?>
        </p>
        <table>
            <thead>
                <tr>
                    <th colspan="6" class="center no-bdr">
                        <h4>PROPERTY TRANSFER REPORT</h4>
                    </th>
                </tr>
                <tr>
                    <th colspan="2" class="">
                        <span>Entity Name : </span>
                    </th>
                    <th colspan="" class="center">
                        <span>Department of Trade and Industry</span>
                    </th>
                    <th colspan="" class="">
                        <span>Fund Cluster :</span>
                    </th>
                    <td class="">
                        <span></span>
                    </td>
                </tr>
                <tr>
                    <th colspan="2" class="">
                        <span>Fom Accountable Officer/Agency/Fund Cluster:</span>
                    </th>
                    <th colspan="" class="center">
                        <span><?= !empty($propertyDetails['from_officer']) ? $propertyDetails['from_officer'] : '' ?></span>
                        <br>
                    </th>
                    <th colspan="" class="">
                        <span>PTR No. :</span>
                    </th>
                    <td class="">
                        <span><?= $model->ptr_number ?></span>
                        <br>
                    </td>
                </tr>
                <tr>
                    <th colspan="2">
                        <span>To Accountable Officer/Agency/Fund CLuster:</span>

                    </th>
                    <th colspan="" class="center">
                        <span><?= $received_by['employee_name'] ?></span>
                    </th>
                    <th colspan="">
                        <span>Date: </span>

                    </th>
                    <td>
                        <span><?= DateTime::createFromFormat('Y-m-d', $model->date)->format('F d, Y') ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="no-bdr" colspan=""></td>
                    <th colspan="1" class="no-bdr">
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
                    <th colspan="" class='no-bdr'>
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
                    <th colspan="">Date Acquired</th>
                    <th>Property No.</th>
                    <th>Description</th>
                    <th>Amount </th>
                    <th>Condtion of PPE</th>
                </tr>

            </thead>
            <tbody>
                <?php
                echo "<tr>
                    <td colspan=''>{$propertyDetails['acquisition_date']}</td>
                    <td>{$propertyDetails['property_number']}</td>
                    <td><b>{$propertyDetails['article']}</b><br>{$propertyDetails['description']}</td>
                    <td>";
                echo number_format($propertyDetails['acquisition_amount'], 2);
                echo "</td><td>";
                echo $propertyDetails['is_unserviceable'] ? 'UnSeviceable' : 'Serviceable';
                echo "</td></tr>";
                for ($i = 0; $i < 3; $i++) {
                    echo "<tr>
                        <td colspan=''></td>
                        <td ><br></td>
                        <td ><br></td>
                        <td ><br></td>
                        <td ><br></td>
                     
                    </tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6">
                        <span>Reason for Transfer: <?= !empty($model->transfer_reason) ? $model->transfer_reason : '' ?></span>

                    </th>
                </tr>
                <tr>
                    <th colspan="2" class="no-bdr">Approved By:</th>
                    <th colspan="" class="no-bdr">Released/Issued By:</th>
                    <th colspan="2" class="no-bdr">Received By:</th>

                </tr>
                <!-- <tr>
                    <th>Signature:</th>
                    <td colspan="2" class="center no-bdr"><br><br>______________________</td>
                    <td colspan="" class="center no-bdr"><br><br>______________________</td>
                    <td class="center no-bdr" colspan="2"><br><br>______________________</td>
                </tr> -->
                <tr>
                    <!-- <th>Printed Name:</th> -->
                    <th class="center no-bdr underlined" colspan="2"><br><br><br><?= strtoupper($approved_by['employee_name']) ?></th>
                    <th class="center no-bdr underlined" colspan="" style="min-width: 250px;"><br><br><br><?= strtoupper($issued_by['employee_name']) ?></th>
                    <th class="center no-bdr underlined" colspan="2" style="min-width: 250px;"><br><br><br><?= strtoupper($received_by['employee_name']) ?></th>
                </tr>
                <tr>
                    <!-- <th>Designation:</th> -->
                    <td class="center no-bdr " colspan="2"><span class='underlined'><?= $approved_by['position'] ?> </span><br><span>Designation</span></td>
                    <td class="center no-bdr " colspan=""><span class='underlined'><?= $issued_by['position'] ?> </span><br><span>Designation</span></td>
                    <td class="center no-bdr " colspan="2"><span class='underlined'><?= $received_by['position'] ?> </span><br><span>Designation</span></td>
                </tr>
                <tr>
                    <!-- <th>Date:</th> -->
                    <td colspan='2' class="center no-bdr">______________________
                        <br><span>Date</span>
                    </td>
                    <td colspan='' class="center no-bdr">______________________ <br><span>Date</span></td>
                    <td class="center no-bdr" colspan="2">______________________ <br><span>Date</span></td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>
<style>
    .container {
        background-color: white;
        padding: 20px;
    }

    .underlined {
        text-decoration: underline;
    }

    .no-bdr {
        border: 0;
    }

    /* 
    tfoot>tr>td,
    tfoot>tr>th {
        border: 1px solid black;

    } */

    .center {
        text-align: center;
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

        .main-footer,
        .btn {
            display: none;
        }

        table,
        th,
        td {
            padding: 5px;
            font-size: 10px;
        }

        /* 
        .chk_box {
            color: currentColor !important;
        }

 

        .con {
            padding: 0;
        }

        table {
            margin-left: 0;
            margin-right: 0;
        }



        .main-header {
            display: none;
        }

        .q {
            visibility: hidden;
        } */
    }
</style>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]);
?>
<script>
    $('.editable').focusout(() => {
        console.log('qweqwe')
    })
</script>