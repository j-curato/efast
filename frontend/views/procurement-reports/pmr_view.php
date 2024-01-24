<?php

use kartik\grid\GridView;
use kartik\export\ExportMenu;


/* @var $this yii\web\View */
/* @var $searchModel app\models\PayeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'PMR';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payee-index">

    <div class="card p-2">

        <?php

        $columns = [
            'office_name',
            'division',
            'stock_name',
            'mode_of_procurement_name',
            'pr_number',
            'pr_date',
            'pr_is_cancelled',
            'rfq_date',
            'rfq_number',
            'rfq_is_cancelled',
            'pre_proc_conference',
            'post_of_ib',
            'philgeps_reference_num',
            'actual_proc_pre_bid_conf',
            'actual_proc_eligibility_check',
            'actual_proc_opening_of_bids',
            'actual_proc_bid_evaluation',
            'actual_proc_post_qual',

            "aoq_number",
            "aoq_is_cancelled",


            // 'bac_resolution_award',
            'notice_of_award',
            'contract_signing',
            'notice_to_proceed',
            'inspection_from',
            'inspection_to',
            'source_of_fund',
            'abc_mooe_amount',
            'abc_co_amount',

            'contract_mooe_amount',
            'contract_co_amount',

            'bidGrossAmount',


            'invitation_pre_bid_conf',
            'invitation_eligibility_check',
            'invitation_opening_of_bids',
            'invitation_bid_evaluation',
            'invitation_post_qual',

            'po_number',
            'po_date',
            "po_is_cancelled",
            'iar_number',
            // [
            //     'attribute' => 'pr_date',
            //     'filter' => DatePicker::widget([
            //         'model' => $searchModel,
            //         'attribute' => 'pr_date',
            //         'type' => DatePicker::TYPE_INPUT,
            //         'pluginOptions' => [
            //             'format' => 'yyyy-mm',
            //             'autoclose' => true,
            //         ],
            //     ]),
            //     'format' => 'html', // If you want to display HTML content in this column
            // ],
            // Add more columns as needed
        ];

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'type' => 'primary'
            ],
            'columns' => $columns,
            'toolbar' => [

                [
                    'content' => ExportMenu::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => $columns,
                        'target' => ExportMenu::TARGET_BLANK,
                        'filename' => 'PMR',
                        'showConfirmAlert' => false,
                        'showHeader' => true, // S
                        'exportConfig' => [
                            ExportMenu::FORMAT_EXCEL => false,
                            ExportMenu::FORMAT_CSV => false,
                            ExportMenu::FORMAT_TEXT => false,
                            ExportMenu::FORMAT_PDF => false,
                            ExportMenu::FORMAT_HTML => false,
                            ExportMenu::FORMAT_EXCEL => false,

                        ],
                    ]),
                    'options' => [
                        'class' => 'btn-group mr-2', 'style' => 'margin-right:200px'
                    ]
                ]
            ],
        ]); ?>
    </div>


</div>