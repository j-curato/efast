<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FmiSubprojects */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Fmi Subprojects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fmi-subprojects-view" id="mainVue">


    <div class="container ">
        <div class="card p-2">

            <span>
                <?= Yii::$app->user->can('update_fmi_subprojects') ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : '' ?>
            </span>
        </div>
        <div class="card p-3">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'office.office_name',
                    'municipality.municipality_name',
                    'barangay.barangay_name',
                    'purok:ntext',
                    'fmiBatch.batch_name',
                    'project_name',
                    'project_duration',
                    'project_road_length',
                    [
                        'attribute' => 'project_start_date',
                        'format' => ['date', 'php:F d, Y']
                    ],
                    [
                        'label' => 'Estimated Date of Completion',
                        'value' => function ($model) {
                            $date = DateTime::createFromFormat('Y-m-d', $model->project_start_date);
                            $date->add(new DateInterval("P{$model->project_duration}M"));
                            return  $date->format('F d, Y');
                        }
                    ],
                    [
                        'attribute' => 'grant_amount',
                        'format' => ['decimal', 2]
                    ],
                    [
                        'attribute' => 'equity_amount',
                        'format' => ['decimal', 2]
                    ],
                    [
                        'label' => 'Total Project Cost',
                        'value' => function ($model) {

                            return floatval($model->grant_amount) + floatval($model->equity_amount);
                        },
                        'format' => ['decimal', 2]
                    ],
                    'bank_account_name',
                    'bank_account_number',
                ],
            ]) ?>
        </div>
        <div class="card p-3">

            <table class="table table-hover">
                <th>Organizations</th>

                <tr v-for="item in subProjectOrganizations">
                    <td>{{item.organization_name}}</td>
                </tr>
            </table>

        </div>

    </div>

</div>

<?php
$subProjectOrganizations =   $model->getFmiSubprojectOrganizationsA(['organization_name']);
?>
<script>
    new Vue({
        el: '#mainVue',
        data: {
            subProjectOrganizations: <?= !empty($subProjectOrganizations) ? json_encode($subProjectOrganizations) : json_encode([]) ?>
        },

    });
</script>