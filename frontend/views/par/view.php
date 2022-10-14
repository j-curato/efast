<?php

use barcode\barcode\BarcodeGenerator;
use Da\QrCode\QrCode;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Par */

$this->title = $model->par_number;
$this->params['breadcrumbs'][] = ['label' => 'Pars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$d = new DateTime($model->property->date);
$dateAquired = $d->format('F d, Y');
$description = preg_replace('#\[n\]#', "<br>", $model->property->description);

$article = $model->property->article;
// $book = $model->property->book->name;
$par_number = $model->par_number;
$property_number = $stickerDetails['property_number'];
$old_par_number = $stickerDetails['old_par_number'];
$par_date = !empty($model->date) ? $model->date : '';
$location = !empty($model->location) ? $model->location : '';
$province = $stickerDetails['province'];
$accountable_officer = $stickerDetails['accountable_officer'];
$actual_user = $stickerDetails['actual_user'];
$issued_by = $stickerDetails['issued_by'];
$remarks = $stickerDetails['remarks'];

$quantity = intval($model->property->quantity);
$aquisition_amount = floatval($model->property->acquisition_amount);
$total_cost = $quantity * $aquisition_amount;
$recieved_by = '';
//   strtoupper($model->employee->f_name) . ' ' . strtoupper(substr($model->employee->m_name, 0, 1)) . '. ' . strtoupper($model->employee->l_name);
$recieved_by_position = '';
// $model->employee->position;
$property_custodian = '';
//  strtoupper($model->property->employee->f_name) . ' ' . strtoupper(substr($model->property->employee->m_name, 0, 1)) . '. ' .
//     strtoupper($model->property->employee->l_name);
$property_custodian_position = '';
// $model->property->employee->position



$qrcode_filename = Yii::$app->request->baseurl . "/frontend/views/par/qrcodes/$model->par_number.png";
// GENERATE QR CODE
if (!file_exists($qrcode_filename)) {
    $text = $model->id;
    $path = 'qr_codes';
    $qrCode = (new QrCode($text))
        ->setSize(150);
    header('Content-Type: ' . $qrCode->getContentType());
    $base_path =  \Yii::getAlias('@webroot');
    $qrCode->writeFile($base_path . "/frontend/views/par/qrcodes/$model->par_number.png");
}
// GENERATE BARCODE
$optionsArray = array(
    'elementId' => 'barcodeTarget', /* div or canvas id*/
    'value' => $model->id, /* value for EAN 13 be careful to set right values for each barcode type */
    'type' => 'code128',/*supported types ean8, ean13, upc, std25, int25, code11, code39, code93, code128, codabar, msi, datamatrix*/

);
BarcodeGenerator::widget($optionsArray);
?>
<div class="par-view">


    <div class="container">
        <p class=''>
            <?= Html::button('<i class="glyphicon glyphicon-pencil"></i> Update', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=par/update&id=' . $model->id), 'id' => 'modalButtoncreate', 'class' => 'btn btn-primary', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>

            <?php
            if (!empty($model->propertyCard->pc_number)) {

                $t = Yii::$app->request->baseUrl . "/index.php?r=property-card/view&id={$model->propertyCard->id}";
                echo  Html::a('Property Card Link', $t, ['class' => 'btn btn-link ']);
            }
            ?>
            <button id="print_sticker" type="button" class="btn btn-success">Print Sticker</button>
            <button id="print_form" type="button" class="btn btn-warning">Print Form</button>
        </p>
        <div class="cut_line sticker_table">

            <table id="sticker_table">
                <tr>

                    <td style="text-align: left;" class="no-border">

                        <span style="width: 100%;">
                            <?php echo Html::img("@web/frontend/web/dti3.png", ['style' => 'width:50px']) ?>
                        </span>

                        <span>
                            <?php echo Html::img($qrcode_filename, ['class' => 'qr', 'style' => 'float:right']) ?>
                        </span>
                    </td>
                    <td colspan='2' class="no-border" style="text-align: right;">
                        <div id="barcodeTarget" class="barcodeTarget"></div>
                    </td>

                </tr>

                <tr>

                    <th>Property No.: </th>
                    <td colspan=''>
                        <?= $property_number ?>
                    </td>
                </tr>
                <tr>
                    <th>New PAR No.: </th>
                    <td colspan=''>
                        <?= $par_number ?>
                    </td>
                </tr>
                <tr>
                    <th>Old PAR No.</th>
                    <td colspan=''>
                        <?= $old_par_number ?>
                    </td>
                </tr>
                <tr>
                    <th>PAR Date.</th>
                    <td colspan=''>
                        <?= $par_date ?>
                    </td>
                </tr>
                <tr>
                    <th>Office</th>
                    <td colspan=''>
                        <?= $province ?>
                    </td>
                </tr>
                <tr>
                    <th>Location <br>
                        <span style="font-size: 8px;"> (Provincial Office, indicate the Division,
                            Name of City/Municipality in the case
                            of NC or others,
                            Name of Cooperator in the case of SSF)
                        </span>
                    </th>
                    <td colspan=''>
                        <?= $location ?>
                    </td>
                </tr>
                <tr>
                    <th>Received by (Accountable Officer)</th>
                    <td colspan=''>
                        <?= $accountable_officer ?>
                    </td>
                </tr>
                <tr>
                    <th>Received by (JO/COS) (leave blank if not JO/COS)</th>
                    <td colspan=''>
                        <?= $actual_user ?>
                    </td>
                </tr>
                <tr>
                    <th>Issued by (Property Officer)</th>
                    <td colspan=''>
                        <?= $issued_by ?>
                    </td>
                </tr>
                <tr>
                    <th>Serviceable / Unserviceable</th>
                    <td colspan=''>
                        <?= $remarks ?>
                    </td>
                </tr>

            </table>
        </div>
        <?php

        if ($model->property->acquisition_amount < 1500) {





        ?>
            <table class="par_form">
                <tbody>
                    <tr>
                        <th colspan="7" class="center no-border">
                            INVENTORY CUSTODIAN SLIP
                            <br>
                            <br>
                            <br>

                        </th>
                    </tr>
                    <tr>
                        <th colspan="7" class="no-border">

                            <span>Entity Name :</span>
                            <span>Department of Trade and Industry - Caraga</span>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="5" class="no-border">

                            <span>Fund Cluster :</span>
                            <span><?php echo $book ?></span>
                        </th>
                        <th colspan="2" class="no-border">
                            <span>ICS No :</span>
                            <span><?php echo $par_number ?></span>
                        </th>
                    </tr>
                    <tr>
                        <th rowspan="2">Quantity</th>
                        <th rowspan="2">Unit</th>
                        <th colspan="2">Amount</th>
                        <th rowspan="2">Description</th>
                        <th rowspan="2">Inventory Item No.</th>
                        <th rowspan="2">Estimated Useful Life</th>
                    </tr>
                    <tr>
                        <td>Unit Cost</td>
                        <td>Total Cost</td>
                    </tr>
                    <?php

                    echo "<tr>
                        <td>{$model->property->quantity}</td>
                        <td>{$model->property->unitOfMeasure->unit_of_measure}</td>
                        <td class='amount'>" . number_format($model->property->acquisition_amount, 2) . "</td>
                        <td class='amount'>" . number_format($total_cost, 2) . "</td>
                        <td>
                        <span style='font-weight:bold;'>{$article}</span>
                        <br>
                        <span style='font-style:italic;'>$description</span>
                        
                        </td>
                        <td>{$model->property->property_number}</td>
                        <td class='center'>{$model->property->estimated_life}</td>
                    </tr>";
                    for ($i = 0; $i < 4; $i++) {
                        echo "<tr>
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
                        <td colspan="4">Recieved from:</td>
                        <td colspan="3">Recieved by:</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="center">
                            <br>
                            <br>
                            <br>
                            <span style="text-decoration: underline;font-weight:bold;"><?php echo $property_custodian ?></span>
                            <br>
                            <span>Signature Over Printed Name</span>
                            <br>
                            <span>Supply Officer / DTI-Caraga Regional Office</span>
                            <br>
                            <span>Position/Office</span>
                            <br>
                            <br>
                            <span style="border-bottom: 1px solid black;"><?php echo DateTime::createFromFormat('Y-m-d', $model->date)->format('F d, Y') ?></span>
                            <br>
                            <span>Date</span>
                        </td>
                        <td colspan="3" class="center">
                            <br>
                            <br>
                            <br>
                            <span style="text-decoration: underline;font-weight:bold;"><?php echo $recieved_by ?></span>
                            <br>
                            <span>Signature Over Printed Name</span>
                            <br>
                            <span><?php echo $recieved_by_position ?></span>
                            <br>
                            <span>Position/Office</span>
                            <br>
                            <br>
                            <span>_____________________</span>
                            <br>
                            <span>Date</span>
                        </td>
                    </tr>


                </tbody>
            </table>
        <?php
        } else {
        ?>
            <table class="par_form">

                <tbody>
                    <tr>
                        <th colspan="6" style="text-align: center;">
                            <br>
                            PROPERTY ACKNOWLEDGMENT RECIEPT
                            <br>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="6">
                            <span>
                                Entity Name:
                            </span>
                            <span>Department of Trade and Industry - Caraga</span>

                        </th>

                    </tr>
                    <tr>
                        <th colspan="3">
                            <span>Fund Cluster:</span>
                            <span><?php
                                    // echo $model->property->book->name;
                                    ?></span>
                        </th>
                        <th colspan="3">
                            <span>PAR No:</span>
                            <span><?php echo $model->par_number; ?></span>
                        </th>
                    </tr>
                    <tr>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Description</th>
                        <th>Property Number</th>
                        <th>Date Acquired</th>
                        <th>Amount</th>
                    </tr>
                    <?php

                    // echo "<tr>
                    //     <td>{$model->property->quantity}</td>
                    //     <td>{$model->property->unitOfMeasure->unit_of_measure}</td>
                    //     <td>
                    //     <span style='font-weight:bold;'>{$article}</span>
                    //     <br>
                    //     <span style='font-style:italic;'>$description</span>

                    //     </td>
                    //     <td>{$model->property->property_number}</td>
                    //     <td>{$dateAquired}</td>
                    //     <td class='amount'>" . number_format($model->property->acquisition_amount, 2) . "</td>
                    // </tr>";
                    for ($i = 0; $i < 4; $i++) {
                        echo "<tr>
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
                        <th class='foot' colspan="3">Received By</th>
                        <th class='foot' colspan="3">Issued By</th>
                    </tr>
                    <tr>
                        <th class='foot' colspan="3">
                            <span style="text-decoration:underline">
                                <span><?php
                                        // echo strtoupper($model->employee->f_name); 
                                        ?>
                                </span>
                                <span><?php
                                        // echo strtoupper(substr($model->employee->m_name, 0, 1)); 
                                        ?>
                                    . </span>
                                <span><?php
                                        // echo strtoupper($model->employee->l_name); 
                                        ?>
                                </span>
                            </span>
                            <br>
                            <span> Signatue over Printed Name of End User</span>
                        </th>
                        <th class='foot' colspan="3">
                            <span style="text-decoration:underline">
                                <span><?php
                                        // echo strtoupper($model->property->employee->f_name); 
                                        ?>
                                </span>
                                <span><?php
                                        // echo strtoupper(substr($model->property->employee->m_name, 0, 1)); 
                                        ?>
                                    .
                                </span>
                                <span><?php
                                        // echo strtoupper($model->property->employee->l_name);
                                        ?>
                                </span>
                            </span>
                            <br>
                            <span> Signatue over Printed Name of Supply and/or </span>
                            <br>
                            <span>Property Custodian</span>
                        </th>

                    </tr>
                    <tr>
                        <th class='foot' colspan="3">
                            <span style="text-decoration: underline;"><?php
                                                                        // echo strtoupper($model->employee->position);
                                                                        ?></span>
                            <br>
                            <span>Position</span>
                        </th>
                        <th class='foot' colspan="3">

                            <span style="text-decoration: underline;"><?php
                                                                        // echo strtoupper($model->property->employee->position);
                                                                        ?></span>
                            <br>
                            <span>Position</span>
                        </th>

                    </tr>
                    <tr>
                        <th class='foot' colspan="3" style="border-bottom: 1px solid black;">

                            <span>_______________</span>
                            <br>
                            <span>Date</span>
                        </th>
                        <th class='foot' colspan="3" style="border-bottom: 1px solid black;">
                            <span>_______________</span>
                            <br>
                            <span>Date</span>
                        </th>

                    </tr>
                    <!-- ACTUAL USER -->
                    <?php

                    if (!empty($model->actual_user)) {
                        $user_name = "{$model->actualUser->f_name} {$model->actualUser->m_name[0]}. {$model->actualUser->l_name} ";
                        echo "        <tr>
                    <th class='foot' colspan='3' style='text-align:center;padding-top:5rem;border-bottom: 1px solid black;'>
                        <span style='text-decoration:underline'>
                            <span>$user_name </span>
                           
                        </span>
                        <br>
                        <span> Signatue over Printed Name of Actual User</span>
                    </th>
                    <th class='foot' colspan='3' style='text-align:center;padding-top:5rem;border-bottom: 1px solid black;'>
                
                    </th>


                </tr>";
                    }
                    ?>




                </tbody>
            </table>
        <?php } ?>
    </div>

</div>
<style>
    th,
    td {
        padding: 12px;
        border: 1px solid black;
    }

    .no-border {
        border: none;
    }

    .center {
        text-align: center;
    }

    .amount {
        text-align: right;
    }

    .par_form {
        margin-left: auto;
        margin-right: auto;
        width: 100%;
    }

    .foot {
        text-align: center;
        border-bottom: 0;
        border-top: 0;
    }

    .container {
        background-color: white;
        padding: 20px;
    }

    .cut_line {
        max-width: 100%;
        position: relative;
        padding: 3px;
        border: 1px solid black;
        float: left;
        margin-bottom: 5px;
    }

    #sticker_table td {
        text-transform: uppercase;
        padding: 5px;
        font-size: 10px;

    }

    #sticker_table th {
        padding: 5px;
        max-width: 250px;
        font-size: 10px;
    }


    #sticker_table {
        margin: 20px;
        border: 0;
    }


    .qr {
        margin-left: auto;
        width: 50px;
    }

    @media print {
        .btn {
            display: none;
        }

        .container {
            background-color: white;
            padding: 0;
            border: none;
        }


        th,
        td {
            padding: 1rem;
            border: 1px solid black;
        }

        table {
            padding: 0;
        }

        .main-footer {
            display: none;

        }

        #sticker_table td {
            max-width: 160px;
            min-width: 160px;
            font-size: 10px;
            padding: 2px;
        }

        #sticker_table th {
            font-size: 10px;
            padding: 2px;
            max-width: 160px;
            min-width: 160px;
        }

        .cut_line {
            padding: .5px;
            border: 2px solid black;
            float: left;
        }

    }
</style>

<style>
    @media print {

        /* #detail_table,
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

        table {
            border: 1px solid black;
        } */


    }
</style>
<script>
    $(document).ready(function() {

        $("#print_sticker").click((e) => {
            e.preventDefault()

            $(".par_form").hide()
            window.print()
            $(".par_form").show()
        })
        $("#print_form").click((e) => {
            e.preventDefault()

            $(".sticker_table").hide()
            window.print()
            $(".sticker_table").show()
        })
    })
</script>