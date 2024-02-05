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
        <?= Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success']) ?>
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
        'pjax' => true,
        'columns' => [

            'alphalist_number',
            'check_range',

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' =>  Yii::$app->user->can('ro_accounting_admin') ? '{view} {delete}' : '{view} ',
            ],
        ],
    ]); ?>


</div>