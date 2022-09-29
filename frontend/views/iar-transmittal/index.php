<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\IarTransmittalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'IAR Transmittals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iar-transmittal-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create IAR Transmittal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'IAR Transmittals'
        ],
        'pjax' => true,
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