<?php

use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RaoudsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Raouds';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="raouds-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Raouds', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    $gridColum=[
        'id',
        'record_allotment_id',
        'process_ors_id',
        'serial_number',
        'reporting_period',
        'obligated_amount',
        'burs_amount',
        'raoudEntries.chartOfAccount.general_ledger'
    ];
    echo ExportMenu::widget([
        'dataProvider'=>$dataProvider,
        'columns'=>$gridColum
    ]);
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'record_allotment_id',
            'process_ors_id',
            'serial_number',
            'reporting_period',
            'obligated_amount',
            'burs_amount',
            'raoudEntries.chartOfAccount.general_ledger',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
