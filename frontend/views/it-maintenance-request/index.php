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
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success lrgModal']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

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
                    return MyHelper::gridDefaultAction($model->id, 'lrgModal');
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