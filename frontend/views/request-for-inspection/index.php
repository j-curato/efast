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

    <p>
        <?= Yii::$app->user->can('create_request_for_inspection') ? Html::a('<i class="fa fa-plus"></i >Create ', ['create'], ['class' => 'btn btn-success mdModal']) : '' ?>
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
            [
                'attribute' => 'fk_responsibility_center_id',
                'value' => function ($model) {
                    $emp = '';
                    if (!empty($model->responsibilityCenter->id)) {
                        $dvsn = !empty($model->responsibilityCenter->name) ? $model->responsibilityCenter->name : '';
                        $emp =  $dvsn;
                    }
                    return $emp;
                }
            ],


            [
                'attribute' => 'fk_chairperson',
                'value' => function ($model) {
                    $emp = '';
                    if (!empty($model->fk_chairperson)) {
                        $emp = $model->chairperson->f_name . ' ' .  $model->chairperson->m_name[0] . '. ' .  $model->chairperson->l_name;
                    }
                    return $emp;
                }
            ],
            [
                'attribute' => 'fk_inspector',
                'value' => function ($model) {
                    $emp = '';
                    if (!empty($model->fk_inspector)) {
                        $emp = $model->inspector->f_name . ' ' .  $model->inspector->m_name[0] . '. ' .  $model->inspector->l_name;
                    }
                    return $emp;
                }
            ],
            [
                'attribute' => 'fk_property_unit',
                'value' => function ($model) {
                    $emp = '';
                    if (!empty($model->fk_property_unit)) {
                        $emp = $model->propertyUnit->f_name . ' ' .  $model->propertyUnit->m_name[0] . '. ' .  $model->propertyUnit->l_name;
                    }
                    return $emp;
                }
            ],
            'created_at',

            [
                'label' => 'Action',
                'format' => 'raw',

                'value' => function ($model) {
                    $btns = Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id], []);
                    if (!$model->is_final && Yii::$app->user->can('update_request_for_inspection')) {
                        $btns .= ' ' . Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], []);
                    }
                    return  $btns;
                }
            ],
        ],
    ]); ?>


</div>