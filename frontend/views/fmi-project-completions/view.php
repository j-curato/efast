<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FmiProjectCompletions */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Fmi Project Completions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fmi-project-completions-view">

    <div class="container">
        <div class="card p-2">
            <span>
                <?= Yii::$app->user->can('update_fmi_project_completion') ? Html::a(
                    'Update',
                    ['update', 'id' => $model->id],
                    ['class' => 'btn btn-primary mdModal']
                ) : '' ?>

            </span>
        </div>
        <div class="card p-3">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'fk_office_id',
                    'fk_fmi_subproject_id',
                    'serial_number',
                    'completion_date',
                    'turnover_date',
                    'spcr_link:ntext',
                    'certificate_of_project_link:ntext',
                    'certificate_of_turnover_link:ntext',
                    'reporting_period',
                ],
            ]) ?>
        </div>
    </div>




</div>