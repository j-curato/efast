<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItMaintenanceRequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'IT Maintenance Requests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="it-maintenance-request-index">


    <p>
        <?= Yii::$app->user->can('create_it_maintenance_request') ? Html::a('Create Book', ['create'], ['class' => 'btn btn-success modalButtonCreate']) : '' ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'headings' => 'IT Maintenance Requests'
        ],
        'columns' => [
            'serial_number',

            'fk_requested_by',
            'fk_worked_by',
            'fk_division_id',
            'date_requested',
            'date_accomplished',
            'description:ntext',
            'type',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_it_maintenance_request') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],
        ],
    ]); ?>


</div>
<?php
$this->registerJsFile(
    '@web/frontend/web/js/globalFunctions.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
)
?>
<script>

</script>