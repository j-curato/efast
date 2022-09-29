<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\IrTransmittalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inspection Report Transmittals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ir-transmittal-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create IR Transmittal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Inspection Report  Transmittal'
        ],
        'columns' => [

            'serial_number',
            'date',

            [
                'label' => 'Action',
                'format' => 'raw',

                'value' => function ($model) {
                    $btns = Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id], []);
                    $btns .= ' ' . Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id], []);
                    return  $btns;
                }
            ],
        ],
    ]); ?>


</div>