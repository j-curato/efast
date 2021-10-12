<?php

use kartik\grid\GridView;
use yii\data\ActiveDataProvider;


$this->title = 'Rao';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">
    <div class="container panel panel-default">


    </div>
    <?php



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
        'columns' => [
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
            'division'
        ]
    ]); ?>


</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 100px;
    }
</style>