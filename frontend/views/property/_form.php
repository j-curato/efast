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

    <?php $form = ActiveForm::begin(); ?>


    
    <?= $form->field($model, 'date')->widget(DatePicker::class, [
        'name' => 'date',
        'pluginOptions' => [
            'placeholder' => 'Select Date',
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'

        ]
    ]) ?>
    <?= $form->field($model, 'book_id')->widget(Select2::class, [
        'name' => 'book_id',
        'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
        'pluginOptions' => [
            'placeholder' => 'Select Book'
        ]
    ]) ?>

    <?= $form->field($model, 'unit_of_measure_id')->widget(Select2::class, [
        'name' => 'unit_of_measure_id',
        'data' => ArrayHelper::map(UnitOfMeasure::find()->asArray()->all(), 'id', 'unit_of_measure'),
        'pluginOptions' => [
            'placeholder' => 'Select Unit of Measure'
        ]
    ]) ?>

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

    <?= $form->field($model, 'iar_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'article')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'serial_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

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

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>