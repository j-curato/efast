<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;


$this->title = 'Rao';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">
    <div class="container card">


    </div>
    <?php

    $gridColumn =  [
        'allotment_number',
        'ors_number',
        'payee',
        'particular',
        'document_name',
        'fund_cluster_code_name',
        'financing_source_code_name',
        'fund_category_and_classification_code_name',
        'authorization_code_name',
        'mfo_pap_code_name',
        'fund_source_name',
        'reporting_period',
        'uacs',
        'general_ledger',
        'book_name',

        [
            'attribute' => 'ors_amount',
            'hAlign' => 'right',
            'format' => ['decimal', 2]

        ],
        [
            'attribute' => 'allotment_amount',
            'hAlign' => 'right',
            'format' => ['decimal', 2]

        ],
        'division',
        'is_cancelled'
    ];

    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Rao',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'toolbar' =>  [
            [
                'content' =>
                ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumn,
                    'filename' => 'Cash Disbursements',
                    'exportConfig' => [
                        ExportMenu::FORMAT_TEXT => false,
                        // ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_EXCEL => false,
                        ExportMenu::FORMAT_HTML => false,
                    ]

                ]),
                'options' => ['class' => 'btn-group mr-2', 'style' => 'margin-right:20px']
            ],

        ],
        'columns' => $gridColumn
    ]); ?>


</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 100px;
    }
</style>