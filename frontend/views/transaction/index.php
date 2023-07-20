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
        <?php
        if (Yii::$app->user->can('super-user')) {
            // echo "<button type='button' class='btn btn-primary'  id ='update_local_transaction'>Update Local Transaction</button>";
        }
        ?>
    </p>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Transactions',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
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
        'striped' => true,
        'condensed' => true,
        'responsive' => true,
        'hover' => true,
        // 'panel' => [
        //     'type' => GridView::TYPE_PRIMARY,
        //     'heading' => '<i class="glyphicon glyphicon-book"></i>  Books',
        //     'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> Create Book', ['create'], ['class' => 'btn btn-success']),
        // ],
        'containerOptions' => ['style' => 'overflow: auto'],
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container']],
        'toolbar' => [
            // [
            //     'content' =>
            //     Html::button('<i class="glyphicon glyphicon-plus"></i>', ['type' => 'button', 'title' => 'Add Book', 'class' => 'btn btn-success', 'onclick' => 'alert("This will launch the book creation form.\n\nDisabled for this demo!");']) . ' ' .
            //         Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['class' => 'btn btn-success']) . ' ' .
            //         Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => 'Reset Grid'])
            // ],
            ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => [],
                'filename' => "DV",
                'exportConfig' => [
                    ExportMenu::FORMAT_TEXT => false,
                    ExportMenu::FORMAT_HTML => false,
                    ExportMenu::FORMAT_EXCEL => false,

                ]

            ]),
            '{toggleData}',
        ],
        'export' => [
            'fontAwesome' => true
        ],
        'bordered' => true,
        'striped' => false,
        'condensed' => true,
        'responsive' => true,
        'hover' => true,
        'showPageSummary' => false,
        'persistResize' => false,
        'panelBeforeTemplate' => '
        <div class="pull-right">
            <div class="btn-toolbar kv-grid-toolbar" role="toolbar">
                {toolbar}
            </div>
        </div>
        <div class="clearfix"></div>',
        'panelAfterTemplate' => '',
        'rowOptions' => function ($model) {
        },
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
    ]); ?>


</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 5rem;
        padding: 0;
    }
</style>