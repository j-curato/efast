<?php

use app\models\ChartOfAccounts;
use app\models\FundClusterCode;
use app\models\Payee;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use aryelds\sweetalert\SweetAlertAsset;

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
    $payee = Payee::find()->all();

    ?>


    <div class="card " style="width: full;background-color:white; padding:2rem;margin-bottom:1rem;border-radius:1rem;box-shadow:5rem">
        <div class="card-body">


            <div class="row ">
                <div class="col-sm-3">


                    <?= $form->field($model, 'date')->widget(DatePicker::class, [
                        'options' => ['placeholder' => 'Enter  Date', 'readonly' => true, 'id' => 'date'],
                        'type' => DatePicker::TYPE_INPUT,
                        'value'=> date(''),
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',

                        ]
                    ]); ?>
                </div>
                <div class="col-sm-3">


                    <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                        'options' => ['placeholder' => 'Reporting Period', 'id' => 'reporting_period',],
                        'type' => DatePicker::TYPE_INPUT,
                        'readonly' => true,

                        'pluginOptions' => [

                            'autoclose' => true,
                            'format' => 'yyyy-mm',
                            'startView' => "year",
                            'minViewMode' => "months",
                        ]
                    ]); ?>
                </div>




                <div class="col-sm-3">


                    <?php
                    // $form->field($model, 'jev_number')->widget(Select2::class, [
                    //     'data' => ['CKDJ' => 'CKDJ', 'ADADJ' => 'ADADJ', 'CDJ' => 'CDJ', 'GJ' => 'GJ', 'CRJ' => 'CRJ'],
                    //     'options' => ['placeholder' => 'Select a Fund Source'],
                    //     'pluginOptions' => [
                    //         'allowClear' => true
                    //     ],
                    // ]);
                    ?>


                    <?= $form->field($model, 'jev_number')->textInput(
                        ['maxlength' => true, 'style' => 'border-radius:5px',],
                    ) ?>

                </div>

                <div class="col-sm-3">
                    <?= $form->field($model, 'lddap_number')->textInput(['maxlength' => true, 'style' => 'border-radius:5px']) ?>
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
                    <?= $form->field($model, 'dv_number')->textInput(['maxlength' => true, 'style' => 'border-radius:5px']) ?>

                </div>
                <div class="col-sm-3">
                    <?= $form->field($model, 'ref_number')->widget(Select2::class, [
                        'data' => [1 => "ADADJ", 2 => "CDJ", 3 => "CKDJ", 4 => "CRJ", 5 => "GJ"],
                        'options' => ['placeholder' => 'Select a Reference'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>



            </div>

            <div class="row">

                <div class="col-sm-3">
                    <?= $form->field($model, 'explaination')->textInput(['maxlength' => true, 'style' => 'border-radius:5px'],) ?>

                </div>
                <div class="col-sm-3">
                    <?= $form->field($model, 'payee_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map($payee, 'id', 'account_name'),
                        'options' => ['placeholder' => 'Select a Payee'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>



            </div>
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
                'limit' => 10, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $modelJevItems[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'chart_of_account_id',
                    'debit',
                    'credit',
                    'current_noncurrent',

                ],
            ]); ?>

            <div class="container-items">
                <!-- widgetContainer -->

                <?php foreach ($modelJevItems as $i => $modelJevItem) : ?>
                    <div class="item panel panel-default" style="border:1px solid black">
                        <!-- widgetBody -->
                        <div class="panel-heading" style="background-color: white;border:none">
                            <!-- <h3 class="panel-title pull-left">Entry</h3> -->
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
                                        'options' => ['placeholder' => 'Select a Fund Source','class'=>"{$i}",],
                                        'pluginEvents' => [
                                            "select2:select" => "function() { console.log($i) }",
                                        ]
                                    ]); ?>
                                </div>

                                <div class="col-sm-4">
                                    <?=

                                    $form->field($modelJevItem, "[{$i}]debit")->textInput(['maxlength' => true, 'class' => 'debit'])

                                    ?>
                                </div>

                                <div class="col-sm-4">
                                    <?=
                                    $form->field($modelJevItem, "[{$i}]credit")->textInput(['maxlength' => true, 'class' => 'credit'])
                                    ?>


                                </div>


                            </div><!-- .row -->
                            <div class="row">

                                <div class="col-sm-4">
                                    <?=

                                    $form->field($modelJevItem, "[{$i}]current_noncurrent")->textInput(['maxlength' => true, 'id' => "$i",])

                                    ?>
                                </div>

                                <div class="col-sm-4">
                                </div>
                            </div><!-- .row -->
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

            <div class="total">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="exampleInputEmail1">TOTAL DEBIT</label>
                        <input disabled type="email" class="form-control" id="d_total" aria-describedby="emailHelp" placeholder="Total Dedit">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="exampleInputEmail1">TOTAL CREDIT</label>
                        <input disabled type="email" class="form-control" id="c_total" aria-describedby="emailHelp" placeholder="Total Dedit">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Current/NonCurrent</label>
                        <input disabled type="email" class="form-control" id="cur_non" aria-describedby="emailHelp" placeholder="Total Dedit">
                    </div>
                </div>
            </div>
            <?php DynamicFormWidget::end(); ?>
        </div>

        <div class="form-group submit-btn">
            <?= Html::submitButton('SAVE', ['class' => 'btn save-btn']) ?>
        </div>

        <?php ActiveForm::end(); ?>


    </div>

    <style>
        .total {
            width: 100%;
            height: 100px;
            border: 1px solid black;
            padding: 1rem;
            align-items: center;
            display: flex;
            justify-content: space-around;
            text-align: center;
            border-radius: 5px;
        }

        #reporting_period {
            background-color: white;
            border-radius: 5px;
            color: black;
        }

        #date {
            background-color: white;
            border-radius: 5px;
        }

        .save-btn {
            background-color: white;
            color: black;
            border: 1px solid green;
            transition-duration: 0.4s;
            width: 95%;
        }

        .submit-btn {
            display: flex;
            align-items: center;
            justify-content: center;
        }


        .save-btn:hover {
            background-color: #4CAF50;
            color: white;
        }

        .credit {
            display: flex;
            flex-direction: column;
            width: 100%;
            padding: 6px 24px 6px 12px;
            border: 1px solid gray;
        }

        .debit {
            display: flex;
            flex-direction: column;
            width: 100%;
            padding: 6px 24px 6px 12px;
            border: 1px solid gray;
        }

        .total>div>input {
            color: red;
        }
    </style>

    <?php
    SweetAlertAsset::register($this);
    $script = <<< JS
    
    $(document).on("keyup change", ".credit, .debit", function(){
         var total_credit = 0.00;
         var total_debit = 0.00;
         $(".credit").each(function(){
            total_credit += +$(this).val()
         })
         $("#c_total").val(total_credit)

         $(".debit").each(function(){
            total_debit +=+$(this).val()
         })
         $("#d_total").val(total_debit)

         if (total_debit == total_credit){
            $(".save-btn").removeAttr("disabled");
         }

         else{
            $(".save-btn").attr("disabled", true);
         }
         
        //  console.log(total_debit);

     })
    //  $(document).change(".sample",function(){
    //      console.log($(this).val())
    //  })
    JS;
    $this->registerJs($script);
    ?>