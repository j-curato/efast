<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RciSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RCIs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rci-index">


    <p>
        <?= Html::a('Create RCI', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'RCIs'
        ],
        'columns' => [

            'serial_number',


            [
                'attribute' => 'fk_book_id',
                'value' => function ($model) {
                    return $model->book->name;
                }
            ],
            'date',
            'reporting_period',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>