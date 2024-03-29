<?php

use aryelds\sweetalert\SweetAlertAsset;
use common\models\User;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PrRfq */

$this->title = $model->rfq_number;
$this->params['breadcrumbs'][] = ['label' => 'Pr Rfqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
SweetAlertAsset::register($this);

?>
<div class="pr-rfq-view">





    <div class="container p-2">
        <div class="card p-2">
            <span>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= !$model->is_cancelled ?
                    Html::a('Cancel', ['cancel', 'id' => $model->id], [
                        'class' => "btn btn-danger",
                        'id' => 'cancel'

                    ]) : '<span class="text-danger p-2">This RFQ is Cancelled</span>'
                ?>
                <?= Html::a('Purchase Request Link ', ['pr-purchase-request/view', 'id' => $model->pr_purchase_request_id], ['class' => 'btn btn-link ', 'style' => 'margin:3px']) ?>
            </span>

        </div>
        <div class="card p-2">

            <table class="rfq-table">
                <thead>

                    <tr>
                        <td colspan="9" class='bdr-none'>
                            <span>
                                Name of the Procuring Entity:
                            </span>
                            <b>
                                DEPARTMENT OF TRADE & INDUSTRY
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="9" class='bdr-none'>

                            <span>
                                Name of the Project:
                            </span>
                            <span>
                                <?= $model->purchaseRequest->purpose ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="9" class='bdr-none'>
                            <span>
                                Location of the Project :
                            </span>
                            <span>
                                <?= $model->project_location ?>
                            </span>
                        </td>

                    </tr>
                    <tr>
                        <th colspan="9" class="border-top-0 bdr-btm-none text-center bdr-none">REQUEST FOR QUOTATION</th>
                    </tr>
                    <tr>
                        <!-- <td class='bdr-none' colspan="2" style="text-align: left;padding-top:5rem;" class="border-top-0 bdr-btm-none bdr-right-none bdr-btm-none"> -->
                        <td class='bdr-none pt-5' colspan="2" style="padding-top:5rem;">
                            <span style='padding-top:20rem'>_____________________________</span>
                            <br>
                            <span>
                                &emsp;
                                &emsp;
                                &emsp;
                                &emsp;
                                Company Name
                            </span>
                        </td>
                        <td class='bdr-none' colspan="5" style="border-right:none;" class="border-top-0 bdr-left-none bdr-btm-none"></td>
                        <td class='bdr-none' colspan="3" style="padding-top:5rem;" class="border-top-0 bdr-left-none bdr-btm-none">
                            <span>Date: </span>
                            <span><?= DateTIme::createFromFormat('Y-m-d', $model->_date)->format('F d, Y') ?></span>
                            <br>
                            <span>RFQ Number:</span>
                            <span><?= $model->rfq_number ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td class='text-left ' colspan="2" style="padding-top:3rem;">
                            <span style='padding-top:20rem'>_____________________________</span>
                            <br>
                            <span>
                                &emsp;
                                &emsp;
                                &emsp;
                                &emsp;
                                Address
                            </span>

                        </td class='bdr-none'>
                        <td colspan="7"></td>
                    </tr>
                    <tr>
                        <?php
                        $deadline = DateTime::createFromFormat('Y-m-d H:i:s', $model->deadline);
                        $timeDeadline =   $deadline->format('h:i a ');
                        ?>
                        <td class="bdr-none"></td>
                        <td colspan="8" class=" bdr-none">
                            <br>
                            <span>
                                &emsp;&emsp; Please quote your lowest price on the item/s listed below, subject to the General Conditions
                            </span>
                            <br>
                            stated herein. Submit your quotation duly signed by you or your representative not later than
                            <?= $timeDeadline ?> on
                            <br>
                            <span class="text-left">
                                <?= $deadline->format('F d, Y') ?> in a sealed envelope. Late submission will not be accepted.
                            </span>
                        </td>

                    </tr>
                    <tr>
                        <td colspan="6" class="bdr-none"></td>
                        <td colspan="3" class='bdr-none' style="text-align:center;border-left:none">

                            <br>
                            <br>
                            <br>
                            <span style="text-decoration: underline;">


                                <?= Select2::widget([
                                    'data' => ArrayHelper::map($rbac, 'pos', 'employee_name'),
                                    'name' => 'rbac',
                                    'id' => 'rbac',
                                    'pluginOptions' => [
                                        'placeholder' => 'Select RBAC '
                                    ]
                                ]);
                                ?>
                            </span>
                            <br>
                            <span>
                                <?= strtolower(User::getUserDetails()->employee->office->office_name)  !== 'ro' ? 'BAC' : 'RBAC' ?>
                            </span>
                            <span id="rbac_position">

                            </span>

                        </td>
                    </tr>
                    <tr>

                        <td colspan="9" class="bdr-none text-left p-0">


                            <ul>
                                <li>Note:</li>
                                <li>1.All entries must be typewritten.</li>
                                <li>2.All supporting documents must be certified true copy by the bidder.</li>
                                <li>3.For Catering Services: Quotations must include list of choices for viand, dessert, and fruits.</li>
                                <li>4.Name of the project shall be printed outside of your envelope.</li>
                                <li>5.Price validity shall be for a period of ONE HUNDRED TWENTY (120) CALENDAR DAYS.</li>
                                <li>6.Quotations exceeding the Approved Budget for the contract shall be rejected.</li>
                                <li>7.All bids shall be inclusive of all applicable taxes.</li>
                                <li>8.Bid Total Price is subject to withholding of taxes.</li>
                                <li>9.The Supplier who will be declared by the BAC as having the Lowest Calculated and Responsive Bid</li>
                                <li> &nbsp;&nbsp;(LCRB) shall submit to the Procuring Entity the following documents before the issuance of NOA / </li>
                                <li>&nbsp;&nbsp;Purchase Order:</li>
                                <li>&emsp;&emsp;a. Certified True Copy of Mayor's Permit;</li>
                                <li>&emsp;&emsp;b. Certified True Copy of DTI/SEC/ or CDA Registration Certificate;</li>
                                <li>&emsp;&emsp;c. Certified True Copies of Latest Income/Business Tax Return (for ABCs above P500,000.00);</li>
                                <li>&emsp;&emsp;d. Certified True Copy of PhilGEPS Registration Certificate; and </li>
                                <li>&emsp;&emsp;e. Notarized Omnibus Sworn Statement (for ABCs above P50,000.00).</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <th class=' text-center border border-dark border border-dark' style="width: 50px;">Item No.</th>
                        <th class=' text-center border border-dark'>Description</th>
                        <th class=' text-center border border-dark'>Quantity</th>
                        <th class=' text-center border border-dark'>Unit of Measure</th>
                        <th class=' text-center border border-dark'>ABC Unit Price</th>
                        <th class=' text-center border border-dark'>ABC Total Price</th>
                        <th style="width: 10px;" class="bdr-none"></th>
                        <th class=' text-center border border-dark ' style="max-width: 5em;">Bid Unit Price</th>
                        <th class=' text-center border border-dark ' style="max-width: 5em;">Bid Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grandTotal = 0;
                    foreach ($model->getItems() as $index => $val) {
                        $specs = preg_replace('#\[n\]#', "<br>", $val['specification']);
                        $total_cost = intval($val['quantity']) * floatval($val['unit_cost']);
                        $grandTotal += $total_cost;
                        $i = $index + 1;
                        echo "<tr>
                        <td class='border border-dark'>$i</td>
                        <td class='border border-dark'> <span  style='font-weight:bold'>" . $val['stock_title'] . "</span></br>
                        <span style='font-style:italic'>" . "{$specs}</span></td>
                        <td class='border border-dark' style='text-align:center' >{$val['quantity']}</td>
                        <td style='text-align:center;' class='border border-dark text-center'>" . $val['unit_of_measure'] . "</td>
                        <td class='border border-dark text-right' >" . number_format($val['unit_cost'], 2) . "</td>
                        <td class='border border-dark text-right' >" . number_format($total_cost, 2) . "</td>
                        <td class='bdr-none'></td>
                        <td class='border border-dark'></td>
                        <td class='border border-dark'></td>
                   </tr>";
                    }
                    ?>
                    <tr>
                        <td class='border border-dark' colspan='5' style='text-align:center'><b>TOTAL</b></td>
                        <td class='border border-dark text-right'><?= number_format($grandTotal, 2) ?></td>
                        <td class='bdr-none'></td>
                        <td class='border border-dark'></td>
                        <td class='border border-dark'></td>
                    </tr>
                    <tr>
                        <td class='border border-dark' colspan="6">
                            <span style="font-weight: bold;">
                                <?= $model->purchaseRequest->purpose ?>
                            </span>
                        </td>

                        <td class='bdr-none'></td>
                        <td class='border border-dark '></td>
                        <td class='border border-dark bdr-left-none'></td>
                    </tr>

                    <tr>
                        <td class="bdr-none"></td>
                        <td colspan="8" class="bdr-none">
                            <br>
                            <span>After having carefully read and accepted your General Conditions, I/We quote you on the item</span>
                            <br>
                            <span>at prices noted above.</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;" class="bdr-none">
                            <span style="left:20px;">Canvassed by:</span>
                            <br>
                            <br>
                            <br>
                            <br>

                            <?php

                            if (!empty($model->employee_id)) {

                                $query = Yii::$app->db->createCommand("SELECT UPPER(employee_name)  as employee_name FROM employee_search_view WHERE employee_id = :id ")
                                    ->bindValue(':id', $model->employee_id)
                                    ->queryOne();
                                $employee  = !empty($query['employee_name']) ? $query['employee_name'] : '';
                                echo "<span style='margin-top:3rem;text-decoration:underline'>" . $employee . "</span>";
                            } else {

                                echo "  <span style='margin-top:3rem'>" . '______________________________  </span>';
                            }
                            ?>
                            <br>
                            <span style="width: 100%; float:right">Canvasser</span>
                        </td>
                        <td colspan="5" class="bdr-left-none bdr-none">

                        </td>
                        <td colspan="2" style="text-align: center;" class="bdr-none">
                            <br>
                            <span style="margin-top:3rem">
                                _________________________
                            </span>
                            <br>
                            <span style="font-style:italic">Printed Name/Signature</span>
                            <br>
                            <br>
                            <span style="margin-top:3rem">
                                _________________________
                            </span>
                            <br>
                            <span style="font-style:italic">Tel no./Cellphone No./Email Address</span>
                            <br>
                            <br>
                            <span style="margin-top:3rem">
                                _________________________
                            </span>
                            <br>
                            <span style="font-style:italic">Date</span>
                        </td>
                    </tr>

                </tbody>

            </table>
        </div>

        <?php if (Yii::$app->user->can('ro_procurement_admin') || YIi::$app->user->can('po_procurement_admin')) : ?>

            <div class="card p-2">
                <table id="link_table" class="table table-hover">

                    <thead>
                        <tr class="danger">
                            <th colspan="3" class="text-center">AOQ LINKS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($model->getAoqLinks() as $val) {
                            $isCancelled = $val['is_cancelled'] ? 'Cancelled' : '';
                            echo "<tr>
                            <td style='border:none;'>{$val['aoq_number']}</td>
                            <td style='border:none;'>" . Html::a('AOQ Link ', ['pr-aoq/view', 'id' => $val['id']], ['class' => 'btn btn-link ', 'style' => 'margin:3px']) . "</td>
                            <td style='border:none;'>$isCancelled</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </div>





</div>

<style>
    table {
        width: 100%;
    }

    th,
    td {
        padding: 1rem;
    }


    /* tbody>tr>td {
        border: 1px solid black;
    } */



    ul {
        list-style-type: none;
    }


    @media print {


        #link_table,
        .btn,
        .main-footer {
            display: none;

        }

        th,
        td {
            padding: 3px;
            font-size: 14px;
        }

        .select2-selection__arrow {
            display: none;
        }

        .select2-container--krajee-bs4 .select2-selection {
            /* -webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%); */
            box-shadow: none;
            background-color: #fff;
            border: none;
            border-radius: 0;
            color: #555555;
            font-size: 14px;
            outline: 0;
        }

        .select2-container--krajee-bs4 .select2-selection--single {
            height: 5px;
            line-height: 1;
            padding: 0;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-right: 0;
        }

        .select2-selection__rendered {
            color: red;
        }

    }
</style>
<script>
    $(document).ready(function() {
        $('#rbac').change(function() {
            const name = $(this).val().split('_')[0]
            const nameCapitalized = name.charAt(0).toUpperCase() + name.slice(1)
            $('#rbac_position').text(nameCapitalized)
        })
        $("#cancel").click((e) => {
            e.preventDefault();
            let ths = $(e.target)
            let link = ths.attr('href');
            swal({
                title: "Are you sure you want to " + ths.text() + " this RFQ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Confirm',
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: true,
                width: "500px",
                height: "500px",
            }, function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: link,
                        method: 'POST',
                        data: {
                            _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
                        },
                        success: function(response) {
                            const res = JSON.parse(response)
                            if (!res.error) {
                                swal({
                                    title: 'Success',
                                    type: 'success',
                                    button: false,
                                    timer: 3000,
                                }, function() {
                                    location.reload(true)
                                })
                            } else {
                                swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: res.message,
                                    button: false,
                                    timer: 5000,
                                })
                            }
                        },
                        error: function(error) {
                            console.error('Cancel failed:', error);
                        }
                    });
                }
            })

        });
    })
</script>