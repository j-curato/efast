<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PayrollSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payrolls';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payroll-index">


    <p>
        <?= Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Payrolls'
        ],
        'columns' => [

            'payroll_number',
            'reporting_period',
            [
                'label' => 'ORS Number',
                'attribute' => 'process_ors_id',
                'value' => 'processOrs.serial_number'
            ],
            'amount',

            [
                'class' => 'kartik\grid\ActionColumn',
                'deleteOptions' => ['style' => 'display:none']
            ],
        ],
    ]); ?>


</div>