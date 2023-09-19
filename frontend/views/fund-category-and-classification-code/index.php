<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FundCategoryAndClassificationCodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fund Category And Classification Codes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-category-and-classification-code-index">


    <p>
        <?= Html::a('<i class="fa fa-pencil-alt"></i> Create ', ['create'], ['class' => 'btn btn-success modalButtonCreate']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Fund Category And Classification Codes',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [

            'name',
            'description',
            'from',
            'to',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id);
                }
            ],
        ],
    ]); ?>


</div>