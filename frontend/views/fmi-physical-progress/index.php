<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FmiPhysicalProgressSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Physical Progresses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-physical-progress-index">


    <p>
        <?= Yii::$app->user->can('create_fmi_physical_progress') ? Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success mdModal']) : '' ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'type' => 'primary',
                'heading' => 'Physical Progresses'
            ],
            'columns' => [
                'serial_number',
                'fk_fmi_subproject_id',
                'date',
                'physical_target',
                //'physical_accomplished',
                //'created_at',
                [
                    'label' => 'Actions',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $updateBtn = Yii::$app->user->can('update_fmi_physical_progress') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'mdModal']) : '';
                        return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                    }
                ]
            ],
        ]); ?>


</div>