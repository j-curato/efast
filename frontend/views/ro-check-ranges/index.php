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
    <?= Yii::$app->user->can('create_ro_check_range') ? Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success modalButtonCreate']) : '' ?>

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
                    $updateBtn = Yii::$app->user->can('update_ro_check_range') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                    
                    
                }
            ]
        ],
        'pjax' => true
    ]); ?>


</div>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]);
?>