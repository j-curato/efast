<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DepreciationScheduleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Depreciation Schedules';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="depreciation-schedule-index">


    <p>
        <?= Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Depreciation Schedules'
        ],
        'columns' => [

            'reporting_period',
            [
                'attribute' => 'fk_book_id',
                'value' => 'book.name'
            ],
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id, 'none');
                }
            ]
        ],
    ]); ?>


</div>