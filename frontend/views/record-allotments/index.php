<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RecordAllotmentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Record Allotments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="record-allotments-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Record Allotments', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'document_recieve_id',
            'fund_cluster_code_id',
            'financing_source_code_id',
            'fund_category_and_classification_code_id',
            //'authorization_code_id',
            //'mfo_pap_code_id',
            //'fund_source_id',
            //'reporting_period',
            //'serial_number',
            //'allotment_number',
            //'date_issued',
            //'valid_until',
            //'particulars',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
