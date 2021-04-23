<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransmittalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transmittals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transmittal-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Transmittal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'cash_disbursement_id',
            'transmittal_number',
            'location',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
