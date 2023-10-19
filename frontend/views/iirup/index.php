<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\IirupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'IIRUPs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iirup-index">


    <p>
        <?= Yii::$app->user->can('create_iirup') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success ']) : '' ?>

    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'IIRUPs'
        ],
        'pjax' => true,
        'columns' => [

            'serial_number',
            'office_name',
            'approved_by',
            'accountable_officer',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {

                    $updateBtn = Yii::$app->user->can('update_iirup') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],
        ],
    ]); ?>


</div>