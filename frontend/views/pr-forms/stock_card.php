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

        <table class="table">
            <tbody>
                <tr>
                    <th colspan="7" class="head" style="border: none;font-size:large">STOCK CARD</th>
                </tr>
                <tr>
                    <th colspan="4" style="border: none;border-bottom:1px solid black;font-weight:bold;">
                        <span>
                            Entity Name:
                        </span>

                        <span>
                            ___________________
                        </span>
                    </th>
                    <th colspan="4" style="border: none;border-bottom:1px solid black;font-weight:bold; text-align:right;">
                        <span>
                            Fund Cluster:
                        </span>

                        <span>
                            ___________________
                        </span>
                    </th>
                </tr>
                <tr>
                    <td colspan="5">

                        <span>Item:

                        </span>


                        <span>

                        </span>
                    </td>
                    <td colspan="2">

                        <span>Item Code:

                        </span>


                        <span>

                        </span>
                    </td>

                </tr>
                <tr>
                    <td colspan="5">

                        <span>
                            Description
                        </span>


                        <span>

                        </span>
                    </td>
                    <td colspan="2">

                        <span>
                            Re-order Point:
                        </span>
                    </td>


                </tr>
                <tr>
                    <td colspan="5" style="border: 1px solid black;">

                        <span>
                            Unit of Measurement:
                        </span>
                    </td>
                    <td colspan="2">

                        <span>

                        </span>
                    </td>


                </tr>
                <tr>
                    <td rowspan="3" class="head">
                        DATE
                    </td>
                    <td rowspan="3" class="head">
                        Reference
                    </td>
                </tr>
                <tr>
                    <td class='head' colspan="" style="font-style: italic; border:1px solid black;">Receipt</td>
                    <td class='head' colspan="2" style="font-style: italic;border:1px solid black;">Issue</td>
                    <td class='head' colspan="" style="font-style: italic;border:1px solid black;">Balance</td>
                    <td class='head' rowspan="2" style="border:1px solid black;">No. of Days to Consume</td>

                </tr>
                <tr>
                    <td>Qty.</td>
                    <td>Qty.</td>
                    <td>Office</td>
                    <td>Qty.</td>


                </tr>


                <tr>

                    <td>qwer</td>
                    <td>qwer</td>
                    <td>qwer</td>
                    <td>qwer</td>
                    <td>qwer</td>
                    <td>qwer</td>
                    <td>qwer</td>

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