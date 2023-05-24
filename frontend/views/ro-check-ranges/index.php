<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RoCheckRangeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ro Check Ranges';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ro-check-range-index">


    <p>
        <?= Html::a('Create Ro Check Range', ['create'], ['class' => 'btn btn-success modalButtonCreate']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'RO Check Ranges'
        ],
        'columns' => [
            [
                'attribute' => 'fk_book_id',
                'value' => function ($model) {
                    return $model->book->name;
                }
            ],
            'from',
            'to',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id);
                }
            ]
        ],
        'pjax' => true
    ]); ?>


</div>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]);
?>