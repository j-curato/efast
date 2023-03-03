<?php

use Da\QrCode\QrCode;
use yii\helpers\Html;
use barcode\barcode\BarcodeGenerator as BarcodeGenerator;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PropertyCard */

$this->title = 'Stickers';
$this->params['breadcrumbs'][] = ['label' => 'Property Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="property-card-view container">


    <?= Html::beginForm(['print-pc'], 'post') ?>
    <div class="row filter-row" style="margin: 2rem;">
        <div class="col-sm-3">
            <?= Html::label('Reporting Period', 'reporting_period') ?>
            <?= DatePicker::widget([
                'name' => 'reporting_period',
                'pluginOptions' => [
                    'format' => 'yyyy-mm',
                    'minViewMode' => 'months',
                    'autoclose' => true
                ]
            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= Html::submitButton('Filter', ['class' => 'btn btn-success', 'style' => 'margin-top:2.5rem']) ?>
            <?= Html::button('Reset', ['class' => 'btn btn-primary', 'style' => 'margin-top:2.5rem', 'id' => 'reset']) ?>
            <?= Html::button('<i class="fa fa-print"></i> Print', ['class' => 'btn btn-warning', 'style' => 'margin-top:2.5rem', 'id' => 'print']) ?>
        </div>
    </div>
    <?= Html::endForm() ?>
    <?php



    $cnt = 1;
    foreach ($items as $idx => $item) {
        $base_path =  \Yii::getAlias('@webroot');
        $qrLoc = "/frontend/views/property-card/qrcodes" . '/' . $item['pc_num'] . ".png";
        $qrFileName = $base_path . $qrLoc;
        if (!file_exists($qrFileName)) {

            $text = $item['pc_id'];
            $path = 'qr_codes';
            $qrCode = (new QrCode($text))
                ->setSize(150);
            header('Content-Type: ' . $qrCode->getContentType());
            $qrCode->writeFile($qrFileName);
        }
        $optionsArray = array(
            'elementId' => 'showBarcode' . $idx, /* div or canvas id*/
            'value' => $item['pc_id'], /* value for EAN 13 be careful to set right values for each barcode type */
            'type' => 'code128',/*supported types ean8, ean13, upc, std25, int25, code11, code39, code93, code128, codabar, msi, datamatrix*/

        );
        BarcodeGenerator::widget($optionsArray);

    ?>
        <div class="cut_line">
            <table class="sticker_tbl">

                <tr>
                    <td style="text-align: center;" class="no-border">
                        <?php echo Html::img("@web/frontend/web/dti3.png", ['style' => 'width:50px']) ?>
                    </td>
                    <td class="no-border">

                        <div id="showBarcode<?= $idx ?>" class="barcodeTarget"></div>
                    </td>
                    <td colspan='2' class="no-border" style="text-align: right;">
                        <span>
                            <?php
                            echo Html::img("@web" . $qrLoc, ['class' => 'qr', 'style' => 'width:50px'])
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>PC No.: </th>
                    <td colspan='2'><?= $item['pc_num'] ?></td>
                </tr>
                <tr>
                    <th>Property No.: </th>
                    <td colspan='2'><?= $item['property_number'] ?></td>
                </tr>
                <tr>
                    <th>Article: </th>
                    <td colspan='2'><?= !empty($item['article_name']) ? $item['article_name'] : $item['article'] ?></td>

                </tr>
                <tr>
                    <th>Description </th>
                    <td colspan='2'><?= $item['description'] ?></td>

                </tr>
                <tr>
                    <th>PAR No.: </th>
                    <td colspan='2'><?= $item['par_number'] ?></td>
                </tr>

                <tr>
                    <th>PAR Date.</th>
                    <td colspan='2'><?= DateTime::createFromFormat('Y-m-d', $item['par_date'])->format('F d, Y') ?></td>
                </tr>
                <tr>
                    <th>Office</th>
                    <td colspan='2'><?= $item['office_name'] ?></td>
                </tr>
                <tr>
                    <th>Location <br>
                        <span style="font-size: 8px;"> (Provincial Office, indicate the Division,
                            Name of City/Municipality in the case
                            of NC or others,
                            Name of Cooperator in the case of SSF)
                        </span>
                    </th>
                    <td colspan='2'><?= $item['location'] ?></td>
                </tr>
                <tr>
                    <th>Received by (Accountable Officer)</th>
                    <td colspan='2'><?= $item['rcv_by'] ?></td>
                </tr>
                <tr>
                    <th>Actual User (JO/COS) (leave blank if not JO/COS)</th>
                    <td colspan='2'><?= $item['act_usr'] ?></td>
                </tr>
                <tr>
                    <th>Issued by (Property Officer)</th>
                    <td colspan='2'><?= $item['isd_by'] ?></td>
                </tr>
                <tr>
                    <th>Serviceable / Unserviceable</th>
                    <td colspan='2'><?= $item['is_unserviceable'] ? 'UnServiceable' : 'Serviceable' ?></td>
                </tr>

            </table>
        </div>
    <?php
        if ($cnt == 6) {
            echo ' <div class="page-break"></div>';
            $cnt = 0;
        }
        $cnt++;
    } ?>

</div>
<style>
    .container {
        background-color: white;
        padding: 2rem;
    }

    .cut_line {
        max-width: 100%;
        position: relative;
        padding: 2rem;
        border: 1px solid black;
        float: left;
    }

    .sticker_tbl th,
    .sticker_tbl td {
        border: 1px solid black;
        padding: .5rem;
    }

    @media print {

        .btn,
        .main-footer {
            display: none;
        }

        .sticker_tbl th {
            font-size: 10px;
            padding: 2px;
        }

        .sticker_tbl td,
        .sticker_tbl th {
            max-width: 120px;
            min-width: 120px;
            font-size: 8px;
            padding: 2px;
        }

        .sticker_tbl {
            border: 1px solid black;

            max-width: 120px;
            min-width: 120px;
        }

        .cut_line {
            padding: 1rem;
            border: 2px solid black;
            margin: 3px;
        }

        .page-break {
            page-break-after: always;
        }

        .filter-row {
            display: none;
        }
    }
</style>

<script>
    $(document).ready(() => {
        $('#reset').click(() => {
            window.location.href = window.location.pathname + '?r=property-card/print-pc';
        })
        $('#print').click(() => {
            window.print()
        })
    })
</script>