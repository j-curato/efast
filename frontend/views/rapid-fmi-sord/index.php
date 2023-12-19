<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RapidFmiSordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rapid FMI SORDs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rapid-fmi-sord-index">


    <p>
        <?= Yii::$app->user->can('super-user') ? Html::a('Create Rapid Fmi Sord', ['create'], ['class' => 'btn btn-success']) : '' ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'SORDs'
        ],
        'columns' => [

            'fk_fmi_subproject_id',
            'reporting_period',
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