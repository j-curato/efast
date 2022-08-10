<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\IarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "IAR's";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iar-index">



    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => "IAR's"
        ],
        'columns' => [

            'iar_number',
            'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>