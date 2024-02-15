<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PropertyStatusrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Property Statuses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-status-index">


    <p>
        <?= Html::a('Create Property Status', ['create'], ['class' => 'btn btn-success mdModal']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Statuses'
        ],
        'columns' => [

            'status',

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
<?php
?>