<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FmiProjectCompletions */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Fmi Project Completions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fmi-project-completions-view">

    <h1><?= Html::encode($this->title) ?></h1>

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

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'fk_office_id',
            'fk_fmi_subproject_id',
            'serial_number',
            'completion_date',
            'turnover_date',
            'spcr_link:ntext',
            'certificate_of_project_link:ntext',
            'certificate_of_turnover_link:ntext',
            'reporting_period',
            'created_at',
        ],
    ]) ?>

</div>
