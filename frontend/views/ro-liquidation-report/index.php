<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RoLiquidationReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Liquidation Reports';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ro-liquidation-report-index">


    <p>
        <?= Html::a('Create Liquidation Report', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => ' Liquidation Report'
        ],
        'columns' => [

            'liquidation_report_number',
            'date',


            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function($model){
                    return MyHelper::gridDefaultAction($model->id,'none');
                }
            ],
        ],
    ]); ?>


</div>