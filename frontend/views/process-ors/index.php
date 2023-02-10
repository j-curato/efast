<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProccessOrsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Process Ors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="process-ors-index">


    <p>
        <?= Html::a('Create Process Ors', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <!-- ANG MODEL ANI KAY SA PROCESS ORS ENTRIES -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Process ORS'
        ],
        'columns' => [

            'serial_number',
            'reporting_period',
            'date',
            'tracking_number',
            'particular',
            'r_center',
            'payee',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id);
                }
            ],
        ],
    ]); ?>


</div>