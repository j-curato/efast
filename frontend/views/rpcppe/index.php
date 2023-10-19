<?php

use app\components\helpers\MyHelper;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RpcppeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RPCPPE';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rpcppe-index">
    <p>
        <?= Yii::$app->user->can('create_rpcppe') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success ']) : '' ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'RPCPPE'
        ],
        'columns' => [
            [
                'attribute' => 'fk_office_id',
                'value' => 'office.office_name'
            ],
            'reporting_period',
            [
                'attribute' => 'fk_book_id',
                'value' => 'book.name'
            ],
            [
                'attribute' => 'fk_chart_of_account_id',
                'value' => 'chartOfAccount.general_ledger'
            ],
            [
                'attribute' => 'fk_actbl_ofr',
                'value' => function ($model) {
                    $name  = !empty($model->fk_actbl_ofr) ? MyHelper::getEmployee($model->fk_actbl_ofr, 'one')['employee_name'] : '';
                    return $name;
                }
            ],



            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>