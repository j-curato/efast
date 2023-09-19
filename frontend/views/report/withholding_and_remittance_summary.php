<?php


use kartik\grid\GridView;



/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Withholding and Remittance Summary';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">
    <!-- <input type="text" name="" id="sample"> -->
    <div class="container card">


    </div>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Withholding and Remittance Summary',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [
            'payroll_number',
            'ors_number',
            'dv_number',
            'object_code',
            'account_title',
            [
                'label' => 'Amount Withheld',
                'attribute' => 'amount',
                'hAlign' => 'right',
                'format' => ['decimal', 2]
            ],
            [
                'attribute' => 'remitted_amount',
                'hAlign' => 'right',
                'format' => ['decimal', 2]
            ],
            [
                'attribute' => 'unremitted_amount',
                'hAlign' => 'right',
                'format' => ['decimal', 2]
            ],

        ],
    ]); ?>



</div>
<style>

</style>