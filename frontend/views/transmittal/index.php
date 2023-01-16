<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransmittalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transmittals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transmittal-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Transmittal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'transmittal_number',
            'date',
            'location', 
            [

                'label'=>'DV Count',
                'value'=>function($model){

                    $query=(new \yii\db\Query())
                    ->select('count(id) as count')
                    ->from('transmittal_entries')
                    ->where("transmittal_entries.transmittal_id =:transmittal_id",['transmittal_id'=>$model->id])
                    ->one();

                    return $query['count'];
                }
            ],

            ['class' => '\kartik\grid\ActionColumn'],
        ],
    ]); ?>


</div>
