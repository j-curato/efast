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
    <div class="container panel panel-default">


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
            'amount',
            'remitted_amount',
            'unremitted_amount'
        ],
    ]); ?>



</div>
<style>

</style>