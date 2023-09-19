<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PurchaseOrderTransmittalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Purchase Order Transmittals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-order-transmittal-index">


    <p>
        <?= Html::a('Create Purchase Order Transmittal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Transmittals'
        ],
        'columns' => [

            'serial_number',

            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    $btns = Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id], []);
                    $btns .= ' ' . Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], []);
                    return  $btns;
                }
            ],
        ],
    ]); ?>


</div>