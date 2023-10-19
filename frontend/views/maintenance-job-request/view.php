<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MaintenanceJobRequest */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Maintenance Job Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);


$employee = Yii::$app->db->createCommand("SELECT employee_name,position FROM employee_search_view WHERE employee_id = :employee_id")
    ->bindValue(':employee_id', $model->fk_employee_id)
    ->queryOne();
$approved_by = '';
if (!empty($model->fk_approved_by)) {
    $approved_by = Yii::$app->db->createCommand("SELECT employee_name,position FROM employee_search_view WHERE employee_id = :employee_id")
        ->bindValue(':employee_id', $model->fk_approved_by)
        ->queryOne();
}
?>
<div class="maintenance-job-request-view">



    <div class="container card">
        <p>

            <?= Yii::$app->user->can('update_maintenance_job_request') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate btn btn-primary']) : '' ?>
        </p>
        <table>

            <tbody>
                <tr>
                    <th colspan="3" class="no-border">
                        <?= Html::img('frontend/web/dti.jpg', ['alt' => 'some', 'class' => '', 'style' => 'width: 65px;height:65px;']); ?>
                    </th>
                </tr>
                <tr>
                    <th colspan="4" class="no-border">
                        HRAS-SUPPORT

                    </th>
                </tr>
                <tr>
                    <th colspan="4">
                        <h4 class="bold">MAINTENANCE JOB REQUEST</h4>
                    </th>
                </tr>
                <tr>
                    <td class=' ' colspan="2">
                        <span class='bold'>Office: </span>

                        <span> <?= $model->responsibilityCenter->name ?></span>
                    </td>
                    <td colspan="1">

                        <span class='bold'> MJR No.:</span>
                        <span> <?= $model->mjr_number ?></span>

                    </td>
                </tr>
                <tr>
                    <td class=' ' colspan="2">

                        <span class='bold'> Requested By:</span>
                        <span><?= $employee['employee_name'] ?></span>

                    </td>
                    <td colspan="1">

                        <span class='bold'>Date Requested:</span>
                        <span><?= DateTime::createFromFormat('Y-m-d', $model->date_requested)->format('F d, Y') ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>

                    <td>

                        <span class="bold"> Date Visited:</span>
                    </td>
                </tr>
                <tr>
                    <th colspan="1">PROBLEM DESCRIPTION</th>
                    <th>RECOMMENDATION</th>
                    <th>ACTION TAKEN</th>
                </tr>
                <tr>

                    <td colspan="1"> <?= $model->problem_description ?></td>
                    <td><?= $model->recommendation ?></td>
                    <td><?= $model->action_taken ?></td>
                </tr>
                <tr>
                    <th colspan="4">Acceptance</th>

                </tr>
                <tr>
                    <td colspan="1" style="min-width: 300px;padding-top:4rem">

                        <span class='bold'> Requested by:</span>
                        <span class="bold" style="text-decoration:underline"><?= strtoupper($employee['employee_name'])  ?></span>
                        <br>
                        <span class='bold'> Designation:</span>
                        <span><?= $employee['position'] ?></span>
                        <br>
                        <br>
                        <span class='bold'>Date:</span>
                        <span>_____________________________</span>


                    </td>
                    <td colspan="2" style="padding-top:4rem">
                        <span class='bold'>Approved By:</span>
                        <span class="bold" style="text-decoration:underline"><?= !empty($approved_by) ? strtoupper($approved_by['employee_name']) : ''  ?></span>
                        <br>
                        <span class='bold'> Designation:</span>
                        <span><?= !empty($approved_by) ? $approved_by['position'] : '' ?></span>
                        <br>
                        <br>
                        <span class='bold'>Date:____________________________________</span>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>



</div>
<style>
    .bold {
        font-weight: bold;
    }

    .no-border {
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

    }

    th {
        text-align: center;
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
        }
    }
</style>
<?php
$script = <<<JS
        $('.modalButtoncreate').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
JS;
$this->registerJs($script);
?>