<?php

use app\components\helpers\MyHelper;
use app\models\Office;
use app\models\PropertyArticles;
use barcode\barcode\BarcodeGenerator;
use Da\QrCode\QrCode;
use yii\helpers\Html;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $model app\models\Par */

function getEmployee($id)
{
    return  Yii::$app->db->createCommand("SELECT employee_name,position FROM employee_search_view WHERE  employee_id = :id")
        ->bindValue(':id', $id)
        ->queryOne();
}
$this->title = $model->par_number;
$this->params['breadcrumbs'][] = ['label' => 'Pars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$d = new DateTime($model->property->date);
$dateAquired = $d->format('F d, Y');
$description = preg_replace('#\[n\]#', "<br>", $model->property->description);

$article = !empty($model->property->fk_property_article_id) ? PropertyArticles::findOne($model->property->fk_property_article_id)->article_name : $model->property->article;
$book = !empty($model->property->book->name) ? $model->property->book->name : '';
$par_number = $model->par_number;
$property_number = $model->property->property_number;
$old_par_number = ''; //$stickerDetails['old_par_number'];
$par_date = !empty($model->date) ? $model->date : '';
$location = !empty($model->locations->location) ? $model->locations->location : '';
$province = !empty($stickerDetails['province']) ? $stickerDetails['province'] : '';


$quantity = intval($model->property->quantity);
$aquisition_amount = floatval($model->property->acquisition_amount);
$total_cost = $quantity * $aquisition_amount;

// $received_by = getEmployee($model->fk_received_by);
$received_by = MyHelper::getEmployee($model->fk_received_by, 'one');
$actual_user = !empty($model->fk_actual_user) ? MyHelper::getEmployee($model->fk_actual_user, 'one') : '';
$issued_by = MyHelper::getEmployee($model->fk_issued_by_id, 'one');
$office = '';


if (!empty($model->fk_office_id)) {
    $office = Office::findOne($model->fk_office_id)->office_name;
}

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

$quantity = !empty($model->property->quantity) ? $model->property->quantity : '';
$unit_of_measure = !empty($model->property->unitOfMeasure->unit_of_measure) ? $model->property->unitOfMeasure->unit_of_measure : '';
?>
<div class="par-view">


    <div class="container">
        <p class=''>
            <?= Yii::$app->user->can('update_par') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'lrgModal btn btn-primary']) : '' ?>
            <?= Html::button('<i class="fa fa-print"></i> Print Form', ['id' => 'print_form', 'class' => 'btn btn-warning']) ?>
            <?= Html::a('Property', ['property/view', 'id' => $model->fk_property_id], ['class' => 'btn btn-link']) ?>
            <?= Html::a('PC', ['property-card/view', 'id' => $model->pc->id], ['class' => 'btn btn-link']) ?>
            <?= !empty($model->fk_ptr_id) ? Html::a('PTR', ['ptr/view', 'id' => $model->fk_ptr_id], ['class' => 'btn btn-link']) : '' ?>

        </p>
        <!-- <div class="cut_line sticker_table">

            <table id="sticker_table">
                <tr>
                    <td style="text-align: left;" class="no-border">
                        <span style="width: 100%;">
                            <?php echo Html::img("@web/frontend/web/dti3.png", ['style' => 'width:50px']) ?>
                        </span>
                    </td>
                    <td class="no-border">
                        <span>
                            <?php echo Html::img($qrcode_filename, ['class' => 'qr', 'style' => 'float:left']) ?>
                        </span>
                    </td>
                    <td colspan='2' class="no-border" style="text-align: right;">
                        <div id="barcodeTarget" class="barcodeTarget"></div>
                    </td>
                </tr>
                <tr>
                    <th>Property No.: </th>
                    <td colspan='2'>
                        <?= $property_number ?>
                    </td>
                </tr>
                <tr>
                    <th>New PAR No.: </th>
                    <td colspan='2'>
                        <?= $par_number ?>
                    </td>
                </tr>
                <tr>
                    <th>Old PAR No.</th>
                    <td colspan='2'>
                        <?= $old_par_number ?>
                    </td>
                </tr>
                <tr>
                    <th>PAR Date.</th>
                    <td colspan='2'>
                        <?= $par_date ?>
                    </td>
                </tr>
                <tr>
                    <th>Office</th>
                    <td colspan='2'>
                        <?= $office ?>
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
                    <td colspan='2'>
                        <?= $location ?>
                    </td>
                </tr>
                <tr>
                    <th>Received by (Accountable Officer)</th>
                    <td colspan='2'>
                        <?= $received_by['employee_name'] ?>
                    </td>
                </tr>
                <tr>
                    <th>Received by (JO/COS) (leave blank if not JO/COS)</th>
                    <td colspan='2'>
                        <?= !empty($actual_user['employee_name']) ? $actual_user['employee_name'] : '' ?>
                    </td>
                </tr>
                <tr>
                    <th>Issued by (Property Officer)</th>
                    <td colspan='2'>
                        <?= $issued_by['employee_name'] ?>

                    </td>
                </tr>
                <tr>
                    <th>Serviceable / Unserviceable</th>
                    <td colspan='2'>
                        <?= $model->is_unserviceable ? 'UnServiceable' : 'Serviceable' ?>
                    </td>
                </tr>

            </table>
        </div> -->
        <?php

        if ($model->property->acquisition_amount < 1500) {
        ?>
            <table class="par_form" style="width: 100%;">
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
                        <th class="center" rowspan="2">Quantity</th>
                        <th class="center" rowspan="2">Unit</th>
                        <th class="center" class="center" colspan="2">Amount</th>
                        <th class="center" rowspan="2">Description</th>
                        <th class="center" rowspan="2">Inventory Item No.</th>
                        <th class="center" rowspan="2">Estimated Useful Life</th>
                    </tr>
                    <tr>
                        <th class="center">Unit Cost</th>
                        <th class="center">Total Cost</th>
                    </tr>
                    <?php

                    echo "<tr>
                        <td>{$quantity}</td>
                        <td>{$unit_of_measure}</td>
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
                        <th colspan="4">Recieved from:</th>
                        <th colspan="3">Recieved by:</th>
                    </tr>
                    <tr>
                        <td colspan="4" class="center">
                            <br>
                            <br>
                            <br>
                            <span style="text-decoration: underline;font-weight:bold;">
                                <?php
                                echo $issued_by['employee_name'];
                                ?></span>
                            <br>
                            <span>Signature Over Printed Name</span>
                            <br>
                            <span>Supply Officer / DTI-Caraga Regional Office</span>
                            <br>
                            <span>Position/Office</span>
                            <br>
                            <br>
                            <span style="border-bottom: 1px solid black;">
                                <?php
                                echo $par_date;
                                ?></span>
                            <br>
                            <span>Date</span>
                        </td>
                        <td colspan="3" class="center">
                            <br>
                            <br>
                            <br>
                            <span style="text-decoration: underline;font-weight:bold;">
                                <?php
                                echo $received_by['employee_name'];
                                ?></span>
                            <br>
                            <span>Signature Over Printed Name</span>
                            <br>
                            <br>
                            <?php
                            echo $received_by['position'];
                            ?>
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
            <table class="par_form" style="width: 100%;">

                <tbody>
                    <tr>
                        <th colspan="6" class="center">
                            <br>
                            PROPERTY ACKNOWLEDGMENT RECEIPT
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
                                    echo $book;
                                    ?></span>
                        </th>
                        <th colspan="3">
                            <span>PAR No:</span>
                            <span><?php echo $model->par_number; ?></span>
                        </th>
                    </tr>
                    <tr>
                        <th class="center">Quantity</th>
                        <th class="center">Unit</th>
                        <th class="center">Description</th>
                        <th class="center">Property Number</th>
                        <th class="center">Date Acquired</th>
                        <th class="center">Amount</th>
                    </tr>
                    <?php

                    echo "<tr>
                        <td>{$quantity}</td>
                        <td>{$unit_of_measure}</td>
                        <td>
                        <span style='font-weight:bold;'>{$article}</span>
                        <br>
                        <span style='font-style:italic;'>$description</span>

                        </td>
                        <td>{$model->property->property_number}</td>
                        <td>{$dateAquired}</td>
                        <td class='amount'>" . number_format($model->property->acquisition_amount, 2) . "</td>
                    </tr>";
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
                        <th class='foot no-border' colspan="3">Received By</th>
                        <th class='foot no-border' colspan="3">Issued By</th>
                    </tr>
                    <tr>
                        <td class='foot no-border center' colspan="3">
                            <span style="text-decoration:underline">
                                <b><?= $received_by['employee_name'] ?></b>
                            </span>
                            <br>

                            <span> Signatue over Printed Name of End User</span>
                        </td>
                        <td class='foot no-border center' colspan="3">
                            <span style="text-decoration:underline">
                                <b><?php
                                    echo $issued_by['employee_name'];
                                    ?>
                                </b>

                            </span>
                            <br>
                            <span> Signatue over Printed Name of Supply and/or </span>
                            <br>
                            <span>Property Custodian</span>
                        </td>

                    </tr>
                    <tr>
                        <td class='foot no-border center' colspan="3">
                            <span style="text-decoration: underline;">
                                <?= $received_by['position'] ?>
                            </span>
                            <br>
                            <span>Position</span>
                        </td>
                        <td class='foot no-border center' colspan="3">

                            <span style="text-decoration: underline;">
                                <?php
                                echo $issued_by['position'];
                                ?></span>
                            <br>
                            <span>Position</span>
                        </td>

                    </tr>
                    <tr>
                        <td class='foot no-border center' colspan="3" style="border-bottom: 1px solid black;">

                            <span>_______________</span>
                            <br>
                            <span>Date</span>
                        </td>
                        <td class='foot no-border center' colspan="3" style="border-bottom: 1px solid black;">
                            <span>_______________</span>
                            <br>
                            <span>Date</span>
                        </td>

                    </tr>
                    <!-- ACTUAL USER -->
                    <?php if (!empty($actual_user['employee_name'])) { ?>

                        <tr>
                            <td class='foot no-border' colspan='3' style='text-align:center;padding-top:5rem;border-bottom: 1px solid black;'>
                                <span style='text-decoration:underline'>
                                    <b> <span><?= $actual_user['employee_name'] ?></span></b>
                                </span>
                                <br>
                                <span> Signatue over Printed Name of Actual User</span>
                                <br>
                                <br>
                                <br>
                            </td>
                            <th class='foot no-border' colspan='3' style='text-align:center;padding-top:5rem;border-bottom: 1px solid black;'>

                            </th>


                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>

</div>
<style>
    .container {
        background-color: white;
        padding: 3rem;
    }

    .center {
        text-align: center;
    }

    .no-border {
        border: 0;
    }

    .cut_line {
        max-width: 100%;
        position: relative;
        padding: 1px;
        border: 1px solid black;
        float: left;
        margin-bottom: 15px;
    }

    .center {
        text-align: center;
    }

    table,
    th,
    td {
        padding: 12px;
        border: 1px solid black;
    }


    #sticker_table td {
        text-transform: uppercase;
        padding: 5px;

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


        .main-footer {
            display: none;

        }

        #sticker_table th {
            font-size: 10px;
            padding: 2px;
        }

        #sticker_table td {
            max-width: 150px;
            min-width: 150px;
            font-size: 10px;
            padding: 2px;
        }

        #sticker_table {
            border: 1px solid black;
            max-width: 150px;
            min-width: 150px;
        }

        .cut_line {
            padding: .5px;
            border: 2px solid black;
        }

        .par_form {
            width: 100%;
        }

    }
</style>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => JqueryAsset::class]);
?>

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