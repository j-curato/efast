<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ParSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pars';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="par-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Par', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'par_number',
            'property_number',
            'date',
            'employee_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
