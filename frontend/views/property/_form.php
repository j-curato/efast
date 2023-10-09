<?php

use yii\db\Query;
use Mpdf\Tag\Select;
use app\models\Books;
use yii\helpers\Html;
use app\models\Office;
use common\models\User;
use app\models\Employee;
use app\models\SsfSpNum;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\UnitOfMeasure;
use yii\bootstrap4\ActiveForm;
use app\models\PropertyArticles;
use app\components\helpers\MyHelper;
use aryelds\sweetalert\SweetAlertAsset;


/* @var $this yii\web\View */
/* @var $model app\models\Property */
/* @var $form yii\widgets\ActiveForm */

$ssf_sp = [];
if (!empty($model->fk_ssf_sp_num_id)) {
    $ssf_sp = ArrayHelper::map(SsfSpNum::find()->where('id = :id', ['id' => $model->fk_ssf_sp_num_id])->asArray()->all(), 'id', 'serial_number');
}

$property_custodian_query = new Query();
$property_custodian_query->select(['employee_id', 'UPPER(employee_name) as employee_name'])
    ->from('employee_search_view')
    ->andWhere('property_custodian  = 1');
if (!Yii::$app->user->can('ro_property_admin')) {
    $user_data = User::getUserDetails();
    $property_custodian_query->andWhere('office_name =:office_name', ['office_name' => $user_data->employee->office->office_name]);
}
$f_property_custodian_query = $property_custodian_query->all();
// Yii::$app->db->createCommand("SELECT 
// employee_id ,
// CONCAT(f_name,' ',LEFT(m_name,1),'. ' , l_name) as `text`
// FROM 
// employee
// WHERE employee.property_custodian  = 1
// ")
//     ->queryAll();
$property_custodians = ArrayHelper::map(MyHelper::getPropertyCustodians(), 'employee_id', 'employee_name');
$article = [];
if (!empty($model->fk_property_article_id)) {

    $article = ArrayHelper::map(
        PropertyArticles::find()->where("id =:id", ['id' => $model->fk_property_article_id])->all(),
        'id',
        'article_name'
    );
}

?>

<div class="property-form">

    <?php $form = ActiveForm::begin([
        'id' => $model->FormName(),
    ]); ?>

    <?php

    if (Yii::$app->user->can('ro_property_admin')) {

        echo $form->field($model, 'fk_office_id')->widget(Select2::class, [
            'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
            'pluginOptions' => [
                'placeholder' => 'Select Province'
            ]
        ]);
    }
    ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'name' => 'date',
                'pluginOptions' => [
                    'placeholder' => 'Select Date',
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'

                ]
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'unit_of_measure_id')->widget(Select2::class, [
                'name' => 'unit_of_measure_id',
                'data' => ArrayHelper::map(UnitOfMeasure::find()->asArray()->all(), 'id', 'unit_of_measure'),
                'pluginOptions' => [
                    'placeholder' => 'Select Unit of Measure'
                ]
            ]) ?>

        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'fk_ssf_sp_num_id')->widget(Select2::class, [

                'data' => $ssf_sp,
                'options' => [
                    'placeholder' => 'Search for a SSF SP No. ...',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Yii::$app->request->baseUrl . '?r=ssf-sp-num/search-ssf-sp',
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { 
                                return {
                                    q:params.term,
                                    page: params.page||1,
                                }; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],

            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'is_ssf')->widget(Select2::class, [
                'data' => [
                    '0' => 'non-SSF',
                    '1' => 'SSF'
                ],
                // 'pluginOptions' => [
                //     'placeholder' => 'Select PPE Type'
                // ]

            ]) ?>

        </div>
    </div>




    <?php
    $description = '';
    if (!empty($model->property_number)) {
        $description = !empty($model->description) ? preg_replace('#\[n\]#', "\n", $model->description) : '';;
    }
    ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'employee_id')->widget(Select2::class, [
                'options' => ['placeholder' => 'Search for a Employee ...'],
                'data' => $property_custodians,
                'pluginOptions' => [
                    'allowClear' => true,

                ],

            ]) ?>
        </div>
        <div class="col-sm-6"> <?= $form->field($model, 'serial_number')->textInput() ?></div>
    </div>


    <?= $form->field($model, 'fk_property_article_id')->widget(Select2::class, [
        'data' => $article,
        'options' => [
            'placeholder' => 'Search for a Article ...',
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => Yii::$app->request->baseUrl . '?r=property-articles/search-article',
                'dataType' => 'json',
                'delay' => 250,
                'data' => new JsExpression('function(params) { 
                               return {
                                   q:params.term,
                                   page: params.page||1,
                               }; }'),
                'cache' => true
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
            'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
        ],
    ]) ?>
    <?= $form->field($model, 'description')->textarea(['maxlength' => true, 'style' => 'display:none;']) ?>
    <textarea id="description" cols="30" rows="5" style="max-width:100%;width:100%"><?php echo $description; ?></textarea>
    <?php

    if (empty($model->id)) {
        echo  $form->field($model, 'acquisition_amount')->widget(
            MaskMoney::class,
            [
                'options' => [
                    'class' => 'amounts',
                ],
                'pluginOptions' => [
                    'prefix' => '₱ ',
                    'allowNegative' => false
                ],
            ]
        );
    }
    ?>

    <div class="row justify-content-center">
        <div class="form-group col-sm-2">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
        </div>

    </div>


    <?php ActiveForm::end(); ?>

</div>
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
SweetAlertAsset::register($this);
$js = <<< JS
    $("#Property").on("beforeSubmit", function (event) {
        event.preventDefault();
        var form = $(this);
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: form.serialize(),
            success: function (data) {
                let res = JSON.parse(data)
                console.log(res)
                swal({
                    icon: 'error',
                    title: res.error,
                    type: "error",
                    timer: 3000,
                    closeOnConfirm: false,
                    closeOnCancel: false
                })
            },
            error: function (data) {
        
            }
        });
        return false;
    });
JS;
$this->registerJs($js);
?>