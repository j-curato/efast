<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DerecognitionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Derecognitions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="derecognition-index">


    <p>
        <?= Html::a('Create Derecognition', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Derecognitions'
        ],
        'columns' => [

            'serial_number',
            'derecognition_date',
            'property_number',
            'article',
            'description',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id);
                }
            ]
        ],
    ]); ?>


</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 5rem;
        padding: 0;
    }
</style>