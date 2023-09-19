<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MandatoryReserveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mandatory Reserves';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mandatory-reserve-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create Mandatory Reserve', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'serial_number',
            'reporting_period',
            'particular:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
