<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FmiActualDateOfStartsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Actual Date Of Starts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-actual-date-of-starts-index">
    <p>
        <?= Yii::$app->user->can('super-user') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success mdModal']) : '' ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Actual Date of Starts'
        ],
        'columns' => [

            'fk_tbl_fmi_subproject_id',
            'actual_date_of_start',
            'created_at',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('super-user') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'mdModal']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]
        ],
    ]); ?>


</div>