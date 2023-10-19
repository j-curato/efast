<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Iirup */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Iirups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$acctbl_ofr = MyHelper::getEmployee($model->fk_acctbl_ofr, 'one');
$approved_by = MyHelper::getEmployee($model->fk_approved_by, 'one');



?>
<div class="iirup-view panel">

    <p>
        <?= Yii::$app->user->can('update_iirup') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate btn btn-primary']) : '' ?>
    </p>

    <table>
        <tr>
            <td colspan="18" class="no-bdr " style="text-align: right;"> <?= $model->serial_number ?></td>
        </tr>
        <tr>
            <th colspan="18" class="no-bdr">
                <h5><b>INVENTORY AND INSPECTION REPORT OF UNSERVICEABLE PROPERTY</b></h5>
                <br>
                <span> As at </span>
                <span><u><?= DateTime::createFromFormat('Y-m', $model->reporting_period)->format('F Y') ?></u></span>
            </th>
        </tr>
        <tr>
            <th class="no-bdr" colspan="2">
                Entity Name:
                <br>
                <br>
            </th>
            <td colspan="13" class="no-bdr" style="text-align: left;">
                <u>DEPARTMENT OF TRADE AND INDUSTRY
                    <br>
                    <br>
                </u>

            </td>
            <!-- <th class="no-bdr">Fund CLuster: </th>
            <td colspan="2" class="no-bdr">____________</td> -->
        </tr>
        <tr>
            <td colspan="5" class="no-bdr">
                <span><u><?= strtoupper($acctbl_ofr['employee_name']) ?></u></span><br>
                <span><i>(Name of Accountable Officer)</i></span>
            </td>
            <td colspan="5" class="no-bdr">
                <span><u><?= strtoupper($acctbl_ofr['position']) ?></u></span><br>
                <span><i>(Designation)</i></span>
            </td>
            <td colspan="" class="no-bdr">
                <span>______________</span><br>
                <span><i>(Station)</i></span>
            </td>
            <td colspan="5" class="no-bdr"></td>
        </tr>
        <tr>
            <th colspan="10">INVENTORY</th>
            <th colspan="8">INSPECTION and DISPOSAL</th>
        </tr>
        <tr>
            <th rowspan="2">Date Acquired</th>
            <th rowspan="2">Particulars/Articles</th>
            <th rowspan="2">Property No.</th>
            <th rowspan="2">Qty</th>
            <th rowspan="2">Unit Cost</th>
            <th rowspan="2">Total Cost</th>
            <th rowspan="2">Accumulated Depreciation</th>
            <th rowspan="2">Accumulated Impairement Losses</th>
            <th rowspan="2">Carrying Amount</th>
            <th rowspan="2">Remarks</th>
            <th colspan="5">DISPOSAL</th>
            <th rowspan="2">Appraised Value</th>
            <th colspan="2">RECORD OF SALES</th>
        </tr>
        <tr>
            <th style="max-width: 50px;">Sale</th>
            <th>Transfer</th>
            <th>Destruction</th>
            <th>Other (Specify)</th>
            <th>Total</th>
            <th style="min-width: 70px;">OR No.</th>
            <th style="min-width: 70px;">Amount</th>
        </tr>
        <?php

        foreach ($items as $itm) {

            echo "<tr>
               <td>{$itm['date_acquired']}</td>
               <td>{$itm['article_name']} - {$itm['description']}</td>
               <td>{$itm['property_number']}</td>
               <td>1</td>
               <td>" . number_format($itm['acquisition_amount'], 2) . "</td>
               <td>" . number_format($itm['acquisition_amount'], 2) . "</td>
               <td>" . number_format($itm['mnthly_depreciation'], 2) . "</td>
               <td></td>
               <td>" . number_format($itm['amount'], 2) . "</td>
               <td>{$itm['par_number']} - {$itm['book_name']}</td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               </tr>";
        }
        ?>
        <tr>
            <td colspan="10" class="no-bdr-btm">
                <br>
                I HEREBY request inspection adn disposition, pursuan to Section 79 of PD 1445, of the property enumerated above.

            </td>
            <td colspan="3" class="no-bdr-btm no-bdr-rgt">
                <br>
                I CERTIFY that I have inspected each and every article enumerated in this report, and that the disposition made thereof was, in my judgment,
                the best for the public interest.
            </td>
            <td colspan="5" class="no-bdr-btm no-bdr-lft">
                <br>
                I CERTIFY that I ahve witnessed the disposition of the art icles enumerated on this report this _____ day of ________________________, __________.
            </td>
        </tr>
        <tr>
            <td colspan="5" class="no-bdr-top no-bdr-rgt">
                <br>
                <span style="float:left"> Requested by: </span>
                <br>
                <br>
                <br>
                <span><u><b><?= strtoupper($acctbl_ofr['employee_name']) ?></b></u></span>
                <br>
                <span>(Signature ov er Printed Name of Accountable Officer)</span>
                <br>
                <br>
                <br>
                <span><u><?= strtoupper($acctbl_ofr['position']) ?></u></span>
                <br>
                (Designation of Accountable Officer)
            </td>
            <td colspan="5" class="no-bdr-top no-bdr-lft">
                <br>
                <span style="float:left"> Approved by:</span>
                <br>
                <br>
                <br>
                <span><u><b><?= strtoupper($approved_by['employee_name']) ?></b></u></span>
                <br>
                <span>(Signature ov er Printed Name of Authorized Official)</span>
                <br>
                <br>
                <br>
                <span><u><?= strtoupper($approved_by['position']) ?></u></span>
                <br>
                (Designation of Authorized Official)
            </td>
            <td colspan="3" class="no-bdr-top no-bdr-rgt">
                <br>
                <span>_________________________</span>
                <br>
                <span>(Signature ov er Printed Name of Inspection Officer)</span>
            </td>
            <td colspan="5" class="no-bdr-top no-bdr-lft">
                <br>
                <span>_________________________</span>
                <br>
                <span>(Signature ov er Printed Name of Witness)</span>
            </td>
        </tr>

    </table>



</div>
<style>
    .panel {
        padding: 2rem;
    }

    th,
    td {
        border: 1px solid black;
        padding: 10px;
        text-align: center;
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

    .no-bdr-lft {
        border-left: 0;
    }

    .no-bdr-rgt {
        border-right: 0;
    }

    @media print {

        .btn,
        .main-footer {
            display: none;
        }

        .panel {
            padding: 0;
        }

        th,
        td {
            padding: 3px;
            font-size: 10px;
        }
    }
</style>