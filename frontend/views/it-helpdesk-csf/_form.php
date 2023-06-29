<?php

use app\models\ItMaintenanceRequest;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ItHelpdeskCsf */
/* @var $form yii\widgets\ActiveForm */

$client = [];
?>

<div class="it-helpdesk-csf-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'fk_it_maintenance_request')->widget(Select2::class, [
        'data' => ArrayHelper::map(ItMaintenanceRequest::find()->asArray()->all(), 'id', 'serial_number'),
        'pluginOptions' => [
            'placeholder' => 'Select Serial Number'
        ]
    ]) ?>

    <?= $form->field($model, 'fk_client_id')->widget(Select2::class, [
        'options' => ['placeholder' => 'Search for a Employee ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => Yii::$app->request->baseUrl . '?r=employee/search-employee',
                'dataType' => 'json',
                'delay' => 250,
                'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                'cache' => true
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
            'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
        ],
    ]) ?>

    <?= $form->field($model, 'contact_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 4]) ?>

    <?= $form->field($model, 'email')->textInput(['type' => 'email']) ?>

    <?= $form->field($model, 'date')->widget(DatePicker::class, [
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'autoclose' => true
        ]
    ]) ?>
    <?= $form->field($model, 'sex')->widget(Select2::class, [
        'data' => [
            'f' => 'Female',
            'm' => 'Male'
        ],
        'pluginOptions' => [
            'placeholder' => 'Select Sex'
        ]
    ]) ?>

    <?= $form->field($model, 'age_group')->widget(Select2::class, [
        'data' => [
            '21-35' => '21-35 years old and below',
            '35-59' => 'Aboce 35 - below 60 years old',
            '60' => ' 60 years old & above',
        ],
        'pluginOptions' => [
            'placeholder' => 'Select Age Group'
        ]
    ]) ?>


    <?= $form->field($model, 'social_group')->widget(Select2::class, [
        'data' => [
            '21-35' => '21-35 years old and below',
            '35-59' => 'Aboce 35 - below 60 years old',
            '60' => ' 60 years old & above',
        ],
        'pluginOptions' => [
            'placeholder' => 'Select Age Group'
        ]
    ]) ?>

    <label for="table">CRITERIA FOR RATING</label>
    <table class="table num_8">
        <tr>
            <td></td>
            <th>VERY SATISFIED</th>
            <th>SATISFIED</th>
            <th>DISSATISFIED</th>
            <th>VERY DISSATISFIED</th>
        </tr>

        <tr>
            <th>
                <div class="int_gbl_olk"></div>
                <?= $form->field($model, 'clarity')->radioList(
                    ['4' => '', '3' => '', '2' => '', '1' => ''],
                    [
                        'custom' => true,
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return "<td><input type='radio' name='{$name}' value='{$value}' " . ($checked ? 'checked' : '') . "></td>";
                        },
                        'inline' => true,
                        'style' => 'margin:0'

                    ]
                ) ?>
            </th>
        </tr>
        <tr>

            <th>
                <div class="del_solution"></div>
                <?= $form->field($model, 'skills')->radioList(
                    ['4' => '', '3' => '', '2' => '', '1' => ''],
                    [
                        'custom' => true,
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return "<td><input type='radio' name='{$name}' value='{$value}' " . ($checked ? 'checked' : '') . "></td>";
                        },
                        'inline' => true,
                        'style' => 'margin:0'

                    ]
                ) ?>


            </th>
        </tr>
        <tr>
            <th>
                <div class="net_link"></div>

                <?= $form->field($model, 'professionalism')->radioList(
                    ['4' => '', '3' => '', '2' => '', '1' => ''],
                    [
                        'custom' => true,
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return "<td><input type='radio' name='{$name}' value='{$value}' " . ($checked ? 'checked' : '') . "></td>";
                        },
                        'inline' => true,
                        'style' => 'margin:0'

                    ]
                ) ?>
            </th>
        </tr>
        <tr>
            <th>
                <div class="del_exl_res"></div>


                <?= $form->field($model, 'courtesy')->radioList(
                    ['4' => '', '3' => '', '2' => '', '1' => ''],
                    [
                        'custom' => true,
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return "<td><input type='radio' name='{$name}' value='{$value}' " . ($checked ? 'checked' : '') . "></td>";
                        },
                        'inline' => true,
                        'style' => 'margin:0'

                    ]
                ) ?>
            </th>
        </tr>
        <tr>
            <th>
                <div class="collaborating"></div>

                <?= $form->field($model, 'response_time')->radioList(
                    ['4' => '', '3' => '', '2' => '', '1' => ''],
                    [
                        'custom' => true,
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return "<td><input type='radio' name='{$name}' value='{$value}' " . ($checked ? 'checked' : '') . "></td>";
                        },
                        'inline' => true,
                        'style' => 'margin:0'

                    ]
                ) ?>
            </th>
        </tr>


    </table>







    <?= $form->field($model, 'other_social_group')->textInput([]) ?>
    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'vd_reason')->textarea(['rows' => 6]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
    th {
        max-width: 25rem;
        min-width: 25rem;
        width: 25rem;
        padding-right: 3rem;
    }



    td,
    th {
        text-align: center;
    }

    .table {
        width: 100%;
    }

    textarea {
        max-width: 100%;
    }
</style>