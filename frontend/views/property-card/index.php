<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PropertyCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Property Cards';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-card-index">



    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'Property Cards'
        ],
        'columns' => [

            'serial_number',
            'balance',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return  Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id],);
                }
            ],
        ],
        'export' => [
            'fontAwesome' => true
        ],

    ]); ?>


</div>