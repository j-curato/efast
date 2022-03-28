<?php

use app\models\Books;
use app\models\Employee;
use app\models\UnitOfMeasure;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Property */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="property-form">


    <div class="panel panel-default container">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($model, 'date')->widget(DatePicker::class, [
                    'name' => 'date',
                    'pluginOptions' => [
                        'placeholder' => 'Select Date',
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'

                    ]
                ]) ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'book_id')->widget(Select2::class, [
                    'name' => 'book_id',
                    'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Book'
                    ]
                ]) ?>

            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'unit_of_measure_id')->widget(Select2::class, [
                    'name' => 'unit_of_measure_id',
                    'data' => ArrayHelper::map(UnitOfMeasure::find()->asArray()->all(), 'id', 'unit_of_measure'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Unit of Measure'
                    ]
                ]) ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true]) ?>

            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">


                <?php

                $query = Yii::$app->db->createCommand("SELECT 
            employee_id ,
            CONCAT(f_name,' ',LEFT(m_name,1),'. ' , l_name) as `text`
            FROM 
            employee
            WHERE employee.property_custodian  = 1
        ")
                    ->queryAll();
                $data = ArrayHelper::map($query, 'employee_id', 'text');
                ?>
                <?= $form->field($model, 'employee_id')->widget(Select2::class, [
                    'data' => $data,
                    'pluginOptions' => [
                        'placeholder' => "Select Custodian"
                    ]



                ]) ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>

            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'iar_number')->textInput(['maxlength' => true]) ?>

            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'quantity')->textInput() ?>

            </div>

        </div>
        <?php
        $description = '';
        if (!empty($model->property_number)) {
            $description = !empty($model->description) ? preg_replace('#\[n\]#', "\n", $model->description) : '';;
        }
        ?>
        <?= $form->field($model, 'article')->textarea(['maxlength' => true,]) ?>
        <?= $form->field($model, 'description')->textarea(['maxlength' => true, 'style' => 'display:none;']) ?>
        <textarea id="description" cols="30" rows="5" style="max-width:100%;width:100%"><?php echo $description; ?></textarea>
        <?= $form->field($model, 'acquisition_amount')->widget(
            MaskMoney::class,
            [
                'options' => [
                    'class' => 'amounts',
                ],
                'pluginOptions' => [
                    'prefix' => 'PHP ',
                    'allowNegative' => true
                ],
            ]
        ) ?>


        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($model, 'object_code')->widget(Select2::class, [
                    'name' => 'object_code',
                    'options' => ['placeholder' => 'Search Object Code ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=chart-of-accounts/search-accounting-code',
                            'dataType' => 'json',
                            'delay' => 250,
                            'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    ],

                ])

                ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'salvage_value')->widget(MaskMoney::class, [
                    'name' => 'salvage_value',
                    'pluginOptions' => [
                        'prefix' => '₱ ',
                        'allowNegative' => false
                    ]
                ])
                ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'estimated_life')->textInput(['type' => 'number']) ?>
            </div>

        </div>
        <div class="row" style="margin: 3rem;">
            <div class="col-sm-5"></div>
            <div class="col-sm-2">
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
                </div>
            </div>

            <div class="col-sm-5"></div>

        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
    .panel {
        padding: 20px;
    }
</style>
<script>
    $(document).ready(function() {
        $('#description').on('keyup change', function(e) {
            e.preventDefault()
            var specs = $(this).val()
            specs = specs.replace(/\n/g, "[n]");
            $('#property-description').val(specs)
        })
    })
</script>

<?php
$script = <<< JS

JS;

$this->registerJs($script);
?>