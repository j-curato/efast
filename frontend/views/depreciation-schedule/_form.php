<?php

use app\models\Books;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\YiiAsset;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\DepreciationSchedule */
/* @var $form yii\widgets\ActiveForm */

$this->registerCssFile("@web/frontend/views/depreciation-schedule/styles.css", ['depends' => YiiAsset::class]);
?>

<div class="depreciation-schedule-form card" style="padding: 1rem;">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-3">

            <?= $form->field($model, 'reporting_period')->widget(
                DatePicker::class,
                ['pluginOptions' => [
                    'format' => 'yyyy-mm',
                    'minViewMode' => 'months',
                    'autoclose' => true
                ]]
            ) ?>
        </div>
        <div class="col-sm-3">

            <?= $form->field($model, 'fk_book_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                'pluginOptions' => [
                    'placeholder' => 'Select Book',
                    'allowClear' => true
                ]
            ]) ?>
        </div>
        <div class="col-sm-3">

            <label for=""></label>
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                <?= Html::button('Generate', ['class' => 'btn btn-primary', 'id' => 'generate']) ?>
            </div>
        </div>
    </div>




    <?php ActiveForm::end(); ?>
    <div class="qwe">

        <table class="table" id="data_tbl">
            <thead>
                <th>Property Number</th>
                <th>Article</th>
                <th>Description</th>
                <th>Date Acquired</th>
                <th>Acquisation Amount</th>
                <th>Book</th>
                <th>Acquisition Cost</th>
                <th>Salvage Value
                    <br>
                    (at least 5% of Cost, rounded to nearest ones)
                </th>
                <th>Depreciable Amount</th>
                <th>1st month of Depn.</th>
                <th>2nd to the last month</th>
                <th> Useful Life in Months</th>
                <th>Monthly Depreciation
                    <br>
                    (from 1st month to 2nd to the last month, rounded to the nearest ones)
                </th>

                <th>
                    Last Month
                </th>
                <th>
                    Monthly Depreciation
                    <br>
                    (Last Month)
                </th>
                <th>
                    Account Title
                </th>

            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

</div>
<style>

</style>
<?php
 
$this->registerJsFile("@web/js/moment.min.js", ['depends' => [JqueryAsset::class]]);
$this->registerJsFile("@web/frontend/views/depreciation-schedule/script.js", ['depends' => [JqueryAsset::class]]);

?>
<script>
    $(document).ready(() => {
        $('#generate').click(() => {
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=depreciation-schedule/generate',
                data: {
                    reporting_period: $('#depreciationschedule-reporting_period').val(),
                    book_id: $('#depreciationschedule-fk_book_id').val(),
                },
                success: (data) => {
                    const res = JSON.parse(data)
                    display(res)
                }
            })
        })
    })
</script>