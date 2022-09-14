<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\IarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "IAR's";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iar-index">



    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => "IAR's"
        ],
        'pjax' => true,
        'columns' => [
            'iar_number',
            'ir_number',
            'rfi_number',
            'responsible_center',
            'inspector_name',
            'requested_by_name',
            'end_user',
            'po_number',
            'payee_name',
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]);
                }
            ]
        ],
    ]); ?>


</div>