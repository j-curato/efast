<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PmrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'PMRs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pmr-index">


    <p>
        <?= Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'panel' => [
            'type' => 'primary',
            'heading' => 'PMRs'
        ],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'fk_office_id',
            'reporting_period',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>