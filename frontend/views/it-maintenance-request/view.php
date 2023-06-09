<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ItMaintenanceRequest */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'It Maintenance Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$rqstd_by = MyHelper::getEmployee($model->fk_requested_by, 'one');
$actnd_by = MyHelper::getEmployee($model->fk_worked_by, 'one');
?>
<div class="it-maintenance-request-view container">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary lrgModal']) ?>

    </p>



    <table>

        <tbody>
            <!-- <tr>
                <th colspan="4" class="no-bdr ctr">
                    <?= Html::img('frontend/web/dti.jpg', ['alt' => 'some', 'class' => '', 'style' => 'width: 65px;height:65px;']); ?>
                </th>
            </tr> -->

            <tr>
                <th colspan="4" class="ctr">
                    <h4 class="bold">

                        <?php
                        if ($model->type === 'ir') {
                            echo 'INCIDENT REQUEST';
                        } else if ($model->type === 'ta') {
                            echo 'TECHNICAL ASSISTANCE';
                        }
                        ?>
                    </h4>
                </th>
            </tr>
            <tr>
                <th>Office:</th>
                <td><?= $model->divisions->division ?></td>
                <th>Serial No.:</th>
                <td><?= $model->serial_number ?></td>
            </tr>
            <tr>

                <th>Date Requested:</th>
                <td><?= DateTime::createFromFormat('Y-m-d', $model->date_requested)->format('F d, Y') ?></td>
                <th>Date Accomplish:</th>
                <td><?= DateTime::createFromFormat('Y-m-d', $model->date_accomplished)->format('F d, Y') ?? '' ?></td>
            </tr>

            <tr>
                <th colspan="2" class="ctr"> DESCRIPTION</th>
                <th colspan="2" class="ctr">ACTION TAKEN</th>
            </tr>
            <tr>

                <td colspan="2" style="height: 15rem;"> <?= $model->description ?></td>
                <td colspan="2"><?= $model->action_taken ?></td>
            </tr>
            <tr>
                <th colspan="4" class='ctr'>Acceptance</th>

            </tr>
            <tr>
                <th class="no-bdr" colspan="2">Requested By</th>
                <th class="no-bdr" colspan="2">Actioned By</th>
            </tr>
            <tr>
                <td colspan="2" class="ctr" style="border-top:0 ; border-right:0;">
                    <br>
                    <b><u><?= strtoupper($rqstd_by['employee_name']) ?? '' ?></u></b>
                    <br>
                    <span><?= $rqstd_by['position'] ?? '' ?></span>
                    <br>
                    <br>


                </td>
                <td colspan="2" class="ctr" style="border-top:0 ;border-left:0;">
                    <br>
                    <b><u><?= strtoupper($actnd_by['employee_name']) ?? '' ?></u></b>
                    <br>
                    <span><?= $actnd_by['position'] ?? '' ?></span>
                    <br>
                    <br>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<style>
    .ctr {
        text-align: center;
    }

    .no-bdr {
        border: 0;
    }

    .border-left {
        border-left: 1px solid black;
    }

    .container {
        background-color: white;
        padding: 3rem;
    }

    table {
        width: 100%;
        border: 1px solid black;

    }


    th,
    td {
        border: 1px solid black;
        padding: 1rem;
    }

    @media print {
        .main-footer {
            display: none;

        }

        .btn {
            display: none;
        }

        th,
        td {
            border: 1px solid black;
            padding: .5rem;
            font-size: 12px;
        }
    }
</style>
<?php
$this->registerJsFile(
    '@web/frontend/web/js/globalFunctions.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
)
?>