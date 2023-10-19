<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccicsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ACIC`s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accics-index">


    <p>
        <?= Yii::$app->user->can('create_acic') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success ']) : '' ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'ACIC`s'
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
            'date_issued',
            'created_at',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_acic') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],
        ],
    ]); ?>


</div>