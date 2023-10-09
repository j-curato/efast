<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .modal-wide {
        width: 90%;
    }
</style>

<div class="transaction-index">



    <p>
        <?= Html::a('Create Transaction', ['create'], ['class' => 'btn btn-success']) ?>
    </p>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Transactions',
        ],

        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            // [
            //     'class' => 'kartik\grid\ExpandRowColumn',
            //     'width' => '50px',
            //     'value' => function ($model, $key, $index, $column) {
            //         return GridView::ROW_COLLAPSED;
            //     },
            //     // uncomment below and comment detail if you need to render via ajax
            //     // 'detailUrl' => Url::to([ '/index.php?r=transaction/sample&id='.$model->id]),
            //     'detail' => function ($model, $key, $index, $column) {
            //         $q=SubAccounts1::findOne(2602);
            //         return Yii::$app->controller->renderPartial('view_sample', ['model' => $q]);
            //     },
            //     'headerOptions' => ['class' => 'kartik-sheet-style'],
            //     'expandOneOnly' => true
            // ],

            // 'id',

            [
                'label' => 'Responsibility Center',
                'attribute' => 'responsibility_center_id',
                'value' => 'responsibilityCenter.name',

            ],
            'tracking_number',

            // 'payee_id',
            // [

            // ],
            [
                'label' => 'Payee',
                'attribute' => 'payee_id',
                'value' => 'payee.account_name'
            ],
            'particular',
            // 'gross_amount',
            [
                'attribute' => 'gross_amount',
                'format' => ['decimal', 2],
            ],
            'earmark_no',
            'payroll_number',
            'transaction_date',
            //'transaction_time',

            [
                'class' => '\kartik\grid\ActionColumn',
                'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>', 'style' => "display:none"],
            ],
        ],
        'pjax' => true,

        // 'panel' => [
        //     'type' => GridView::TYPE_PRIMARY,
        //     'heading' => '<i class="glyphicon glyphicon-book"></i>  Books',
        //     'before' => Html::a('<i class="fa fa-pencil-alt"></i> Create Book', ['create'], ['class' => 'btn btn-success']),
        // ],




    ]); ?>


</div>