<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PrRfq */

$this->title = 'RFQ  FORM';
$this->params['breadcrumbs'][] = ['label' => 'Pr Rfqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pr-rfq-view">





    <div class="container">

        <table>
            <thead>

                <tr>
                    <td colspan="7" class='bdr-none'>

                        <span>
                            Name of the Procuring Entity:

                        </span>
                        <span style="font-weight: bold;">
                            DEPARTMENT OF TRADE & INDUSTRY
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class='bdr-none'>

                        <span>
                            Name of the Project:
                        </span>
                        <span>


                        </span>


                    </td>
                </tr>
                <tr>
                    <td colspan="7" class='bdr-none'>
                        <span>
                            Location of the Project :
                        </span>
                        <span>
                        </span>
                    </td>

                </tr>

                <tr>
                    <th class='bdr-none' colspan="7" style="text-align: center;" class="bdr-top-none bdr-btm-none">REQUEST FOR QUOTATION</th>
                </tr>
                <tr>
                    <td class='bdr-none' colspan="2" style="text-align: left;padding-top:5rem;" class="bdr-top-none bdr-btm-none bdr-right-none bdr-btm-none">
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
                    <td class='bdr-none' colspan="4" style="border-right:none;" class="bdr-top-none bdr-left-none bdr-btm-none"></td>
                    <td class='bdr-none' colspan="2" style="padding-top:5rem;" class="bdr-top-none bdr-left-none bdr-btm-none">
                        <span>Date:________________________ </span>
                        <br>
                        <span>RFQ Number:_____________________</span>

                    </td>
                </tr>
                <tr>
                    <td class='bdr-none' colspan="2" style="text-align: left;padding-top:3rem;" class="bdr-top-none bdr-btm-none bdr-right-none bdr-btm-none">
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
                    <td class='bdr-none' colspan="6" style="padding-top:5rem;" class="bdr-top-none bdr-left-none bdr-btm-none">


                    </td>
                </tr>
                <tr>
                    <td class="bdr-none"></td>
                    <td colspan="4" class=" bdr-none">
                        <br>
                        <span>

                            &emsp;&emsp; Please quote your lowest price on the item/s listed below, subject to the General Conditions
                        </span>
                        <br>
                        stated herein. Submit your quotation duly signed by you or your representative not later than 3:00 PM on
                        _______________ in a sealed envelope. Late submission will not be accepted.
                        <br>
                        <span style="text-align: left;">

                        </span>
                    </td>

                </tr>
                <tr>
                    <td colspan="5" class="bdr-none"></td>
                    <td colspan="3" class='bdr-none' style="text-align:center;border-left:none">

                        <span>
                            <?php
                            // $rbac = Yii::$app->db->createCommand("SELECT 
                            // employee_search_view.employee_name,
                            // CONCAT(bac_position.position,'_', employee_search_view.employee_name) as pos
                            //  FROM bac_composition
                            // LEFT JOIN bac_composition_member ON bac_composition.id = bac_composition_member.bac_composition_id
                            // LEFT JOIN bac_position ON bac_composition_member.bac_position_id = bac_position.id
                            // LEFT JOIN employee_search_view ON bac_composition_member.employee_id = employee_search_view.employee_id
                            // WHERE bac_composition.id = :id")
                            //     ->bindValue(':id', )
                            //     ->queryAll();

                            // echo Select2::widget([
                            //     'data' => ArrayHelper::map($rbac, 'pos', 'employee_name'),
                            //     'name' => 'rbac',
                            //     'id' => 'rbac',
                            //     'pluginOptions' => [
                            //         'placeholder' => 'Select RBAC '
                            //     ]

                            // ]);

                            ?>
                            <br><br>
                            ______________________________
                        </span>
                        <br>
                        <span>
                            RBAC
                        </span>
                        <span id="rbac_position">

                        </span>

                    </td>
                </tr>
                <tr>

                    <td colspan="7" style="text-align: left;padding:0" class="bdr-none">


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
                    <th class='bordered tb-head' style="width: 50px;">Item No.</th>
                    <th class='bordered tb-head'>Description</th>
                    <th class='bordered tb-head'>Quantity</th>
                    <th class='bordered tb-head'>Unit of Measure</th>
                    <th class='bordered tb-head'>ABC Unit Price</th>
                    <th class='bordered tb-head'>ABC Total Price</th>
                    <th style="width: 10px;" class="bdr-none"></th>
                    <th class='bordered tb-head'>Bid Unit Price</th>
                    <th class='bordered tb-head' style="width: 4rem;">Bid Total Price</th>
                </tr>
            </thead>
            <tbody>

                <?php
                for ($i = 0; $i < 2; $i++) {
                    echo "   <tr>
                        <td class='bordered'></td>
                        <td class='bordered'> <span style='font-weight:bold'></span></br>
                            <span style='font-style:italic'></span>
                        </td>
                        <td class='bordered' style='text-align:center'></td>
                        <td style='text-align:center;'></td>
                        <td class='bordered amount'></td>
                        <td class='bordered amount'></td>
                        <td class='bdr-none'></td>
                        <td class='bordered'></td>
                        <td class='bordered' style='width: 130px;'></td>
                    </tr>";
                }

                ?>
                <tr>
                    <td class='bordered' colspan="6">

                        <span style="font-weight: bold;">


                        </span>
                    </td>

                    <td class='bdr-none'></td>
                    <td class='bordered '></td>
                    <td class='bordered bdr-left-none'></td>
                </tr>

                <tr>
                    <td class="bdr-none"></td>
                    <td colspan="7" class="bdr-none">
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

                        <span>________________________________</span>
                        <br>
                        <span style="width: 100%; float:right">Canvasser</span>
                    </td>
                    <td colspan="4" class="bdr-left-none bdr-none">

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





</div>

<style>
    .amount {
        text-align: right;

    }

    .tb-head {
        text-align: center;
    }

    .txt-center {
        text-align: center;
    }

    .bdr-top-none {
        border-top: none
    }

    .bdr-left-none {
        border-left: none
    }

    .bdr-right-none {
        border-right: none
    }

    .bdr-btm-none {
        border-bottom: none
    }

    .bdr-none {
        border: none
    }

    .container {
        background-color: white;
        padding: 3rem;
    }

    table {
        width: 100%;
    }

    th,
    td {
        border: 1px solid black;
        padding: 1rem;
    }

    .bordered {
        border: 1px solid black;
    }

    tbody>tr>td {
        border: 1px solid black;
    }



    ul {
        list-style-type: none;
    }

    @media print {
        .container {
            padding: 0;
        }

        #link_table {
            display: none;

        }

        .btn {
            display: none;
        }

        .main-footer {
            display: none;
        }

        th,
        td {
            padding: .5rem;
            font-size: 10px
        }

        .select2-selection__arrow {
            display: none;
        }

        .select2-container--krajee .select2-selection {
            /* -webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%); */
            box-shadow: none;
            background-color: #fff;
            border: none;
            border-radius: 0;
            color: #555555;
            font-size: 14px;
            outline: 0;
        }

        .select2-container--krajee .select2-selection--single {
            height: 5px;
            line-height: 1;
            padding: 0;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-right: 0;
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
    })
</script>