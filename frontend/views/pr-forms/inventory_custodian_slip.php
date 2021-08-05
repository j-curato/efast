<?php

use app\models\Assignatory;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Transmittal */

$this->title = 'Supplies Ledger card';
$this->params['breadcrumbs'][] = ['label' => 'Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="transmittal-view">


    <div class="container">

        <table>
            <tbody>
                <tr>
                    <th colspan="7" class="head" style="border: none;font-size:large">INVENTORY CUSTODIAN SLIP</th>
                </tr>
                <tr>
                    <th colspan="7" style="border: none;font-weight:bold;">
                        <span>
                            Entity Name:
                        </span>

                        <span>
                            ___________________
                        </span>
                    </th>

                </tr>
                <tr>
                    <th colspan="5" style="border: none;font-weight:bold;">
                        <span>
                            Fund Cluster:
                        </span>

                        <span>
                            ___________________
                        </span>
                    </th>
                    <th colspan="2" style="border: none;font-weight:bold; ">
                        <span>
                            ICS No.:
                        </span>

                        <span>
                            ___________________
                        </span>
                    </th>
                </tr>



                <tr>
                    <td rowspan="3" class="head">
                        Quantity
                    </td>
                    <td rowspan="3" class="head">
                        Unit
                    </td>
                </tr>
                <tr>
                    <td class='head' colspan="2" style="font-style: italic;">Receipt</td>
                    <td class='head' colspan="2" rowspan="2" style="font-style: italic; width:300px">Description</td>
                    <td class='head' rowspan="2" style="font-style: italic;width:50px">Inventory Item No.</td>
                    <td class='head' rowspan="2" style="font-style: italic; width:50px">Estimated Useful Life</td>


                </tr>
                <tr>
                    <td>Unit Cost</td>
                    <td>Total Cost</td>


                </tr>


                <tr>

                    <td>qwer</td>
                    <td>qwer</td>
                    <td>qwer</td>
                    <td>qwer</td>
                    <td colspan="2">qwer</td>
                    <td>qwer</td>
                    <td>qwer</td>

                </tr>
                <tr>
                    <td colspan="5" style="border-top:none;border-bottom:none;">Recieved From:</td>
                    <td colspan="4" style="border-top:none;border-bottom:none;">Recieved By:</td>
                </tr>
                <tr>
                    <td colspan="5" class="foot">
                        <div class="row">

                            _______________________________
                        </div>
                        <div class="row">

                            Signature Over Printed Name
                        </div>

                    </td>
                    <td class='foot' colspan="4">
                        <div class="row">

                            _______________________________
                        </div>
                        <div class="row">

                            Signature Over Printed Name
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class='foot' colspan="5">
                        <div class="row">

                            _______________________________
                        </div>
                        <div class="row">

                            Position/Office
                        </div>

                    </td>
                    <td class='foot' colspan="4">
                        <div class="row">

                            _______________________________
                        </div>
                        <div class="row">

                            Position/Office
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class='foot-last' colspan="5">
                        <div class="row">

                            _______________________________
                        </div>
                        <div class="row">

                            Date
                        </div>

                    </td>
                    <td class='foot-last' colspan="4">
                        <div class="row">

                            _______________________________
                        </div>
                        <div class="row">

                            Date
                        </div>
                    </td>
                </tr>
            </tbody>

        </table>
    </div>

</div>

<style>
    .head {
        font-weight: bold;
        text-align: center;
    }

    .foot {
        text-align: center;
        border-top: none;
        border-bottom: none;
        padding-top: 0;
    }

    .foot-last {
        border-top: none;
        text-align: center;
        padding-top: 0;
    }

    th,
    td {
        padding: 15px;
        border: 1px solid black;

    }

    table {
        width: 100%
    }

    @media print {
        .main-footer {
            display: none;
        }
    }
</style>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<?php
$script = <<< JS
    $("#assignatory").change(function(){
      
        console.log("qwe")
    })

JS;
$this->registerJs($script);
?>