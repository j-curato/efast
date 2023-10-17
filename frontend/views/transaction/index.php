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
            [
                'label' => 'Responsibility Center',
                'attribute' => 'responsibility_center_id',
                'value' => 'responsibilityCenter.name',

            ],
            'tracking_number',

            [
                'label' => 'Payee',
                'attribute' => 'payee_id',
                'value' => 'payee.account_name'
            ],
            'particular',
            'earmark_no',
            'payroll_number',
            'transaction_date',

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