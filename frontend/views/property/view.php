<?php

use Da\QrCode\QrCode;
use barcode\barcode\BarcodeGenerator as BarcodeGenerator;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Property */

$this->title = $model->property_number;
$this->params['breadcrumbs'][] = ['label' => 'Properties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$qrcode_filename = Yii::$app->request->baseurl . "/frontend/views/property/qrcodes/$model->property_number.png";
// GENERATE QR CODE
if (!file_exists($qrcode_filename)) {
    $text = $model->id;
    $path = 'qr_codes';
    $qrCode = (new QrCode($text))
        ->setSize(150);
    header('Content-Type: ' . $qrCode->getContentType());
    $base_path =  \Yii::getAlias('@webroot');
    $qrCode->writeFile($base_path . "/frontend/views/property/qrcodes/$model->property_number.png");
}
// GENERATE BARCODE
$optionsArray = array(
    'elementId' => 'barcodeTarget', /* div or canvas id*/
    'value' => $model->id, /* value for EAN 13 be careful to set right values for each barcode type */
    'type' => 'code128',/*supported types ean8, ean13, upc, std25, int25, code11, code39, code93, code128, codabar, msi, datamatrix*/

);
BarcodeGenerator::widget($optionsArray);
?>
<div class="property-view">
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
    <div class="container">
        <table id="qr_table">
            <tbody>
                <tr>


                    <td style="text-align: left;" class="no-border">

                        <span style="width: 100%;">
                            <?php echo Html::img("@web/frontend/web/dti3.png", ['style' => 'width:50px']) ?>
                        </span>
                    </td>
                    <td class="no-border ">
                        <span>
                            <?php echo Html::img($qrcode_filename, ['class' => 'qr', 'style' => 'float-right']) ?>
                        </span>
                    </td>
                    <td class="no-border" style="text-align: right;">
                        <div id="barcodeTarget" class="barcodeTarget"></div>
                    </td>

                </tr>


                <tr>
                    <th>Property No.: </th>
                    <td colspan="3"><?php echo $model->property_number ?></td>
                </tr>
                <tr>
                    <th>SSF/Non-SSF</th>
                    <td colspan="3"><?php echo $model->ppe_type ?></td>
                </tr>
                <tr>
                    <th>SSF SP No.</th>
                    <td colspan="3"><?php echo !empty($model->ssfCategory->ssf_number) ? $model->ssfCategory->ssf_number : '' ?></td>
                </tr>
                <tr>
                    <th>Date Acquired</th>
                    <td colspan="3"><?php echo !empty($model->date) ? DateTime::createFromFormat('Y-m-d', $model->date)->format('F d, Y') : '' ?></td>
                </tr>
                <tr>
                    <th>Article</th>
                    <td colspan="3"><?php echo $model->article ?></td>
                </tr>
                <tr>
                    <th>ITEM/BRAND/MODEL</th>
                    <td colspan="3"><?php echo $model->description ?></td>
                </tr>
                <tr>
                    <th>Serial Number</th>
                    <td colspan="3"><?php echo $model->serial_number ?></td>
                </tr>
                <tr>
                    <th>Total Acquisition Amount</th>
                    <td colspan="3"><?php echo $model->acquisition_amount  ?></td>
                </tr>

            </tbody>
        </table>

        <?= DetailView::widget([
            'model' => $model,
            'id' => 'detail_table',
            'attributes' => [
                'property_number',

                [
                    'label' => 'Book',
                    'attribute' => 'book.name'
                ],
                [
                    'label' => 'Unit of Measure',
                    'attribute' => 'unitOfMeasure.unit_of_measure'
                ],

                'iar_number',
                'article',
                [
                    'label' => 'Description',
                    'value' => function ($model) {
                        return     preg_replace('#\[n\]#', "\n", $model->description);
                    }
                ],

                'model',
                'serial_number',
                'quantity',
                'acquisition_amount'
            ],
        ]) ?>
    </div>


</div>
<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/css/customCss.css", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<style>
    .container {
        background-color: white;
    }

    table,
    th,
    td {
        padding: 12px;
        border: 1px solid black;
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
        margin-left: auto;
        width: 50px;
    }



    @media print {

        #detail_table,
        .main-footer,
        .btn {
            display: none;
        }

        td,
        th {
            font-size: 10px;
            padding: 2px;
        }

        td {
            max-width: 350px;
            min-width: 100px;
        }


    }
</style>