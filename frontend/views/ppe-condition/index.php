<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PpeConditionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ppe Conditions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ppe-condition-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create Ppe Condition', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'condition',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
