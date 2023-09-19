<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RoAlphalistSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ro Alphalists';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ro-alphalist-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create Ro Alphalist', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'RO Alphalist'
        ],
        'columns' => [

            'alphalist_number',
            'reporting_period',
            [
                'attribute' => 'is_final',
                'value' => function ($model) {
                    return $model->is_final == true ? 'Final' : 'Draft';
                }
            ],

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view}'
            ],
        ],
    ]); ?>


</div>