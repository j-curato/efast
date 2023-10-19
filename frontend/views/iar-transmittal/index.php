<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\IarTransmittalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'IAR Transmittals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iar-transmittal-index">


    <p>
    <?= Yii::$app->user->can('create_iar_transmittal') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success ']) : '' ?>

    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'IAR Transmittals'
        ],
        'pjax' => true,
        'columns' => [

            'serial_number',
            'date',

            [
                'label' => 'Action',
                'format' => 'raw',

                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_iar_transmittal') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
               
                }
            ],
        ],
    ]); ?>


</div>