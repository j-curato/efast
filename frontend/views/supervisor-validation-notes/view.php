<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SupervisorValidationNotes */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Supervisor Validation Notes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="supervisor-validation-notes-view">



    <div class="container" style="background-color: white;">

        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        </p>




        <table class="">
            <tr>
                <td></td>
                <th>Employee Name (Last Name, First Name, M.I) </th>
                <td><?= $model->employee_name ?></td>
            </tr>
            <tr>
                <td></td>
                <th>Evaluation Period </th>
                <td><?= $model->evaluation_period ?></td>
            </tr>
            <tr>
                <td></td>
                <th>Total no. of success measures </th>
                <td><?= $model->ttl_suc_msr ?></td>
            </tr>
            <tr>
                <td></td>
                <th> Valid measures for evaluation period </th>
                <td><?= $model->valid_msr ?></td>
            </tr>
            <tr>
                <td></td>
                <th> Measures with 90% accomplishment for the evaluation period </th>
                <td><?= $model->accomplishments ?></td>
            </tr>
            <tr>
                <td></td>
                <th> Tentative PGS Rating </th>
                <td><?= $model->pgs_rating ?></td>
            </tr>
            <tr>
                <td></td>
                <th> Comment from the supervisor </th>
                <td><?= $model->comment ?></td>
            </tr>
            <tr>
                <th colspan="3"> Staff Demonstration of Competencies (1 being the lowest and 5 being the highest) </th>
            </tr>


            <tr>
                <td></td>
                <th> Integrated Industry and Globalized Outlook </th>
                <td><?= $model->int_gbl_olk ?></td>
            </tr>
            <tr>
                <td></td>
                <th> I Delivering Solutions, Services and Support to DTI's Stakeholders </th>
                <td><?= $model->del_solution ?></td>
            </tr>
            <tr>
                <td></td>
                <th> Networking and Linkaging </th>
                <td><?= $model->net_link ?></td>
            </tr>
            <tr>
                <td></td>
                <th> Delivering Exellent Results </th>
                <td><?= $model->del_exl_res ?></td>
            </tr>
            <tr>
                <td></td>
                <th> Collaborating </th>
                <td><?= $model->collaborating ?></td>
            </tr>
            <tr>
                <td></td>
                <th> Agility </th>
                <td><?= $model->agility ?></td>
            </tr>

            <tr>
                <td></td>
                <th> Exemplifying Professionalism and Integrity </th>
                <td><?= $model->proflsm_int ?></td>
            </tr>



            <tr>
                <th colspan="3"> Staff Demonstration of Core Values (1 being the lowest and 5 being the highest) </th>
            </tr>


            <tr>
                <td></td>
                <th> Passion </th>
                <td><?= $model->passion ?></td>
            </tr>
            <tr>
                <td></td>
                <th> Integrety </th>
                <td><?= $model->integrety ?></td>
            </tr>
            <tr>
                <td></td>
                <th> Competence </th>
                <td><?= $model->competence ?></td>
            </tr>
            <tr>
                <td></td>
                <th> Creativity </th>
                <td><?= $model->creativity ?></td>
            </tr>
            <tr>
                <td></td>
                <th> Synergy </th>
                <td><?= $model->synergy ?></td>
            </tr>
            <tr>
                <td></td>
                <th> Love of Country </th>
                <td><?= $model->love_of_country ?></td>
            </tr>
            <tr>
                <td></td>
                <th> Staff learning and Development Intervention needed </th>
                <td><?= $model->dev_intervention ?></td>
            </tr>
        </table>
    </div>


</div>
<style>
    .container {
        padding: 3rem;
    }

    table {
        width: 100%;
    }

    th,
    td {
        padding: 1rem;
    }

    th {
        min-width: 25rem;
        max-width: 28rem;
    }

    @media print {

        .btn,
        .main-footer {
            display: none;
        }

        th,
        td {
            padding: 5px;
        }
    }
</style>