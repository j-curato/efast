<?php

use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\SupervisorValidationNotes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="supervisor-validation-notes-form container panel panle-default" style="background-color: white;padding:3rem">




    <?php $form = ActiveForm::begin([
        'id' => $model->formName(),
    ]); ?>


    <?= $form->field($model, 'employee_name')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'evaluation_period')->radioList(
        ['first_sem' => '1st Semester', 'annual' => 'Annual'],
        ['custom' => true, 'inline' => true, 'id' => 'custom-radio-list-inline']
    ) ?>

    <?= $form->field($model, 'ttl_suc_msr')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valid_msr')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'accomplishments')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pgs_rating')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>



    <?php

    // echo $form->field($model, 'attributeName')->widget(RadioList::class(), [
    //     'data' => [
    //         'Option 1' => 'Option 1 Label',
    //         'Option 2' => 'Option 2 Label',
    //         'Option 3' => 'Option 3 Label',
    //     ],
    //     'item' => function ($index, $label, $name, $checked, $value) {
    //         return "<td>$label</td><td><input type='radio' name='{$name}' value='{$value}' " . ($checked ? 'checked' : '') . "></td>";
    //     },
    //     'inline' => true
    // ]);


    ?>


    <label for="table">8. Staff Demonstration of Competencies (1 being the lowest and 5 being the highest) *</label>
    <table class="table num_8">
        <tr>
            <td></td>
            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
            <td>5</td>
        </tr>

        <tr>
            <th>
                <div class="int_gbl_olk"></div>

                <?= $form->field($model, 'int_gbl_olk')->radioList(
                    ['1' => '', '2' => '', '3' => '', '4' => '', '5' => ''],
                    [
                        'custom' => true,
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return "<td><input id='supervisorvalidationnotes-int_gbl_olk' type='radio' name='{$name}' value='{$value}' " . ($checked ? 'checked' : '') . "></td>";
                        },


                    ]
                ) ?>
            </th>
        </tr>
        <tr>

            <th>
                <div class="del_solution"></div>
                <?= $form->field($model, 'del_solution')->radioList(
                    ['1' => '', '2' => '', '3' => '', '4' => '', '5' => ''],
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
                <?= $form->field($model, 'net_link')->radioList(
                    ['1' => '', '2' => '', '3' => '', '4' => '', '5' => ''],
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
                <?= $form->field($model, 'del_exl_res')->radioList(
                    ['1' => '', '2' => '', '3' => '', '4' => '', '5' => ''],
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
                <?= $form->field($model, 'collaborating')->radioList(
                    ['1' => '', '2' => '', '3' => '', '4' => '', '5' => ''],
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
                <div class="agility"></div>
                <?= $form->field($model, 'agility')->radioList(
                    ['1' => '', '2' => '', '3' => '', '4' => '', '5' => ''],
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
    <br>

    <label for="num_9">9. Staff Demonstration of Core Values (1 being the lowest and 5 being the highest) *</label>
    <table class="table num_9">
        <tr>
            <th></th>
            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
            <td>5</td>
        </tr>
        <th>
            <div class="proflsm_int"></div>

            <?= $form->field($model, 'proflsm_int')->radioList(
                ['1' => '', '2' => '', '3' => '', '4' => '', '5' => ''],
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
        <tr>
            <th>
                <div class="passion"></div>

                <?= $form->field($model, 'passion')->radioList(
                    ['1' => '', '2' => '', '3' => '', '4' => '', '5' => ''],
                    [
                        'custom' => true,
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return "
                <td><input type='radio' name='{$name}' value='{$value}' " . ($checked ? 'checked' : '') . "></td>";
                        },
                        'inline' => true,
                        'style' => 'margin:0'

                    ]
                ) ?>

            </th>
        </tr>
        <tr>

            <th>
                <div class="integrety"></div>

                <?= $form->field($model, 'integrety')->radioList(
                    ['1' => '', '2' => '', '3' => '', '4' => '', '5' => ''],
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
                <div class="competence"></div>

                <?= $form->field($model, 'competence')->radioList(
                    ['1' => '', '2' => '', '3' => '', '4' => '', '5' => ''],
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
                <div class="creativity"></div>
                <?= $form->field($model, 'creativity')->radioList(
                    ['1' => '', '2' => '', '3' => '', '4' => '', '5' => ''],
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
                <div class="synergy"></div>

                <?= $form->field($model, 'synergy')->radioList(
                    ['1' => '', '2' => '', '3' => '', '4' => '', '5' => ''],
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
                <div class="love_of_country"></div>
                <?= $form->field($model, 'love_of_country')->radioList(
                    ['1' => '', '2' => '', '3' => '', '4' => '', '5' => ''],
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


    <?= $form->field($model, 'dev_intervention')->textarea(['rows' => 6]) ?>


    <div class="row">
        <div class="col-sm-4 col-sm-offset-5">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:15rem']) ?>
            </div>
        </div>
    </div>



    <?php ActiveForm::end(); ?>

</div>
<style>
    .radio-inline {
        padding-left: 15rem;
    }

    label,
    div[role="radiogroup"] {
        display: inline-block;
    }

    div[role="radiogroup"] {
        padding: 0;
    }

    th {
        max-width: 25rem;
        min-width: 25rem;
        width: 25rem;
        padding-right: 3rem;
    }

    .num_8 td,
    .num_9 td {
        text-align: right;
    }

    .table {
        width: 80%;
    }

    textarea {
        max-width: 100%;
    }
</style>
<script>
    $(document).ready(() => {

        // $('.int_gbl_olk').closest('th').append('<div>qwe </div>')
        // $('.int_gbl_olk').closest('th').find('.form-label').attr('style', 'color:red;')
    })
</script>
<?php
SweetAlertAsset::register($this);

$script = <<< JS
function q (){
   const arr = [
    'int_gbl_olk',
    'del_solution',
    'net_link',
    'del_exl_res',
    'collaborating',
    'agility',
    'proflsm_int',
    'passion',
    'integrety',
    'competence',
    'creativity',
    'synergy',
    'love_of_country',
   ];
   $.each(arr,(key,val)=>{
        $("."+val).closest('th').find('.form-label').attr('style', 'color:black;')
   })
}
    $('#SupervisorValidationNotes').on('submit',function(e){
        e.preventDefault()
        var \$form = $(this);
        $.post(
            \$form.attr("action"),
            \$form.serialize()
        )
        .done(function(result){
            q()

            const res = JSON.parse(result)
            if (!res.isSuccess) {

                if (typeof res.error_message === 'object') {
                    $.each(res.error_message,(key,val)=>{
                        if (
                        key=='int_gbl_olk'
                        ||key=='del_solution'
                        ||key=='net_link'
                        ||key=='del_exl_res'
                        ||key=='collaborating'
                        ||key=='agility'
                        ||key=='proflsm_int'
                        ||key =='passion'
                        ||key =='integrety'
                        ||key =='competence'
                        ||key =='creativity'
                        ||key =='synergy'
                        ||key =='love_of_country'
                        ){
                            const error =   $("."+key).closest('th')
                            error.find('.form-label').attr('style', 'color:red;')
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