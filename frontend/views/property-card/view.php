<?php

use Da\QrCode\QrCode;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PropertyCard */

$this->title = $model->pc_number;
$this->params['breadcrumbs'][] = ['label' => 'Property Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="property-card-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->pc_number], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->pc_number], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php
    $filename = Yii::$app->request->baseurl . "/qr_codes/$model->pc_number.png";

    if (!file_exists($filename)) {
        $text = $model->pc_number;
        $path = 'qr_codes';
        $qrCode = (new QrCode($text))
            ->setSize(150);
        header('Content-Type: ' . $qrCode->getContentType());
        $base_path =  \Yii::getAlias('@webroot');
        $qrCode->writeFile($base_path . "/qr_codes/$text.png");
    }
    ?>


    <table id="qr_table">
        <tbody>
            <tr>
                <td rowspan="7" style="padding-left:12px;padding-right:12px"> <?php echo Html::img("@web/qr_codes/$model->pc_number.png", ['class' => 'qr']) ?></td>
            </tr>
            <tr>
                <td>Property No.: </td>
                <td><?php echo $model->pc_number ?></td>
            </tr>
            <tr>
                <td>Serial No.:</td>
                <td><?php echo $model->par->property->serial_number ?></td>
            </tr>
            <tr>
                <td>Acquisition Cost</td>
                <td><?php echo $model->par->property->acquisition_amount ?></td>

            </tr>
            <tr>
                <td>Acquisition Date</td>
                <td><?php echo $model->par->property->date ?></td>
            </tr>
            <tr>
                <td>Person Accountable</td>
                <td><?php echo $model->par->employee->l_name ?></td>

            </tr>
            <tr>
                <td>Validation Signature</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <table id="pc_form">
        <tr>
            <th colspan="8" style="text-align: center;">
                PROPERTY CARD
            </th>
        </tr>
        <tr>
            <th colspan="6">
                <span>Entity Name:</span>
            </th>
            <th colspan="2">
                <span>Fund Cluster:</span>
            </th>
        </tr>
        <tr>
            <th colspan="6">
                <span>Property, Plant and Equipment :</span>
            </th>
            <th colspan="2">
                <span> Property Number:</span>
                <span><?php echo $model->pc_number ?></span>

            </th>
        </tr>
        <tr>
            <th colspan="6"><span>Description</span></th>
            <th colspan="2"></th>
        </tr>
        <tr>
            <th rowspan="2">Date</th>
            <th rowspan="2">Reference/ PAR No.</th>
            <th>Receipt</th>
            <th colspan="3">Issue/Transfer/ Disposal</th>
            <th rowspan="2">Amount</th>
            <th rowspan="2">Remarks</th>

        </tr>
        <tr>
            <th>Qty.</th>
            <th>Qty.</th>
            <th>Office/Officer</th>
            <th>Qty.</th>
        </tr>
        <tbody>
            <tr>
                <th></th>
            </tr>
        </tbody>
    </table>

</div>
<style>
    table,
    th,
    td {
        padding: 12px;
        border: 1px solid black;
    }

    #pc_form {
        width: 100%;

    }

    .property-card-view {
        background-color: white;
        padding: 15px;

    }

    #qr_table td {
        text-transform: uppercase;
        padding: 5px;

    }

    #qr_table {
        margin: 20px;
        border: 0;
    }


    .qr {
        width: 100px;
        height: 100px;
    }
</style>