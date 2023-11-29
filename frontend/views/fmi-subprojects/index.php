<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FmiSubprojectsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Subprojects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-subprojects-index">


    <p>
        <?= Yii::$app->user->can('super-user') ? Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success']) : '' ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'List of Subprojects',
        ],
        'columns' => [

            'fk_province_id',
            'fk_municipality_id',
            'fk_barangay_id',
            'purok:ntext',
            //'fk_fmi_batch_id',
            //'project_duration',
            //'project_road_length',
            //'project_start_date',
            //'grant_amount',
            //'equity_amount',
            //'bank_account_name',
            //'bank_account_number',
            //'created_at',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('super-user') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]

        ],
    ]); ?>


</div>