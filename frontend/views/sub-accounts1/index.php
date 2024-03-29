<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use kartik\grid\GridView;
use kartik\widgets\FileInput;
use kartik\widgets\ActiveForm;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubAccounts1Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sub Accounts1s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-accounts1-index">




    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Areas',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [
            'object_code',
            [

                'label' => 'Account Title',
                'attribute' => 'name',
                'options' => [
                    'style' => 'word-wrap: break-word; width: 100px'
                ]
            ],


            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn  = Yii::$app->user->can('update_sub_account_1') ?  Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'mdModal']) : '';
                    $createSubAccount2Btn  = Yii::$app->user->can('create_sub_account_2') ? Html::a('<i class="fa fa-plus"></i>', ['sub-accounts2/create', 'subAcc1Id' => $model->id], ['class' => 'btn-xs btn-success mdModal']) : '';
                    return $createSubAccount2Btn . ' ' .
                        Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id])
                        . ' ' . $updateBtn;
                }
            ]
        ],
    ]); ?>

</div>

