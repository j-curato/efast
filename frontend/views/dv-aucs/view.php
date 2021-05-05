<?php

use app\models\Raouds;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\DvAucs */

$this->title = $model->dv_number;
$this->params['breadcrumbs'][] = ['label' => 'Dv Aucs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="dv-aucs-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="container panel panel-default">
        <p>
            <?= Html::a('Print', ['dv-form', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php

            if (!empty($model->cashDisbursement)) {

                $t = yii::$app->request->baseUrl . "/index.php?r=cash-disbursement/view&id={$model->cashDisbursement->id}";
                echo  Html::a('Cash Disbursement Link', $t, ['class' => 'btn btn-success ']);
            }
            ?>
        </p>
        <table class="table table-striped">

            <tbody>
                <thead>
                    <th>
                        Obligation Number
                    </th>
                    <th>
                        DV Number
                    </th>
                    <th>
                        Reporting Period
                    </th>
                    <th>
                        Payee
                    </th>
                    <th>
                        Amount Disbursed
                    </th>
                    <th>
                        Tax Withheld
                    </th>
                </thead>
            <tbody>

                <?php

                foreach ($model->dvAucsEntries as $val) {

                    $ors_serial_number = '';
                    $ors_serial_number = !empty($val->process_ors_id) ? $val->processOrs->serial_number : '';
                    $t = '';
                    if (!empty($val->process_ors_id)) {

                        $q = Raouds::find()
                            ->where('raouds.process_ors_id = :process_ors_id', ['process_ors_id' =>  $val->process_ors_id])
                            ->one();
                        // $q = !empty($val->process_ors_id) ? $val->process_ors_id : '';
                        $t = yii::$app->request->baseUrl . "/index.php?r=process-ors-entries/view&id=$q->id";
                    }

                    echo "
                    <tr>
                    <td>
                        {$ors_serial_number}
                    </td>
                    <td>
                        {$val->dvAucs->dv_number}
                    </td>
                    <td>
                        {$val->dvAucs->reporting_period}
                    </td>
                    <td>
                        {$val->dvAucs->payee->account_name}
                    </td>
                    <td >"
                        . number_format($val->amount_disbursed, 2) .
                        "</td>
                    <td>
                        {$val->ewt_goods_services}
                    </td>
                    <td class='link'>" .

                        Html::a('ORS', $t, ['class' => 'btn-xs btn-success '])
                        . "
                
                </td>
                    </tr>
                    ";
                }
                // echo $model->dvAucsEntries;
                ?>
            </tbody>

            </tbody>
        </table>

    </div>
    <style>
        .head {
            font-weight: bold;
        }
        .container{
            padding:15px
        }

        .checkbox {

            margin-right: 4px;
            margin-top: 6px;
            height: 20px;
            width: 20px;
            border: 1px solid black;
        }

        /* td {
            border: 1px solid black;
            padding: 1rem;
            white-space: nowrap;
        } */

        table {
            margin: 12px;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
        }

        @media print {
            .actions {
                display: none;
            }

            table,
            th,
            td {
                border: 1px solid black;
                padding: 5px;
                font-size: 10px;
            }

            @page {
                size: auto;
                margin: 0;
                margin-top: 0.5cm;
            }

            .container {
                margin: 0;
                top: 0;
            }

            .entity_name {
                font-size: 5pt;
            }

            table,
            th,
            td {
                border: 1px solid black;
                padding: 5px;
                background-color: white;
            }

            .container {

                border: none;
            }

            table {
                page-break-after: auto
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto
            }

            td {
                page-break-inside: avoid;
                page-break-after: auto
            }

            /* thead {
        display: table-header-group
    } */

            .main-footer {
                display: none;
            }
        }
    </style>

</div>