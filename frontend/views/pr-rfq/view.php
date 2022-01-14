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

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>


    <table>
        <thead>

            <tr>
                <td colspan="7">Name of the Procuring Entity:</td>
            </tr>
            <tr>
                <td colspan="7">Name of the Project</td>
            </tr>
            <tr>
                <td colspan="7">Location of the Project</td>
            </tr>

            <tr>
                <td colspan="7">REQUEST FOR QUOTAION</td>
            </tr>
            <tr>
                <td colspan="7">Company Name</td>
            </tr>
            <tr>
                <td colspan="7">Address</td>
            </tr>
            <tr>
                <td colspan="7">

                    Please quote your lowest price on the item/s listed below, subject to the General Conditions
                    <br>
                    stated herein. Submit your quotation duly signed by you or your representative not later than 3:00 PM on
                    <br>
                    December 27, 2021 in a sealed envelope. Late submission will not be accepted.
                </td>

            </tr>
            <tr>
                <td colspan="7">RBAC Chairperson</td>
            </tr>
            <tr>
                <td colspan="7">

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
                        <li>a. Certified True Copy of Mayor's Permit;</li>
                        <li>b. Certified True Copy of DTI/SEC/ or CDA Registration Certificate;</li>
                        <li>c. Certified True Copies of Latest Income/Business Tax Return (for ABCs above P500,000.00);</li>
                        <li>d. Certified True Copy of PhilGEPS Registration Certificate; and </li>
                        <li>e. Notarized Omnibus Sworn Statement (for ABCs above P50,000.00).</li>
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

            foreach ($model->rfqItems as $val)
            {
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

        </tbody>
    </table>


</div>

<style>
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
</style>