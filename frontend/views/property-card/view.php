<?php

use Da\QrCode\QrCode;
use yii\helpers\Html;
use barcode\barcode\BarcodeGenerator as BarcodeGenerator;


/* @var $this yii\web\View */
/* @var $model app\models\PropertyCard */

$this->title = $model->pc_number;
$this->params['breadcrumbs'][] = ['label' => 'Property Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="property-card-view">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->pc_number], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->pc_number], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?php
        if (!empty($model->par_number)) {
            $t = yii::$app->request->baseUrl . "/index.php?r=par/view&id={$model->par_number}";
            echo  Html::a('PAR Link', $t, ['class' => 'btn btn-success ']);
        }
        ?>
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

    <?php


    $optionsArray = array(
        'elementId' => 'showBarcode', /* div or canvas id*/
        'value' => $model->pc_number, /* value for EAN 13 be careful to set right values for each barcode type */
        'type' => 'code128',/*supported types ean8, ean13, upc, std25, int25, code11, code39, code93, code128, codabar, msi, datamatrix*/

    );
    BarcodeGenerator::widget($optionsArray);
    ?>

    <table id="qr_table">
        <tbody>
            <tr>
                <td rowspan="7">

                    <span style="width: 100%;">
                        <?php echo Html::img("@web/frontend/web/dti3.png", ['style' => 'width:100px;height:100px']) ?>
                    </span>
                    <br>
                    <span>
                        <?php echo Html::img("@web/qr_codes/$model->pc_number.png", ['class' => 'qr']) ?>
                    </span>
                    <br>
                    <br>
                    <span id="showBarcode"></span>
                </td>
            </tr>


            <tr>
                <th>Property No.: </th>
                <td><?php echo $model->pc_number ?></td>
            </tr>
            <tr>
                <th>Serial No.:</th>
                <td><?php echo $model->par->property->serial_number ?></td>
            </tr>
            <tr>
                <th>Acquisition Cost</th>
                <td><?php echo $model->par->property->acquisition_amount ?></td>

            </tr>
            <tr>
                <th>Acquisition Date</th>
                <td><?php echo $model->par->property->date ?></td>
            </tr>
            <tr>
                <th>Person Accountable</th>
                <td><?php
                    $name = "{$model->par->employee->f_name} {$model->par->employee->m_name[0]}. {$model->par->employee->l_name} ";
                    echo $name;
                    ?></td>

            </tr>
            <tr>
                <th>Validation Signature</th>
                <td></td>
            </tr>
        </tbody>
    </table>
    <table id="pc_form">
        <tr>
            <th colspan="8" style="text-align: center;border:0;">
                PROPERTY CARD
            </th>
        </tr>
        <tr>
            <th colspan="6" style="border:0;">
                <span>Entity Name:</span>
                <span>Department of Trade and Idustry (Caraga-XIII)</span>
            </th>
            <th colspan="2" style="border:0;">
                <span>Fund Cluster:</span>
                <span><?php echo $model->par->property->book->name; ?></span>
            </th>
        </tr>
        <tr>
            <th colspan="6">
                <span>Property, Plant and Equipment :</span>
                <span>
                    <?php
                    echo $model->par->property->article;

                    ?>
                </span>

            </th>
            <th colspan="2">
                <span> Property Number:</span>
                <span><?php echo $model->pc_number ?></span>

            </th>
        </tr>
        <tr>
            <th colspan="6">
                <span>Description: </span>
                <span>
                    <?php
                    echo $model->par->property->model;
                    echo ',';
                    echo $model->par->property->serial_number;
                    ?>
                </span>
            </th>
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
            <?php
            $transfer_quantity = '';
            $balance = 1;
            if (!empty($model->par->ptr->ptr_number)) {
                $transfer_quantity = 1;
                $balance -= 1;
            }
            $date = New DateTime($model->par->date);
            $remark = '';
            if (!empty($model->par->ptr)){
                $remark = $model->par->ptr->transfer_type_id;
            }
            echo "<tr>
                    <td>".$date->format('F d, Y')."</td>
                    <td>{$model->par->par_number}</td>
                    <td>1</td>
                    <td>$transfer_quantity</td>
                    <td></td>
                    <td>$balance</td>
                    <td></td>
                    <td></td>
                </tr>";

            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
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

    @media print {
        .btn {
            display: none;
        }

        .main-footer {
            display: none;
        }

        #qr_table {
            padding: 3px;
            margin-top: 0;

        }

        #qr_table td {
            padding: 8px;
            margin: 0;
            font-size: 10px;

        }


    }
</style>