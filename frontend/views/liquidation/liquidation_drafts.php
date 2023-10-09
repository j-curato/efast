<?php

use yii\helpers\Html;
use common\models\User;
use kartik\grid\GridView;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\export\ExportMenu;
use app\models\LiquidationViewSearch;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LiquidataionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Liquidations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="liquidation-index">


    <?php if (Yii::$app->user->can('create_liquidation')) { ?>



    <?php }
    ?>

    <!-- LIQUIDATION ENTRIES AND MODEL NAA SA INDEX CONTROLLER GE CHANGE -->


    <?php

    $user_data = User::getUserDetails();
    $province = strtolower($user_data->employee->office->office_name);
    $viewSearchModel = new LiquidationViewSearch();
    $viewSearchModel->is_final = 0;
    $viewDataProvider = $viewSearchModel->search(Yii::$app->request->queryParams);
    $viewDataProvider->pagination = ['pageSize' => 10];

    $viewColumn = [
        'province',
        // [
        //     'attribute' => 'province',
        //     'value' => function ($model) {


        //         return strtoupper($model->province);
        //     }
        // ],
        'check_date',
        'check_number',
        'dv_number',
        'reporting_period',
        // 'particular',
        [
            'label' => 'Payee',
            'attribute' => 'payee',
            'value' => function ($model) {

                if (!empty($model->tr_payee)) {
                    $payee = $model->tr_payee;
                } else {
                    $payee = $model->payee;
                }
                return $payee;
            }
        ],
        [
            'label' => 'Particular',
            'attribute' => 'particular',
            'value' => function ($model) {

                if (!empty($model->tr_particular)) {
                    $particular = $model->tr_particular;
                } else {
                    $particular = $model->particular;
                }
                return $particular;
            }
        ],

        [
            'label' => 'Total Disbursements',
            'attribute' => 'total_withdrawal',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],
        [
            'label' => 'Total Sales Tax (VAT/Non-VAT)',
            'attribute' => 'total_vat',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],
        [
            'label' => 'Income Tax (Expanded Tax)',
            'attribute' => 'total_expanded',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],
        [
            'label' => 'Total Liquidation Damage',
            'attribute' => 'total_liquidation_damage',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],
        [
            'label' => 'Gross Payment',
            'attribute' => 'gross_payment',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],

        [
            'label' => 'View',
            'format' => 'raw',
            'value' => function ($model) {

                return Html::a("", ['view', 'id' => $model->id], ['class' => 'btn-xs  fa fa-eye']);
                // return $query['total'];
            },
            'hiddenFromExport' => true,
            'vAlign' => 'middle',
        ],
    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $viewDataProvider,
        'filterModel' => $viewSearchModel,
        'columns' => $viewColumn,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Liquidations',
        ],
        'toolbar' => [
            [
                'content' =>
                ExportMenu::widget([
                    'dataProvider' => $viewDataProvider,
                    'columns'  => $viewColumn,
                    'filename' => 'Liquidations',
                    'exportConfig' => [
                        ExportMenu::FORMAT_CSV => false,
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_HTML => false,

                    ]
                ]),
                'options' => [
                    'class' => 'btn-group mr-2', 'style' => 'margin-right:20px'
                ]
            ]
        ]
    ]); ?>


</div>

<style>
    .grid-view td {
        white-space: normal;
        font-size: 12px;
    }
</style>