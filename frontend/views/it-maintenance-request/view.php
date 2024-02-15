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
$approvedBy = MyHelper::getEmployee($model->fk_approved_by, 'one');
?>
<div class="it-maintenance-request-view container">


    <p>
        <?= Yii::$app->user->can('update_it_maintenance_request') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'mdModal btn btn-primary']) : '' ?>
        <?php
        if (Yii::$app->user->can('super-user')) {
            if (!empty($model->helpdeskCsf->id)) {
                echo Html::a('Update CSF', ['it-helpdesk-csf/update', 'id' => $model->helpdeskCsf->id], ['class' => 'btn btn-info lrgModal']);
            } else {
                echo Html::a('Add CSF', ['it-helpdesk-csf/create', 'fk_it_maintenance_request_id' => $model->id], ['class' => 'btn btn-info lrgModal']);
            }
        }

        ?>

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
                <td><?= !empty($model->divisions->division) ? strtoupper($model->divisions->division) : '' ?></td>
                <th>Serial No.:</th>
                <td><?= $model->serial_number ?></td>
            </tr>
            <tr>

                <th>Date Requested:</th>
                <td><?= !empty($model->date_requested) ? DateTime::createFromFormat('Y-m-d', $model->date_requested)->format('F d, Y') : '' ?></td>
                <th>Date Accomplish:</th>
                <td><?= !empty($model->date_accomplished) ? DateTime::createFromFormat('Y-m-d', $model->date_accomplished)->format('F d, Y') : '' ?></td>
            </tr>

            <tr>
                <th colspan="2" class="ctr"> DESCRIPTION </th>
                <th colspan="2" class="ctr">ACTION TAKEN</th>
            </tr>
            <tr>
                <td colspan="2" style="height: 2%;width:50%"> <?= $model->description ?></td>
                <td colspan="2" style="height: 15rem;width:50%"><?= $model->action_taken ?></td>
            </tr>
            <tr>
                <th colspan="4" class='ctr'>Acceptance</th>

            </tr>
            <tr>
                <th class="no-bdr" colspan="2">Requested By</th>
                <th class="no-bdr" colspan="2">Actioned By</th>
            </tr>
            <tr>
                <td colspan="2" class="ctr" style="border:0 ; border-right:0;">
                    <br>
                    <b><u><?= !empty($rqstd_by['employee_name']) ? strtoupper($rqstd_by['employee_name']) : '' ?></u></b>
                    <br>
                    <span><?= $rqstd_by['position'] ?? '' ?></span>
                    <br>
                    <br>


                </td>
                <td colspan="2" class="ctr" style="border:0 ;border-left:0;">
                    <br>
                    <b><u><?= !empty($actnd_by['employee_name']) ? strtoupper($actnd_by['employee_name']) : '' ?></u></b>
                    <br>
                    <span><?= !empty($actnd_by['position']) ? $actnd_by['position'] : '' ?></span>
                    <br>
                    <br>
                </td>
            </tr>
            <tr>
                <th class="no-bdr" colspan="2">Approved By</th>
            </tr>
            <tr>
                <td colspan="2" class="ctr" style="border:0;">
                    <br>
                    <b><u><?= !empty($approvedBy['employee_name']) ? strtoupper($approvedBy['employee_name']) : '' ?></u></b>
                    <br>
                    <span><?= !empty($approvedBy['position']) ? $approvedBy['position'] : '' ?></span>
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

?>