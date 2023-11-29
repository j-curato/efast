<?php

use app\models\Barangays;
use app\models\Municipalities;
use app\models\Office;
use yii\helpers\Html;
use yii\web\JsExpression;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Provinces;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model app\models\Mgrfrs */
/* @var $form yii\widgets\ActiveForm */

$defaultBankBranchDetail = !empty($model->fk_bank_branch_detail_id) ?
    $defaultBankBranchDetail = [
        $model->fk_bank_branch_detail_id => strtoupper($model->bankBranchDetail->bankBranch->bank->name . ' - ' .  $model->bankBranchDetail->bankBranch->branch_name)
    ] : [];

?>

<div class="mgrfrs-form card p-2 container-fluid" id="main">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row justify-content-center">
        <div class="col-sm-4">
            <?= $form->field($model, 'organization_name')->textInput(['maxlength' => true]) ?>
        </div>
        <?php if (Yii::$app->user->can('select_mgrfr_office')) : ?>
            <div class="col-sm-2">
                <?= $form->field($model, 'fk_office_id')->dropdownList(
                    ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                    [
                        'prompt' => 'Select Office'
                    ]
                ) ?>
            </div>
        <?php endif; ?>
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

        <div class="col-sm-4">
            <?= $form->field($model, 'authorized_personnel')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'contact_number')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4"> <?= $form->field($model, 'email_address')->textInput(['maxlength' => true]) ?></div>
        <div class="col-sm-4">
            <?= $form->field($model, 'fk_bank_branch_detail_id')->widget(Select2::class, [
                'data' => $defaultBankBranchDetail,
                'options' => ['placeholder' => 'Search for a Bank ...', 'style' => 'height:30em'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Yii::$app->request->baseUrl . '?r=bank-branch-details/search-bank-branch-details',
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {q:params.term,page:params.page}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],

            ]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'saving_account_number')->textInput(['maxlength' => true]) ?>
        </div>

    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'investment_type')->textarea(['rows' => 4]) ?>

        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'investment_description')->textarea(['rows' => 4]) ?>

        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'project_objective')->textarea(['rows' => 4]) ?>

        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'project_beneficiary')->textarea(['rows' => 4]) ?>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <?= $form->field($model, 'project_consultant')->textInput() ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'matching_grant_amount')->widget(MaskMoney::class, [
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
    </div>


    <div class="row justify-content-center">
        <div class="form-group col-sm-2 pt-3">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success  w-100',]) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<?php
$csrfToken = Yii::$app->request->csrfToken;
?>
<script>
    new Vue({
        el: '#main',
        data: {
            province_id: '<?= !empty($model->fk_province_id) ? $model->fk_province_id : '' ?>',
            municipality_id: '<?= !empty($model->fk_municipality_id) ? $model->fk_municipality_id : '' ?>'
        },
        mounted() {

        },
        methods: {

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