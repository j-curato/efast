<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BacPositionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bac Positions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bac-position-index">


    <p>
        <?= Html::a('Create Bac Position', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>'BAC Postions'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'position',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
