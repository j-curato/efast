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
                'template' =>  Yii::$app->user->can('super-user')?'{view} {delete}':'{view} ',

                // 'deleteOptions' => function () {

                //     if (!Yii::$app->user->can('super-user')) {
                //         return ['style' => 'display:none'];
                //     } else {
                //         return [];
                //     }
                // },
                // 'updateOptions' => ['hidden' => true],

            ],
        ],
    ]); ?>


</div>