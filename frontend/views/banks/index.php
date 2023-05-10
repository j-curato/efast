<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BanksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Banks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banks-index">


    <p>
        <?= Html::a('Create Banks', ['create'], ['class' => 'btn btn-success modalButtonCreate']) ?>
    </p>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Banks'
        ],
        'columns' => [
            'name',
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {

                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id])
                        . ' ' . Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate'])
                        .  Html::a(' <i class="fa fa-trash"></i>', ['delete', 'id' => $model->id], [
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]);
                }
            ]
        ],
    ]); ?>


</div>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]);
?>