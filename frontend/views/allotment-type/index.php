<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AllotmentTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Allotment Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="allotment-type-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create Allotment Type', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary'
        ],
        'columns' => [

            'type:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>