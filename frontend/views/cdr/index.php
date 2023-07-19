<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CdrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cdrs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cdr-index">


    <p>


        <?php
        $action_display = 'display:none';
        if (Yii::$app->user->can('create_cdr')) {
            $action_display = '';
        }
        echo Html::a('Create Cdr', ['create'], ['class' => 'btn btn-success']);
        // }
        ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of CDR',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'export' => false,
        'columns' => [

            'id',
            'serial_number',
            'reporting_period',
            'province',

            [
                'class' => '\kartik\grid\ActionColumn',
                'deleteOptions' => ['style' => $action_display],
                'updateOptions' => ['style' => 'display:none']
            ],
        ],
    ]); ?>


</div>