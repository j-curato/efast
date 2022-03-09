<?php

use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PropertySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Properties';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Property', ['create'], ['class' => 'btn btn-success']) ?>
        <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Import</button>
    </p>
    <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">UPLOAD Cash Disbursement</h4>
                </div>
                <div class='modal-body'>
                    <center><a href="/afms/frontend/web/import_formats/Cash_Disbursement and DV Format.xlsx">Download Template Here to avoid error during Upload.</a></center>
                    <hr>
                    <?php


                    $form = ActiveForm::begin([
                        'action' => ['property/import'],
                        'method' => 'post',
                        'id' => 'formupload',
                        'options' => [
                            'enctype' => 'multipart/form-data',
                        ], // important
                    ]);
                    // echo '<input type="file">';
                    echo FileInput::widget([
                        'name' => 'file',
                        // 'options' => ['multiple' => true],
                        'id' => 'fileupload',
                        'pluginOptions' => [
                            'showPreview' => true,
                            'showCaption' => true,
                            'showRemove' => true,
                            'showUpload' => true,
                        ]
                    ]);

                    ActiveForm::end();


                    ?>

                </div>
            </div>
        </div>
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GRIDVIEW::TYPE_PRIMARY,
            'heading' => 'Property',
        ],
        'export' => [
            'fontAwesome' => true
        ],
        'columns' => [

            'property_number',
            'iar_number',
            [
                'label' => 'Unit of Measure',
                'attribute' => 'unit_of_measure_id',
                'value' => function ($model) {
                    $unit_of_measure = '';
                    if (!empty($model->unitOfMeasure->unit_of_measure)) {

                        $unit_of_measure = $model->unitOfMeasure->unit_of_measure;
                    }
                    return $unit_of_measure;
                }
            ],
            [
                'label' => 'Book',
                'attribute' => 'book_id',
                'value' => function ($model) {
                    $book = '';
                    if (!empty($model->book->name)) {

                        $book = $model->book->name;
                    }
                    return $book;
                }
            ],
            [
                'label' => 'Property Custodian',
                'attribute' => 'employee_id',
                'value' => function ($model) {
                    $emp = '';
                    if (!empty($model->employee->f_name)) {
                        $f_name = !empty($model->employee->f_name) ? $model->employee->f_name : '';
                        $m_name = !empty($model->employee->m_name[0]) ? $model->employee->m_name[0] : '';
                        $l_name = !empty($model->employee->l_name) ? $model->employee->l_name : '';
                        $emp =   $f_name . ' ' .  $m_name . '. ' .  $l_name;
                    }
                    return $emp;
                }
            ],


            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>


</div>