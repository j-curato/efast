<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DivisionProgramUnitSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Division Program Units';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="division-program-unit-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Division Program Unit', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel'=>[
            'type'=>'primary'
        ],
        'columns' => [

            'name',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>


</div>
