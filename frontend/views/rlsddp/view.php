<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Rlsddp */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Rlsddps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$acc_ofr = MyHelper::getEmployee($model->fk_acctbl_offr, 'one');
$spvr =   MyHelper::getEmployee($model->fk_supvr, 'one');
?>
<div class="rlsddp-view  container">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>


    <table class=" ">

        <tr>
            <th colspan="6" class="ctr no-bdr">
                <h4>REPORT OF LOST, STOLEN, DAMAGED OR DESTROYED PROPERTY</h4>
            </th>
        </tr>
        <tr>
            <th class="no-bdr ctr">Entity Name :</th>
            <td class="no-bdr" colspan="2">
                DEPARTMENT OF TRADE AND INDUSTRY - CARAGA
            </td>
            <td class='no-bdr ctr' colspan="3"><b>Fund Cluster :</b> _____________</td>
        </tr>
        <tr>
            <th class='ctr '>Department / Office:</th>
            <td colspan="2"><?= $model->office->office_name ?></td>
            <td class='ctr' colspan="3"><b>RLSDDP No. : </b><?= $model->serial_number ?></td>
        </tr>
        <tr>
            <th class='ctr'>Accountable Officer:</th>
            <td colspan="2"><?= strtoupper($acc_ofr['employee_name']) ?></td>
            <td class='ctr' colspan="3"><b>RLSDDP Date :</b> <?= DateTime::createFromFormat('Y-m-d', $model->date)->format('F d, Y')  ?></td>
        </tr>
        <tr>
            <th class='ctr'>Designation: </th>
            <td colspan="2"><?= $acc_ofr['position'] ?></td>
            <td colspan="3"></td>
        </tr>
        <tr>
            <th class='ctr'>Police Notified: </th>
            <td class="ctr  no-rgt-bdr " colspan="2">
                <span class='chk_box'> <?= $model->is_blottered ? '&#10003;' : '' ?> </span>
                <span>Yes</span>

                <span class='chk_box' style="margin-left:30px">
                    <?= !$model->is_blottered ? '&#10003;' : '' ?></span>
                <span>No</span>
            </td>
            <td colspan="3" class='no-lft-bdr'>

                <span>Police Station:
                    <u><?= !empty($model->police_station) ? $model->police_station : '' ?></u>
                </span>
                <br>

                <span>Date:
                    <u>
                        <?= !empty($model->blotter_date) ? DateTime::createFromFormat('Y-m-d', $model->blotter_date)->format('F d, Y') : '' ?>
                    </u>
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="6" class="no-bdr"> Status of Property:(check applicable box)</td>
        </tr>
        <tr>
            <td class="no-bdr">

            </td>
            <!--    '1' => 'Lost',
                    '2' => 'Stolen',
                    '3' => 'Damaged',
                    '4' => 'Destroyed', -->
            <td class="no-bdr">
                <span class='chk_box'><?= intval($model->status) ===  1 ? '&#10003;' : '' ?> </span> <span>Lost</span>
                <br>
                <br>
                <span class='chk_box'><?= intval($model->status) ===  2 ? '&#10003;' : '' ?> </span> <span>Stolen</span>
            </td>
            <td class="no-bdr">
                <span class='chk_box'><?= intval($model->status) === 3 ? '&#10003;' : '' ?> </span> <span>Damage</span>
                <br>
                <br>
                <span class='chk_box'><?= intval($model->status) ===  4 ? '&#10003;' : '' ?> </span> <span>Destroyed</span>
            </td>
            <td class="no-bdr"></td>
        </tr>
        <tr>
            <th class='ctr'>Property No.</th>
            <th class='ctr'>PAR No.</th>
            <th class='ctr'>PAR Date</th>
            <th class='ctr' colspan="2">Description</th>
            <th class='ctr'>Acquisition Cost</th>
        </tr>
        <?php
        foreach ($items as $itm) {
            echo "<tr>
                <td  class='ctr' > {$itm['property_number']}</td>
                <td  class='ctr' > {$itm['par_number']}</td>
                <td  class='ctr' > {$itm['par_date']}</td>
                <td   class='ctr' colspan='2'> {$itm['article']}\n {$itm['description']}</td>
                <td  class='ctr' >" . number_format($itm['acquisition_amount'], 2) . "</td>
            </tr>";
        }
        for ($i = 0; $i < 4; $i++) {
            echo "<tr>
            <td><br></td>
            <td><br></td>
            <td><br></td>
            <td colspan='2'></td>
            <td></td>
            </tr>";
        }
        ?>
        <tr>
            <td colspan="6">
                <b>Circumstances:</b>
                <span><?= $model->circumstances ?></span>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="no-bdr-btm"> I herby Certify that the item/s and circumstances stated above are true and correct.</td>
            <td colspan="3" class="no-bdr-btm"> Noted by:</td>
        </tr>
        <tr>
            <td colspan="3" class="ctr  no-bdr-top-btm " style="min-width: 350px;">

                <br>
                <u> <b><?= $acc_ofr['employee_name'] ?></b></u>
                <br>
                <?= strtoupper($acc_ofr['position']) ?>
                <br>
                Signature over Printed Name of the Accountable Officer
                <br>
                <br>
                _______________
                <br>
                Date

            </td>
            <td colspan="3" class="no-bdr-top-btm ctr">

                <br>
                <u> <b><?= strtoupper($spvr['employee_name']) ?></b></u>
                <br>
                <?= $spvr['position'] ?>
                <br>
                Signature over Printed Name of the Immediate Supervisor
                <br>
                <br>
                _______________
                <br>
                Date
            </td>
        </tr>
        <tr>
            <td colspan="3" class="no-bdr-top">
                <span> Government Issued ID:</span><span>____________________</span>
                <br>
                <span> ID No. :</span><span>____________________</span>
                <br>
                <span> Date Issued :</span><span>____________________</span>
            </td>
            <td colspan="3" class="no-bdr-top"></td>
        </tr>
        <tr>
            <td colspan="6" class="no-bdr-btm">
                <br>
                <b>SUBSCRIBED AND SWORN</b> to before me this ____ day of ________________, affiant ehibiting the above government issued identification card.

            </td>
        </tr>
        <tr>
            <td colspan="3" class="no-bdr">
                <span>Doc. No. :</span><span>____________</span><br>
                <span>Page No. :</span><span>____________</span><br>
                <span>Book. No. :</span><span>____________</span><br>
                <span>Series of :</span><span>____________</span><br>
            </td>
            <td colspan="3" class="no-bdr ctr">
                <span>_____________________</span><br>
                <span> Notary Public</span>

            </td>
        </tr>
    </table>

</div>
<style>
    .container {
        padding: 2rem;
        background-color: white;
    }

    table {
        width: 100%;

    }

    .txt-rgt {
        text-align: right;
    }

    .ctr {
        text-align: center;
    }

    table,
    th,
    td {
        border: 1px solid black;
        padding: 8px;
    }

    .chk_box {
        border: 1px solid black;
        min-width: 20px;
        min-height: 22px;
        max-width: 20px;
        max-height: 22px;
        display: inline-block;
        padding: 2px;

        vertical-align: bottom;
    }

    .no-bdr {
        border: 0;
    }

    .no-bdr-top {
        border-top: 0;
    }

    .no-bdr-btm {
        border-bottom: 0;
    }

    .no-bdr-top-btm {
        border-top: 0;
        border-bottom: 0;
    }

    .no-lft-bdr {
        border-left: 0;
    }

    .no-rgt-bdr {
        border-right: 0;
    }

    @media print {

        th,
        td {
            padding: 5px;
            font-size: 11px;
        }

        .main-footer,
        .btn {
            display: none;
        }
    }
</style>