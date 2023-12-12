<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FmiFundReleasesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fmi Fund Releases';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-fund-releases-index">


    <p>
        <?= Yii::$app->user->can('super-user') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success lrgModal']) : '' ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'List of Fund Releases'
        ],
        'columns' => [

            'serial_number',
            'fk_subproject_id',
            'fk_tranche_id',
            'fk_cash_disbursement_id',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('super-user') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => ' lrgModal']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]
        ],
    ]); ?>


</div>