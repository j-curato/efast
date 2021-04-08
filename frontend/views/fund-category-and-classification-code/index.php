<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FundCategoryAndClassificationCodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fund Category And Classification Codes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-category-and-classification-code-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Fund Category And Classification Code', ['create'], ['class' => 'btn btn-success']) ?>
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
            'from',
            'to',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
