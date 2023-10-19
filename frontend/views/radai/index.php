<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RadaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RADAIs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="radai-index">


    <p>
        <?= Yii::$app->user->can('create_radai') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success ']) : '' ?>
    </p>
 
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'RADAIs'
        ],
        'pjax' => true,
        'columns' => [

            'serial_number',
            'reporting_period',
            'date',
            [

                'attribute' => 'fk_book_id',
                'value' => function ($model) {
                    return $model->book->name ?? '';
                }
            ],

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_radai') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],
        ],
    ]); ?>


</div>