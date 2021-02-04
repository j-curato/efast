<?php

use app\models\ChartOfAccounts;
use app\models\FundClusterCode;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\JevPreparation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jev-preparation-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <?php
    $r_center = Yii::$app->db->createCommand(
        "SELECT * from responsibility_center"
    )->queryAll();
    $f_code = FundClusterCode::find()
        ->all();
    $chart = Yii::$app->db->createCommand("SELECT  ca.id ,CONCAT(ca.uacs,'-',ca.general_ledger) as ledger from chart_of_accounts as ca")->queryAll();

    ?>


    <div class="card " style="width: full;background-color:white; padding:2rem;margin-bottom:1rem;border-radius:1rem;box-shadow:5rem">
        <div class="card-body">

            <div class="row ">
                <div class="col-sm-3">


                    <?= $form->field($model, 'date')->widget(DatePicker::class, [
                        'options' => ['placeholder' => 'Enter  Date'],
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',

                        ]
                    ]); ?>
                </div>
                <div class="col-sm-3">


                    <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                        'options' => ['placeholder' => 'Reporting Period'],
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [

                            'autoclose' => true,
                            'format' => 'yyyy-mm',
                            'startView' => "year",
                            'minViewMode' => "months",
                        ]
                    ]); ?>
                </div>


                <div class="col-sm-3">
                    <?= $form->field($model, 'entity_name')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-sm-3">


                    <?= $form->field($model, 'jev_number')->widget(Select2::class, [
                        'data' => ['ckdj' => 'a', 'adadj' => 'b', 'c'],
                        'options' => ['placeholder' => 'Select a Fund Source'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>

                </div>

            </div>

            <div class="row">

                <div class="col-sm-3">
                    <?= $form->field($model, 'responsibility_center_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map($r_center, 'id', 'name'),
                        'options' => ['placeholder' => 'Select a Fund Source'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
                <div class="col-sm-3">
                    <?= $form->field($model, 'fund_cluster_code_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map($f_code, 'id', 'name'),
                        'options' => ['placeholder' => 'Select a Fund Source'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>


                <div class="col-sm-3">
                    <?= $form->field($model, 'dv_number')->textInput(['maxlength' => true]) ?>

                </div>

                <div class="col-sm-3">
                    <?= $form->field($model, 'lddap_number')->textInput(['maxlength' => true]) ?>
                </div>


            </div>

            <?= $form->field($model, 'explaination')->textInput(['maxlength' => true]) ?>
        </div>
    </div>










    <div class="panel panel-default" style="border-radius:1rem;box-shadow:2rem">
        <div class="panel-heading" style="border-radius:1rem 1rem 0 0 ;">
            <h4><i class="glyphicon "></i> JEV Accounting Entries</h4>
        </div>
        <div class="panel-body">
            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => 4, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $modelJevItems[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'chart_of_account_id',
                    'debit',
                    'credit',

                ],
            ]); ?>

            <div class="container-items">
                <!-- widgetContainer -->
                <?php foreach ($modelJevItems as $i => $modelJevItem) : ?>
                    <div class="item panel panel-default">
                        <!-- widgetBody -->
                        <div class="panel-heading" style="background-color: #6495ED;">
                            <h3 class="panel-title pull-left">Entry</h3>
                            <div class="pull-right">
                                <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <?php
                            // necessary for update action.
                            if (!$modelJevItem->isNewRecord) {
                                echo Html::activeHiddenInput($modelJevItem, "[{$i}]id");
                            }
                            ?>

                            <div class="row">


                                <div class="col-sm-4">
                                    <?= $form->field($modelJevItem,  "[{$i}]chart_of_account_id")->widget(Select2::class, [
                                        'data' => ArrayHelper::map($chart, 'id', 'ledger'),
                                        'options' => ['placeholder' => 'Select a Fund Source'],

                                    ]); ?>
                                </div>


                                <div class="col-sm-4">
                                    <?= $form->field($modelJevItem, "[{$i}]credit")->textInput(['maxlength' => true]) ?>
                                </div>
                                <div class="col-sm-4">
                                    <?= $form->field($modelJevItem, "[{$i}]debit")->textInput(['maxlength' => true]) ?>
                                </div>
                            </div><!-- .row -->
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php DynamicFormWidget::end(); ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('SAVE', ['class' => 'btn btn-success', 'style' => 'width:100%;']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <?php

    $js = "
        $('#modalButtoncreate').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('.modalButtonedit').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
 


";
    $this->registerJs($js, $this::POS_END);
    ?>