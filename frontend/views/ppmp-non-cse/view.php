<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PpmpNonCse */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ppmp Non Cses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ppmp-non-cse-view">


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



    <div class="container">

        <table>

            <thead>
                <tr>
                    <th colspan="10" class="center no-border">
                        <h5>
                            <b>
                                PROJECT PROCUREMENT MANAGEMENT PLAN (PPMP)
                            </b>
                        </h5>
                    </th>
                </tr>
                <tr>
                    <td class="no-border" colspan="2">
                        <span>
                            END-USER/UNIT:
                        </span>
                        <span>

                            xxx Division
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="no-border" colspan="2">
                        <span>
                            DIVISION::
                        </span>
                        <span>

                            xxx Division
                        </span>
                    </td>
                </tr>
                <tr>

                    <th rowspan="2">Procurement Program/ Project</th>
                    <th rowspan="2">Category</th>
                    <th rowspan="2">Remarks (brief description of Program/Activity/Project)</th>
                    <th rowspan="2">Target Date/Months</th>
                    <th rowspan="2">Source of Funds</th>
                    <th rowspan="2">Code (PAP)</th>
                    <th rowspan="2">PMO/ End-User</th>
                    <th colspan="3">Estimated Budget (PhP)</th>
                </tr>
                <tr>
                    <th>Total</th>
                    <th>MOOE</th>
                    <th>CO</th>
                </tr>
            </thead>

            <tbody>
                <?php
                foreach ($items as $i => $val) {
                    $min_key =  min(array_keys($val));
                    $project_name = $val[$min_key]['project_name'];
                    $description = $val[$min_key]['description'];
                    $target_month = $val[$min_key]['target_month'];
                    $fund_source_name = $val[$min_key]['fund_source_name'];
                    $mfo_name = $val[$min_key]['mfo_name'];
                    $employee_name = $val[$min_key]['end_user'];
                    echo "<tr>
                            <td>$project_name</td>
                            <td></td>
                            <td>$description</td>
                            <td>$target_month</td>
                            <td>$fund_source_name</td>
                            <td>$mfo_name</td>
                            <td>$employee_name</td>
                            <td></td>
                            <td></td>
                            <td></td>
                    </tr>";

                    foreach ($val as $val2) {
                        $type = $val2['type'];
                        $budget = $val2['budget'];
                        echo "<tr>
                            <td></td>
                            <td>$type</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>$budget</td>
                            <td></td>
                            <td></td>
                        </tr>";
                    }
                }

                ?>
                <tr>
                    <td colspan="10">
                        <b>
                            We hereby warrant that the total amount reflected in this Project Procurement Management Plan, to procure the listed NonCSEs, has been included in or is within our approved budget for the year.
                        </b>
                    </td>
                </tr>
                <tr>

                    <td colspan="3" class="center no-border">
                        <br>
                        <br>
                        <br>
                        <b>_____________________________________________</b><br>
                        <span>End-user</span><br>
                        <span>(Account Offcier)</span>
                    </td>
                    <td colspan="7" class="center no-border">
                        <br>
                        <br>
                        <br>
                        <b>_____________________________________________</b><br>
                        <span>Division Chief</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="10" class="no-border">
                        <br>
                        <span>Date Prepared:</span>
                        <span>_______________________</span>
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="center no-border">
                        <b>Certified Funds Available / Certified Appropriate Funds Available:</b>
                        <br>
                        <br>
                        <br>
                        <b>_____________________________________________</b><br>
                        <span>O II - for Provincial Offices</span><br>
                        <span>Budget Officer - for Regional Office</span>
                    </td>
                    <td colspan="7" class="center no-border">

                        <span class="" style="float: left;">Approved By:</span>
                        <br>
                        <br>
                        <br>
                        <b>_____________________________________________</b><br>

                        <span>Head of Office/Agency</span>
                    </td>
                </tr>
            </tbody>

        </table>
    </div>

</div>
<?php $this->registerCssFile(yii::$app->request->baseUrl . "/css/customCss.css", ['depends' => [\yii\web\JqueryAsset::class]]); ?>
<style>
    table {
        width: 100%;
    }

    th,
    td {
        border: 1px solid black;
        font-size: small;
        padding: 1rem;
    }

    @media print {
        .btn {
            display: none;
        }

        th,
        td {
            padding: 4px;
        }
    }
</style>