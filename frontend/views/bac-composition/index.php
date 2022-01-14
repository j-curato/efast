<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BacCompositionnSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bac Compositions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bac-composition-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Bac Composition', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'effectivity_date',
            'expiration_date',
            'rso_number',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
