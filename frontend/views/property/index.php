<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PropertySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Properties';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Property', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel'=>[
            'type'=>GRIDVIEW::TYPE_PRIMARY,
            'heading'=>'Property',
        ],
        'export'=>[
            'fontAwesome'=>true
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'property_number',
            'book_id',
            'unit_of_measure_id',
            'employee_id',
            'iar_number',
            //'article',
            //'model',
            //'serial_number',
            //'quantity',
            //'acquisition_amount',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
