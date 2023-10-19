<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DerecognitionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Derecognitions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="derecognition-index">


    <p>
        <?= Yii::$app->user->can('create_derecognition') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success ']) : '' ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Derecognitions'
        ],
        'columns' => [

            'serial_number',
            'derecognition_date',
            'property_number',
            'article',
            'description',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_derecognition') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]
        ],
    ]); ?>


</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 5rem;
        padding: 12px;
    }
</style>