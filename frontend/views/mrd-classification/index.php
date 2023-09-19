<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MrdClassificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mrd Classifications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mrd-classification-index">


    <p>
        <?= Html::a('Create Mrd Classification', ['create'], ['class' => 'btn btn-success modalButtonCreate']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'MRD Classifications'
        ],
        'columns' => [

            'name',
            'description',

            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id);
                }
            ]
        ],
    ]); ?>


</div>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [\yii\web\JqueryAsset::class]]) ?>