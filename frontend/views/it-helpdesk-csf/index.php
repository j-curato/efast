<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use kartik\grid\GridView;
use app\components\helpers\MyHelper;
use PhpOffice\PhpSpreadsheet\Calculation\Information\Value;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ItHelpdeskCsfSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'It Helpdesk Csfs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="it-helpdesk-csf-index">



    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'CSF'
        ],
        'columns' => [

            'serial_number',
            'fk_it_maintenance_request',
            'contact_num',
            //'address:ntext',
            //'email:ntext',
            //'date',
            //'clarity',
            //'skills',
            //'professionalism',
            //'courtesy',
            //'response_time:datetime',
            //'sex',
            //'age_group',
            //'comment:ntext',
            //'vd_reason:ntext',
            //'created_at',

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
