<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OtherPropertyDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Other Property Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="other-property-details-index">


    <p>
        <?= Yii::$app->user->can('create_other_property_details') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success ']) : '' ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Other Property Details'
        ],
        'columns' => [
            'office_name',
            'property_number',
            'article',
            'description',
            'uacs',
            'general_ledger',
            'salvage_value_prcnt',
            'useful_life',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_other_property_details') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],
        ],
    ]); ?>


</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 10rem;
        padding: 12px;
    }
</style>