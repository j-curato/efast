<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AlphalistSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Alphalists';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="alphalist-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Alphalist', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 

    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Alphalist'
        ],
        'columns' => [

            'alphalist_number',
            'check_range',

            [
                'class' => 'kartik\grid\ActionColumn',
                'deleteOptions' => ['style' => 'display:none'],
                'updateOptions' => ['hidden' => true],

            ],
        ],
    ]); ?>


</div>