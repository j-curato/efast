<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrIarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' IAR';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-iar-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create IAR', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'IAR'
        ],
        'columns' => [

            'id',
            '_date',
            'reporting_period',
            'invoice_number',
            'invoice_date',
            //'fk_pr_purchase_order_id',
            //'fk_insepection_officer',
            //'fk_property_custodian',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>