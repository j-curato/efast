<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PrRfq */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pr Rfqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pr-rfq-view">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>


    <div class="container">
        <table>
            <thead>

                <tr>
                    <td colspan="7">

                        Name of the Procuring Entity: DEAPARTMENT OF TRADE & INDUSTRY
                    </td>
                </tr>
                <tr>
                    <td colspan="7">

                        <span>
                            Name of the Project:
                        </span>
                        <span>

                            <?php
                            echo $model->purchaseRequest->projectProcurement->title;
                            ?>
                        </span>


                    </td>
                </tr>
                <tr>
                    <td colspan="7">Location of the Project</td>
                </tr>

                <tr>
                    <th colspan="7" style="text-align: center;" class="bdr-top-none bdr-btm-none">REQUEST FOR QUOTAION</th>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: center;padding-top:5rem;" class="bdr-top-none bdr-btm-none bdr-right-none bdr-btm-none">
                        <span style='padding-top:20rem'>_________________________</span>
                        <br>
                        <span>
                            Company Name
                        </span>

                    </td>
                    <td colspan="2" style="border-right:none;" class="bdr-top-none bdr-left-none bdr-btm-none"></td>
                    <td colspan="1" style="padding-top:5rem;" class="bdr-top-none bdr-left-none bdr-btm-none">
                        <span>Date</span>
                        <span><?= $model->_date ?></span>
                        <br>
                        <span>RFQ Number</span>
                        <span><?= $model->rfq_number ?></span>

                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="bdr-top-none bdr-btm-none">Address</td>
                </tr>
                <tr>
                    <td colspan="7" class="txt-center">

                        Please quote your lowest price on the item/s listed below, subject to the General Conditions
                        <br>
                        stated herein. Submit your quotation duly signed by you or your representative not later than 3:00 PM on
                        <br>
                        December 27, 2021 in a sealed envelope. Late submission will not be accepted.
                    </td>

                </tr>
                <tr>
                    <td colspan="5" style="border-right: none;"></td>
                    <td colspan="2" style="text-align:center;border-left:none">

                        <?php

                        $bac = Yii::$app->db->createCommand("SELECT 
                        UPPER(employee_name)  as employee_name
                        FROM 
                        bac_composition_member 
                        LEFT JOIN bac_position ON bac_composition_member.bac_position_id  = bac_position.id
                        LEFT JOIN employee_search_view ON bac_composition_member.employee_id = employee_search_view.employee_id
                        WHERE
                        bac_composition_member.bac_composition_id = :bac_composition_id
                        AND bac_position.position = 'chairperson'
                         ")->bindValue(':bac_composition_id', $model->rbac_composition_id)
                            ->queryOne();

                        ?>

                        <br>
                        <span>
                            <?= $bac['employee_name'] ?>
                        </span>
                        <br>
                        <span>
                            RBAC Chairperson
                        </span>

                    </td>
                </tr>
                <tr>
                    <td colspan="7" style="text-align: left;">

                        <ul>
                            <li>All entries must be typewritten.</li>
                            <li>All supporting documents must be certified true copy by the bidder.</li>
                            <li>For Catering Services: Quotations must include list of choices for viand, dessert, and fruits.</li>
                            <li>Name of the project shall be printed outside of your envelope.</li>
                            <li>Price validity shall be for a period of ONE HUNDRED TWENTY (120) CALENDAR DAYS.</li>
                            <li>Quotations exceeding the Approved Budget for the contract shall be rejected.</li>
                            <li>All bids shall be inclusive of all applicable taxes.</li>
                            <li>Bid Total Price is subject to withholding of taxes.</li>
                            <li>The Supplier who will be declared by the BAC as having the Lowest Calculated and Responsive Bid</li>
                            <li>(LCRB) shall submit to the Procuring Entity the following documents before the issuance of NOA / </li>
                            <li>Purchase Order:</li>
                            <li> &emsp;a. Certified True Copy of Mayor's Permit;</li>
                            <li>&emsp;b. Certified True Copy of DTI/SEC/ or CDA Registration Certificate;</li>
                            <li>&emsp;c. Certified True Copies of Latest Income/Business Tax Return (for ABCs above P500,000.00);</li>
                            <li>&emsp;d. Certified True Copy of PhilGEPS Registration Certificate; and </li>
                            <li>&emsp;e. Notarized Omnibus Sworn Statement (for ABCs above P50,000.00).</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <th class='bordered'>Item No.</th>
                    <th class='bordered'>Description</th>
                    <th class='bordered'>Quantity</th>
                    <th class='bordered'>ABC Unit Price</th>
                    <th class='bordered'>ABC Total Price</th>
                    <th class='bordered'>Bid Unit Price</th>
                    <th class='bordered'>Bid Total Price</th>
                </tr>
            </thead>
            <tbody>

                <?php

                foreach ($model->rfqItems as $val) {
                    $specs = preg_replace('#\[n\]#', "<br>", $val->purchaseRequestItem->specification);
                    echo "<tr>
                        <td class='bordered'></td>
                        <td class='bordered'>Description</td>
                        <td class='bordered'>{$specs}</td>
                        <td class='bordered'>{$val->purchaseRequestItem->quantity}</td>
                        <td class='bordered'>{$val->purchaseRequestItem->unit_cost}</td>
                        <td class='bordered'>ABC Total Price</td>
                        <td class='bordered'>Bid Unit Price</td>
                   </tr>";
                }
                ?>
                <tr>
                    <td class='bordered'></td>
                    <td class='bordered'> <?= $model->purchaseRequest->purpose ?></td>
                    <td class='bordered'></td>
                    <td class='bordered'></td>
                    <td class='bordered'></td>
                    <td class='bordered'></td>
                    <td class='bordered'></td>
                </tr>
            </tbody>
        </table>
    </div>





</div>

<style>
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
    }
</style>