<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RequestForInspectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Request For Inspections';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="request-for-inspection-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Create Request For Inspection', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Request For Inspection',
        ],
        'pjax' => true,
        'columns' => [

            'rfi_number',
            'date',
            'division',
            'unit',
            'unit_head',
            'inspector',
            'chairperson',
            'property_unit',
            'po_number',
            'payee',
            'purpose',
            'project_name',


            [
                'label' => 'Action',
                'format' => 'raw',

                'value' => function ($model) {
                    $btns = Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id], []);
                    if (!$model->is_final) {
                        $btns .= ' ' . Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id], []);
                    }
                    return  $btns;
                }
            ],
        ],
    ]); ?>


</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 70rem;
        padding: 0;
    }
</style>