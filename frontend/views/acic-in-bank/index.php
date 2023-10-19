<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AcicInBankSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Acic In Banks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="acic-in-bank-index">


    <p>
        <?= Yii::$app->user->can('create_acic_in_bank') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success ']) : '' ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'ACIC in Bank'
        ],
        'pjax' => true,
        'columns' => [

            'serial_number',
            'date',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {

                    $updateBtn = Yii::$app->user->can('update_acic_in_bank') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],
        ],
    ]); ?>


</div>