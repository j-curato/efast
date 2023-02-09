<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrAoqSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'AOQ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-aoq-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create  AOQ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'AOQ'
        ],
        'pjax' => true,
        'columns' => [

            'aoq_number',
            [
                'attribute' => 'pr_rfq_id',
                'value' => 'rfq.rfq_number'
            ],
            'pr_date',
            [
                'label' => 'PR Number',
                'attribute' => 'pr_number',
                'value' => 'rfq.purchaseRequest.pr_number'
            ],
            [
                'label' => 'purpose',
                'attribute' => 'purpose',
                'value' => 'rfq.purchaseRequest.purpose'
            ],
            'created_at',
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id);
                }
            ]
        ],
    ]); ?>


</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 5rem;
        padding: 0;
    }
</style>