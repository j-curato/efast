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
        'columns' => [

            'rfi_number',
            'date',
            'fk_chairperson',
            'fk_inspector',
            //'fk_property_unit',
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