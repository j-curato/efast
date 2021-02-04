<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ResponsibilityCenterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Responsibility Centers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="responsibility-center-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Responsibility Center', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Areas',
      ],
       'floatHeaderOptions'=>[
           'top'=>50,
           'position'=>'absolute',
         ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'description',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
