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
            [
                'attribute' => 'fk_pr_office_id',
                'value' => function ($model) {
                    $emp = '';
                    if (!empty($model->fk_pr_office_id)) {
                        $dvsn = !empty($model->office->division) ? $model->office->division : '';
                        $unit = !empty($model->office->unit) ? $model->office->unit : '';
                        $emp =  $dvsn . '-' .  $unit;
                    }
                    return $emp;
                }
            ],
            [
                'attribute' => 'unit_head',
                'value' => function ($model) {
                    $emp = '';
                    if (!empty($model->office->id)) {

                        if (!empty($model->office->unitHead->f_name)) {
                            $emp = $model->office->unitHead->f_name . ' ' .  $model->office->unitHead->m_name[0] . '. ' .  $model->office->unitHead->l_name;
                        }
                        // $emp = $model->office->unitHead->f_name;
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