<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RlsddpSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RLSDDPs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rlsddp-index">
    <p>
        <?= Html::a('Create RLSDDP', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'RLSDDPs'
        ],
        'columns' => [
            'office_name',
            'serial_number',
            'date',
            'status',
            'accountable_officer',
            'supervisor',
            'circumstances',
            'blottered',
            'police_station',
            'blotter_date',


            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id, 'none');
                }
            ],
        ],
    ]); ?>


</div>