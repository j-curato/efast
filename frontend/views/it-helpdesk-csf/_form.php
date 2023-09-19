<?php

use app\models\ItMaintenanceRequest;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\ItHelpdeskCsf */
/* @var $form yii\widgets\ActiveForm */

$client = [];
?>

<div class="it-helpdesk-csf-form">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true
                ]
            ]) ?></div>

        <div class="col-sm-3">
            <?= $form->field($model, 'social_group')->widget(Select2::class, [
                'data' => [
                    '21-35' => '21-35 years old and below',
                    '35-59' => 'Aboce 35 - below 60 years old',
                    '60' => ' 60 years old & above',
                ],
                'pluginOptions' => [
                    'placeholder' => 'Select Age Group'
                ]
            ]) ?></div>
    </div>






    <label for="table">CRITERIA FOR RATING</label>
    <table class=" num_8" style="width:100%">
        <tr>
            <td></td>
            <th>VERY SATISFIED</th>
            <th>SATISFIED</th>
            <th>DISSATISFIED</th>
            <th>VERY DISSATISFIED</th>
        </tr>

        <tr>
            <th>
                <div class="clarity"></div>
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
                <div class="skills"></div>
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
                <div class="professionalism"></div>

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
                <div class="courtesy"></div>


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
                <div class="response_time"></div>
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
        <tr>
            <th>
                <div class="outcome"></div>
                <?= $form->field($model, 'outcome')->radioList(
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

    <?= $form->field($model, 'comment')->textarea(['rows' => 3]) ?>
    <?= $form->field($model, 'vd_reason')->textarea(['rows' => 3]) ?>


    <div class="row">
        <div class="col-sm-2 col-sm-offset-5 form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
<style>
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
<?php
SweetAlertAsset::register($this);

$script = <<< JS
function q (){
   const arr = [
    'clarity',
    'skills',
    'professionalism',
    'courtesy',
    'response_time',
    'outcome',
  
   ];
   $.each(arr,(key,val)=>{
        $("."+val).closest('th').find('label').attr('style', 'color:black;')
   })
}
    $('#ItHelpdeskCsf').on('submit',function(e){
        e.preventDefault()
        var \$form = $(this);
        $.post(
            \$form.attr("action"),
            \$form.serialize()
        )
        .done(function(result){
            q()

            const res = JSON.parse(result)
            console.log(res)
            if (!res.isSuccess) {

                if (typeof res.error_message === 'object') {
                    $.each(res.error_message,(key,val)=>{
                        if (
                        key=='clarity'
                        ||key=='skills'
                        ||key=='professionalism'
                        ||key=='courtesy'
                        ||key=='response_time'
                        ||key=='outcome'
                       
                        ){
                            const error =   $("."+key).closest('th')
                            error.find('label').attr('style', 'color:red;')
                        }
                    })
                } else if (typeof res.error_message === 'string') {
                    swal({
                        icon: 'error',
                        title: res.error_message,
                        type: "error",
                        timer: 3000,
                        closeOnConfirm: false,
                        closeOnCancel: false
                    })
                }
            }
        })

      
    })       

JS;
$this->registerJs($script);
?>