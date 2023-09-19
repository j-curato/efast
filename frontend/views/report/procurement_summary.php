<?php

use kartik\export\ExportMenu;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PropertyCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Procurement Summary';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-card-index">

    <h3><?= Html::encode($this->title) ?></h3>



    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    $columns =  [
        'project_title',
        'prepared_by',
        'pr_created_at',
        'pr_number',
        'pr_date',
        'pr_requested_by',
        'pr_approved_by',
        'purpose',
        'stock_title',
        'specification',
        'unit_of_measure',
        'quantity',
        'unit_cost',
        'rfq_created_at',
        'rfq_number',
        'rfq_date',
        'rfq_deadline',
        'canvasser',
        'aoq_created_at',
        'aoq_number',
        'aoq_date',
        'supplier_bid_amount',
        'lowest',
        'remark',
        'payee',
        'po_created_at',
        'po_number',
        'contract_type',
        'mode_of_procurement',
    ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'Procurement Summary'
        ],
        'toolbar' => [
            [
                'content' => ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $columns,
                    'filename' => "Procurement Summary",
                    'exportConfig' => [
                        ExportMenu::FORMAT_CSV => false,
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_HTML => false,
                        ExportMenu::FORMAT_EXCEL => false,

                    ]

                ]),
                'options' => [
                    'class' => 'btn-group mr-2', 'style' => 'margin-right:20px'
                ]
            ]
        ],
        'columns' => $columns,
        'export' => [
            'fontAwesome' => true
        ],

    ]); ?>


</div>