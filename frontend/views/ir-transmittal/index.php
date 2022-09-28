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

            'id',
            'serial_number',
            'date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>