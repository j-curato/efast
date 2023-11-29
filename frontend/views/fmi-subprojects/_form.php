<?php

use yii\helpers\Html;
use app\models\Barangays;
use app\models\Provinces;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Municipalities;
use app\models\Office;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\FmiSubprojects */
/* @var $form yii\widgets\ActiveForm */

$batchData = [
    ['id' => $model->fk_fmi_batch_id ?? null, 'batch_name' => $model->fmiBatch->batch_name ?? null],
];
?>

<div class="fmi-subprojects-form " id="mainVue">

    <?php $form = ActiveForm::begin(); ?>
    <div class="card p-3">

        <div class="row">
            <div class="col-sm-2">
                <?= $form->field($model, 'fk_office_id')->dropdownList(
                    ArrayHelper::map(Office::getOfficesA(), 'id', 'office_name'),
                    [
                        'prompt' => 'Select Office',
                    ]
                ) ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'fk_province_id')->dropdownList(
                    ArrayHelper::map(Provinces::find()->where('fk_region_id = 16')->asArray()->all(), 'id', 'province_name'),
                    [
                        'prompt' => 'Select Province',
                        '@change' => 'getMunicipalities()',
                        'v-model' => 'province_id'
                    ]
                ) ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'fk_municipality_id')->dropDownList(
                    !empty($model->fk_province_id) ? ArrayHelper::map(Municipalities::getMunicipalitiesByProvinceId($model->fk_province_id), 'id', 'municipality_name') : [],
                    [
                        'prompt' => 'Select City/Municipality',
                        '@change' => 'getBarangays()',
                        'v-model' => 'municipality_id'
                    ]
                ) ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'fk_barangay_id')->dropDownList(
                    !empty($model->fk_municipality_id) ? ArrayHelper::map(Barangays::getBarangaysByMunicipalityId($model->fk_municipality_id), 'id', 'barangay_name') : [],
                    ['prompt' => 'Select Barangay']
                ) ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'purok')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-3">
                <?= $form->field($model, 'fk_fmi_batch_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map($batchData, 'id', 'batch_name'),
                    'options' => ['placeholder' => 'Search for a Bank ...', 'style' => 'height:30em'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Url::to(['fmi-batches/search-fmi-batch']),
                            'dataType' => 'json',
                            'delay' => 250,
                            'data' => new JsExpression('function(params) { return {text:params.term,page:params.page}; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    ],

                ]) ?>
            </div>
            <div class="col-3">
                <?= $form->field($model, 'project_duration')->textInput(['type' => 'number']) ?>

            </div>
            <div class="col-3">
                <?= $form->field($model, 'project_road_length')->textInput() ?>

            </div>
            <div class="col-3">
                <?= $form->field($model, 'project_start_date')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true,
                        'autoclose' => true,
                    ]
                ]) ?>
            </div>

            <div class="col-sm-3">
                <?= $form->field($model, 'grant_amount')->widget(MaskMoney::class, [
                    'pluginOptions' => [
                        'prefix' => '₱ ',
                        'allowNegative' => false
                    ]
                ]) ?>
            </div>

            <div class="col-sm-3">
                <?= $form->field($model, 'equity_amount')->widget(MaskMoney::class, [
                    'pluginOptions' => [
                        'prefix' => '₱ ',
                        'allowNegative' => false
                    ]
                ]) ?>
            </div>
            <div class="col-3">
                <?= $form->field($model, 'bank_account_name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-3">
                <?= $form->field($model, 'bank_account_number')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <table>
            <thead>
                <th class="text-center">Organization Name</th>
                <th class="text-center">
                    <button type="button" @click="addProjectOrganization" class="btn-xs btn-success"><i class="fa fa-plus"></i> Add</button>
                </th>
            </thead>
            <tbody>
                <tr v-for="(item,index) in project_organizations" :key="index">
                    <td class="d-none"><input type="text" v-model="item.id" v-if="item.id" :name="'items['+index+'][id]'" class="form-control"></td>
                    <td class="text-center"><input type="text" v-model="item.organization_name" :name="'items['+index+'][organization_name]'" class="form-control"></td>
                    <td class="text-center"><button class="btn-xs btn-danger" @click="removeItem(index)" type="button"><i class="fa fa-times"></i></button></td>
                </tr>
            </tbody>
        </table>
        <div class="row justify-content-center">
            <div class="form-group m-2">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<?php
$csrfToken = Yii::$app->request->csrfToken;
$items  =     $model->getFmiSubprojectOrganizationsA(['id', 'organization_name']);
?>
<script>
    new Vue({
        el: '#mainVue',
        data: {
            province_id: '<?= !empty($model->fk_province_id) ? $model->fk_province_id : '' ?>',
            municipality_id: '<?= !empty($model->fk_municipality_id) ? $model->fk_municipality_id : '' ?>',
            project_organizations: <?= !empty($items) ? json_encode($items) : json_encode([]) ?>
        },
        mounted() {

            console.log(this.project_organizations)
        },
        methods: {
            removeItem(index) {
                this.project_organizations.splice(index, 1);
            },
            addProjectOrganization() {
                this.project_organizations.push({
                    'organization_name': ''
                })
            },
            getMunicipalities() {
                const url = window.location.pathname + '?r=municipalities/get-municipalities'
                const data = {
                    _csrf: '<?= $csrfToken ?>',
                    id: this.province_id
                }
                axios.post(url, data)
                    .then(response => {
                        const data = response.data
                        const select2Element = $('#<?= Html::getInputId($model, 'fk_municipality_id') ?>');
                        select2Element.html('')
                        select2Element.append('<option   value="">Select City/Municipality</option>')
                        data.map((item) => {

                            const newOption = new Option(item.municipality_name, item.id, false, false);
                            select2Element.append(newOption);

                        })
                        select2Element.trigger('change');

                    })
                    .catch(err => {
                        console.log(err)
                    })
            },
            getBarangays() {

                const url = window.location.pathname + '?r=barangays/get-barangays'
                const data = {
                    _csrf: '<?= $csrfToken ?>',
                    id: this.municipality_id
                }
                axios.post(url, data)
                    .then(response => {
                        const data = response.data
                        const select2Element = $('#<?= Html::getInputId($model, 'fk_barangay_id') ?>');
                        select2Element.html('')
                        select2Element.append('<option   value="">Select Barangay</option>')
                        data.map((item) => {

                            const newOption = new Option(item.barangay_name, item.id, false, false);
                            select2Element.append(newOption);

                        })
                        select2Element.trigger('change');

                    })
                    .catch(err => {
                        console.log(err)
                    })
            },
        },
    });
</script>