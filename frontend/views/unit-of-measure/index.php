<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UnitOfMeasureSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Unit Of Measures';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unit-of-measure-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Unit Of Measure', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'unit_of_measure',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
