<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PrApr */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pr Aprs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pr-apr-view">

    <h1><?= Html::encode($this->title) ?></h1>

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

    <table>


        <tbody>
            <tr>
                <td colspan="4">
                    <span>DEPARTMENT OF TRADE & INDUSTRY - CARAGA</span>
                    <br>
                    <span>4h Floor D & V Plaza Bldg., J. C. Aquino Avenue, Butuan City</span>
                    <br>
                    <span>Tel. No. 816-0079 or Fax No. 815-1271</span>
                </td>
                <td colspan="2">
                    <span>Agency</span>
                    <span>Acct. Code</span>
                    <span>Agency COntrol No. </span>
                </td>
            </tr>
            <tr>
                <th colspan="6">AGENCY PROCUREMENT REQUEST</th>
            </tr>
            <tr>
                <td colspan="4">
                    <span>
                        To: THE PROCUREMENT SERVICE
                    </span>
                    <br>
                    <span>
                        Depot Region XIII (CARAGA)
                    </span>
                    <br>
                    <span>J. Rosales Avenue, City Hall Drive</span>
                    <br>
                    <span>Butuan City</span>
                    <br>
                    <span>
                        ACTION REQUESTED ON THE ITEM LISTED BELOW
                    </span>


                    <span>[ / ] Please furnish with us Price Estimate (for office equipment/furniture & supplementary items)</span>
                    <br>
                    <span>[ ] Please purchase for our agency/furnitures/supplementary items per you Price Estimate</span>
                    <br>
                    <span> (PS RAD No. _________________ attached) dated ________________,________</span>
                    <br>
                    <span>[ ] Please issue common-use supplies/materials per price list as of __________,____</span>
                    <br>
                    <span>[ ] Please issue certificate of Price Reasonableness</span>
                    <br>
                    <span>[ / ] Please furnish us with your latest/updated Price list</span>
                    <br>
                    <span>[ ] Other (Specify)______________________________________________________</span>
                </td>
                <td colspan="2" style="vertical-align: top;">
                    DATE
                </td>

            </tr>
            <tr>
                <th colspan="6">IMPORTANT !! PLEASE SEE THE INSTRUCTIONS/CONDITION AT THE BACK OF ORIGINAL COPY</th>
            </tr>
            <tr>
                <th>ITEM NO.</th>
                <th>APR</th>
                <th>QUANTITY</th>
                <th>UNIT</th>
                <th>UNIT PRICE</th>
                <th>AMOUNT</th>
            </tr>
            <tr>
                <td colspan="5">
                    <span>Total AMount:</span>
                    <span> IN WORDS!</span>
                </td>
                <td>IN NUMBER</td>
            </tr>
            <tr>
                <td colspan="6">NOTE: ALL SIGNATURES MUST BE OVER PRINTED NAME</td>
            </tr>
            <tr>
                <td colspan="2">
                    <span>STOCKS REQUESTED ARE CERTIFIED TO BE WITHIN APPROVED PROGRAM:</span>
                    <br>
                    <span>ROG</span>
                    <br>
                    <span>AGENCY PROPERTY SUPPLY OFFICER</span>
                </td>
                <td colspan="2">
                    <span>FUNDS CERTIFIED AVAILABLE:</span>
                    <br>
                    <span>ROG</span>
                    <br>
                    <span>AGENCY PROPERTY SUPPLY </span>
                </td>
                <td colspan="2">
                    <span>APPROVED:</span>
                    <br>
                    <span>ROG</span>
                    <br>
                    <span>AGENCY PROPERTY SUPPLY OFFICER</span>
                </td>
            </tr>
            <tr>
                <td colspan="6">

                    <span>[ ] FUNDS DEPOSITED WITH PS [ ] ____________________CHECK No. ______________</span>
                    <br>
                    <span>IN THE AMOUNT OF: _______________________________________________________ (P _______________) ENCLOSED</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<style>
    th,
    td {
        border: 1px solid black;
        padding: 5px;
    }

    th {
        text-align: center;
    }
</style>