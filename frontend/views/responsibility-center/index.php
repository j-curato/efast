<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ResponsibilityCenterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Responsibility Centers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="responsibility-center-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create Responsibility Center', ['create'], ['class' => 'btn btn-success modalButtonCreate']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Areas',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [

            'name',
            'description',

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
<?php $this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [\yii\web\JqueryAsset::class]]) ?>