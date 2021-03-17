<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProccessOrsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Process Ors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="process-ors-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Process Ors', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'transaction_id',
            'reporting_period',
            'serial_number',
            'obligation_number',
            //'funding_code',
            //'document_recieve_id',
            //'mfo_pap_code_id',
            //'fund_source_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
