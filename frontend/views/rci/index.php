<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RciSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RCIs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rci-index">


    <p>
        <?= Yii::$app->user->can('create_rci') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success ']) : '' ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'RCIs'
        ],
        'pjax' => true,
        'columns' => [

            'serial_number',
            [
                'attribute' => 'fk_book_id',
                'value' => function ($model) {
                    return $model->book->name;
                }
            ],
            'date',
            'reporting_period',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_rci') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],
        ],
    ]); ?>


</div>