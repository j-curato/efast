<?php

use Da\QrCode\QrCode;
use yii\helpers\Html;
use barcode\barcode\BarcodeGenerator as BarcodeGenerator;


/* @var $this yii\web\View */
/* @var $model app\models\PropertyCard */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Property Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="property-card-view container">


    <p>
        <?= Html::a('PAR', ['par/view', 'id' => $model->fk_par_id], ['class' => 'btn btn-link']) ?>


        <button id="print_sticker" class="btn btn-primary"><i class="fa fa-print"></i> Print Sticker</button>
        <button id="print_form" class="btn btn-warning"><i class="fa fa-print"></i> Print Form</button>

    </p>

    <?php
    $filename = Yii::$app->request->baseurl . "/qr_codes/$model->serial_number.png";
    if (!file_exists($filename)) {
        $text = $model->id;
        $path = 'qr_codes';
        $qrCode = (new QrCode($text))
            ->setSize(150);
        header('Content-Type: ' . $qrCode->getContentType());
        $base_path =  \Yii::getAlias('@webroot');
        $qrCode->writeFile($base_path . "/qr_codes/$model->serial_number.png");
    }

    ?>

    <?php


    $optionsArray = array(
        'elementId' => 'showBarcode', /* div or canvas id*/
        'value' => $model->id, /* value for EAN 13 be careful to set right values for each barcode type */
        'type' => 'code128',/*supported types ean8, ean13, upc, std25, int25, code11, code39, code93, code128, codabar, msi, datamatrix*/

    );
    BarcodeGenerator::widget($optionsArray);
    ?>
    <div class="cut_line" id='sticker_table'>
        <table id="sticker_tbl">
            <tr>
                <td style="text-align: center;" class="no-border">
                    <?php echo Html::img("@web/frontend/web/dti3.png", ['style' => 'width:50px']) ?>
                </td>
                <td class="no-border">

                    <div id="showBarcode" class="barcodeTarget"></div>
                </td>
                <td colspan='2' class="no-border" style="text-align: right;">
                    <span>
                        <?php echo Html::img("@web/qr_codes/$model->serial_number.png", ['class' => 'qr', 'style' => 'width:50px']) ?>
                    </span>
                </td>
            </tr>
            <tr>
                <th>PC No.: </th>
                <td colspan='2'><?= $model->serial_number ?></td>
            </tr>
            <tr>
                <th>Property No.: </th>
                <td colspan='2'><?= $sticker_details['property_number'] ?></td>
            </tr>
            <tr>
                <th>Article: </th>
                <td colspan='2'><?= $sticker_details['article'] ?></td>

            </tr>
            <tr>
                <th>Description </th>
                <td colspan='2'><?= $sticker_details['description'] ?></td>

            </tr>
            <tr>
                <th>PAR No.: </th>
                <td colspan='2'><?= $sticker_details['par_number'] ?></td>
            </tr>

            <tr>
                <th>PAR Date.</th>
                <td colspan='2'><?= DateTime::createFromFormat('Y-m-d', $sticker_details['par_date'])->format('F d, Y') ?></td>
            </tr>
            <tr>
                <th>Office</th>
                <td colspan='2'><?= $sticker_details['office_name'] ?></td>
            </tr>
            <tr>
                <th>Location <br>
                    <span style="font-size: 8px;"> (Provincial Office, indicate the Division,
                        Name of City/Municipality in the case
                        of NC or others,
                        Name of Cooperator in the case of SSF)
                    </span>
                </th>
                <td colspan='2'><?= $sticker_details['location'] ?></td>
            </tr>
            <tr>
                <th>Received by (Accountable Officer)</th>
                <td colspan='2'><?= $sticker_details['rcv_by'] ?></td>
            </tr>
            <tr>
                <th>Actual User (JO/COS) (leave blank if not JO/COS)</th>
                <td colspan='2'><?= $sticker_details['act_usr'] ?></td>
            </tr>
            <tr>
                <th>Issued by (Property Officer)</th>
                <td colspan='2'><?= $sticker_details['isd_by'] ?></td>
            </tr>
            <tr>
                <th>Serviceable / Unserviceable</th>
                <td colspan='2'><?= $sticker_details['is_unserviceable'] ? 'UnServiceable' : 'Serviceable' ?></td>
            </tr>

        </table>
    </div>
    <!-- <table id="qr_table">
        <tbody>
            <tr>
                <td rowspan="7">

                    <span style="width: 100%;">
                        <?php echo Html::img("@web/frontend/web/dti3.png", ['style' => 'width:100px;height:100px']) ?>
                    </span>
                    <br>
                    <span>
                        <?php echo Html::img("@web/qr_codes/$model->serial_number.png", ['class' => 'qr']) ?>
                    </span>
                    <br>
                    <br>
                    <span id="showBarcode"></span>
                </td>
            </tr>


            <tr>
                <th>Property No.: </th>
                <td><?php !empty($model->serial_number) ? $model->serial_number : '' ?></td>
            </tr>
            <tr>
                <th>Serial No.:</th>
                <td><?php !empty($model->par->property->serial_number) ? $model->par->property->serial_number : '' ?></td>
            </tr>
            <tr>
                <th>Acquisition Cost</th>
                <td><?php !empty($model->par->property->acquisition_amount) ? $model->par->property->acquisition_amount : '' ?></td>

            </tr>
            <tr>
                <th>Acquisition Date</th>
                <td><?php !empty($model->par->property->date) ? $model->par->property->date : '' ?></td>
            </tr>
            <tr>
                <th>Person Accountable</th>
                <td><?php
                    // $name = "{$model->par->employee->f_name} {$model->par->employee->m_name[0]}. {$model->par->employee->l_name} ";
                    // echo $name;
                    ?></td>

            </tr>
            <tr>
                <th>Validation Signature</th>
                <td></td>
            </tr>
        </tbody>
    </table> -->
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
                <span><?php
                        //  echo $model->par->property->book->name; 
                        ?></span>
            </th>
        </tr>
        <tr>
            <th colspan="6">
                <span>Property, Plant and Equipment :</span>
                <span>
                    <?php
                    $description = preg_replace('#\[n\]#', ",", $model->par->property->article);

                    echo $description;

                    ?>
                </span>

            </th>
            <th colspan="2">
                <span> Property Number:</span>
                <span><?php echo $model->serial_number ?></span>

            </th>
        </tr>
        <tr>
            <th colspan="6">
                <span>Description: </span>
                <span>
                    <?php


                    echo $sticker_details['article'] . ',';
                    echo $sticker_details['description'];
                    echo ',';
                    echo $model->par->property->serial_number;
                    ?>
                </span>
            </th>
            <th colspan="2"></th>
        </tr>
        <tr>
            <th rowspan="2" class='ctr'>Date</th>
            <th rowspan="2" class='ctr'>Reference/ PAR No.</th>
            <th class='ctr'>Receipt</th>
            <th colspan="2" class="ctr">Issue/Transfer/ Disposal</th>
            <th class="ctr">Balance</th>
            <th rowspan="2" class="ctr">Amount</th>
            <th rowspan="2" class="ctr">Remarks</th>

        </tr>
        <tr>
            <th class='ctr'>Qty.</th>
            <th class='ctr'>Qty.</th>
            <th class='ctr'>Office/Officer</th>
            <th class='ctr'>Qty.</th>
        </tr>
        <tbody>
            <?php
            $transfer_quantity = '';
            $balance = 1;
            if (!empty($model->par->ptr->ptr_number)) {
                $transfer_quantity = 1;
                $balance -= 1;
            }
            $date = new DateTime($model->par->date);
            $remark = !empty($model->par->ptr->transferType->type) ? $model->par->ptr->transferType->type : '';
            echo "<tr>
                    <td>" . $date->format('F d, Y') . "</td>
                    <td>{$model->par->par_number}</td>
                    <td>1</td>
                    <td>$transfer_quantity</td>
                    <td></td>
                    <td>$balance</td>
                    <td></td>
                    <td></td>
                </tr>";

            for ($i = 0; $i < 10; $i++) {
                echo " <tr>
                    <td><br></td>
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

        </tbody>
    </table>

</div>
<style>
    .container {
        background-color: white;
        padding: 2rem;
    }

    .ctr {
        text-align: center;
    }

    .cut_line {
        max-width: 100%;
        position: relative;
        padding: 2rem;
        border: 1px solid black;
        float: left;
        margin-bottom: 2rem;
    }

    #sticker_tbl th,
    #sticker_tbl td {
        border: 1px solid black;
        padding: .5rem;
    }

    #pc_form {
        width: 100%;
        border: 1px solid black;
    }

    #pc_form th,
    #pc_form td {
        border: 1px solid black;
        padding: 5px;
    }

    @media print {

        .btn,
        .main-footer {
            display: none;
        }

        #sticker_tbl th {
            font-size: 10px;
            padding: 2px;
        }

        #sticker_tbl td,
        #sticker_tbl th {
            max-width: 120px;
            min-width: 120px;
            font-size: 8px;
            padding: 2px;
        }

        #sticker_tbl {
            border: 1px solid black;
            max-width: 120px;
            min-width: 120px;
        }

        .cut_line {
            padding: 1rem;
            border: 2px solid black;
        }


    }
</style>
<script>
    $(document).ready(() => {
        $("#print_sticker").click((e) => {
            e.preventDefault()
            $("#pc_form").hide()
            window.print()
            $("#pc_form").show()
        })
        $("#print_form").click((e) => {

            $("#sticker_table").hide()
            window.print()
            $("#sticker_table").show()
        })
    })
</script>