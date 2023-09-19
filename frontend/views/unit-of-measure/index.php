<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UnitOfMeasureSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Unit Of Measures';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unit-of-measure-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::button('<i class="fa fa-pencil-alt"></i> Create', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=unit-of-measure/create'), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>

    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Unit of Measure'
        ],
        'columns' => [

            'unit_of_measure',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
