<?php

use app\models\Office;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use Mpdf\Tag\Select;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BacComposition */
/* @var $form yii\widgets\ActiveForm */

$row_number = 0;
?>

<div class="bac-composition-form">

    <div class="container panel panel-primary">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">

            <div class="col-sm-3">
                <?= $form->field($model, 'effectivity_date')->widget(
                    DatePicker::class,
                    [
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'autoclose' => true

                        ],
                        'options' => [
                            'readonly' => true,
                            'style' => 'background-color:white'
                        ]
                    ]
                ) ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'expiration_date')->widget(
                    DatePicker::class,
                    [
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'autoclose' => true

                        ],
                        'options' => [
                            'readonly' => true,
                            'style' => 'background-color:white'
                        ]
                    ]
                ) ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'fk_office_id')->widget(
                    Select2::class,
                    [
                        'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                        'pluginOptions' => [
                            'placeholder' => 'Select Office'

                        ],

                    ]
                ) ?>
            </div>

        </div>





        <?= $form->field($model, 'rso_number')->textInput(['maxlength' => true]) ?>

        <table class="table" id="form_fields_data">
            <thead>
                <tr>
                    <th colspan="2" style="text-align: center;">
                        <h3>Members</h3>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php

                if (!empty($model->bacCompositionMembers)) {
                    foreach ($model->bacCompositionMembers as $i => $val) {
                        $emp_name = "{$val->employee->f_name} {$val->employee->m_name[0]}. {$val->employee->l_name}";

                        echo "<tr class='panel  panel-default' style='margin-top: 2rem;margin-bottom:2rem;'>
                    <td style='max-width:100rem;'>
    
                        <div class='row'>
                            <div class='col-sm-6'>
                                <label for='employee'>Employee</label>
                                <select required name='employee_id[$i]' class='employee form-control' style='width: 100%'>
                                <option value= '$val->employee_id'>{$emp_name}</option>

                                </select>
                            </div>
                   
                            <div class='col-sm-6'>
                                <label for='employee'>Position</label>
                                <select required name='position[$i]' class='position form-control' style='width: 100%'>
                                <option value= '$val->bac_position_id'>{$val->bacPosition->position}</option>
                                </select>
                            </div>
                        </div>
                    </td>
                    <td style='  text-align: center;'>
                        <div class='pull-left'>
                            <button class='add_new_row btn btn-primary btn-xs' type='button'><i class='fa fa-plus fa-fw'></i> </button>
                            <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan='2'>
                        <hr>
                    </td>
                </tr>";
                        $row_number++;
                    }
                } else {
                ?>
                    <tr class="panel  panel-default" style="margin-top: 2rem;margin-bottom:2rem;">
                        <td style="max-width:100rem;">

                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="employee">Employee</label>
                                    <select required name="employee_id[0]" class="employee form-control" style="width: 100%">
                                        <option></option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="employee">Position</label>
                                    <select required name="position[0]" class="position form-control" style="width: 100%">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td style='  text-align: center;'>
                            <div class='pull-left'>
                                <button class='add_new_row btn btn-primary btn-xs' type='button'><i class='fa fa-plus fa-fw'></i> </button>
                                <a class='remove_this_row btn btn-danger btn-xs disabled' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <hr>
                        </td>
                    </tr>
                <?php } ?>


            </tbody>
        </table>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
<style>
    .container {
        padding: 3rem;
    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile(yii::$app->request->baseUrl . "/css/select2.min.css", ['depends' => [\yii\web\JqueryAsset::class]]);

?>
<script type="text/javascript">
    function stockSelect() {
        $('.employee').select2({
            ajax: {
                url: window.location.pathname + '?r=employee/search-employee',
                dataType: 'json',
                data: function(params) {

                    return {
                        q: params.term,
                    };
                },
                processResults: function(data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data.results
                    };
                },
            },

        });
    }

    function maskAmount() {

        $('.amount').maskMoney({
            allowNegative: true
        });

    }

    function positionSelect() {
        $('.position').select2({
            data: bac_positions,
            placeholder: "Select Position",

        })
    }

    var bac_positions = []
    $(document).ready(function() {
        let row_number = <?php echo $row_number ?>;
        $.getJSON(window.location.pathname + '?r=bac-position/get-position')
            .then(function(data) {
                var array = []
                $.each(data, function(key, val) {
                    array.push({
                        id: val.id,
                        text: val.position
                    })
                })
                bac_positions = array
                positionSelect()

            });


        maskAmount()
        stockSelect()
        $('.remove_this_row').on('click', function(event) {
            event.preventDefault();
            $(this).closest('tr').next().remove();
            $(this).closest('tr').remove();
        });
        $('.add_new_row').on('click', function(event) {
            event.preventDefault();
            $('.employee').select2('destroy');
            $('.position').select2('destroy');
            $('.unit_cost').maskMoney('destroy');
            var source = $(this).closest('tr');;
            var clone = source.clone(true);
            clone.children('td').eq(0).find('.employee').val('')
            clone.children('td').eq(0).find('.employee').attr('name', 'employee_id[' + row_number + ']')
            clone.children('td').eq(0).find('.position').val('')
            clone.children('td').eq(0).find('.position').attr('name', 'position[' + row_number + ']')
            $('#form_fields_data').append(clone);
            var spacer = `<tr>
                        <td colspan="2" >
                          <hr>
                        </td>
                    </tr>`
            $('#form_fields_data').append(spacer);
            clone.find('.remove_this_row').removeClass('disabled');
            stockSelect()
            positionSelect()
            maskAmount()
            row_number++
        });





    });
</script>