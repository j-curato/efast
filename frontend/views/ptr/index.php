<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PtrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'PTRs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ptr-index">


    <p>
        <?= Yii::$app->user->can('create_ptr') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success lrgModal']) : '' ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'PTRs'
        ],
        'pjax' => true,
        'columns' => [

            'office_name',
            'ptr_number',
            'date',
            'property_number',
            'par_number',
            'description',
            'receive_by',
            'article',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_ptr') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'lrgModal']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]

        ],
    ]); ?>


</div>
<?php $this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => JqueryAsset::class]) ?>