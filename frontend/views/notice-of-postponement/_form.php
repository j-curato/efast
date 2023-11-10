<?php

use yii\helpers\Html;
use yii\web\JsExpression;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use yii\bootstrap4\ActiveForm;
use kartik\select2\Select2Asset;
use app\components\helpers\MyHelper;
use app\models\BacComposition;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\NoticeOfPostponement */
/* @var $form yii\widgets\ActiveForm */

$bacMembers = BacComposition::getBacMembersByOffice('ro');
?>

<div class="notice-of-postponement-form" id="main">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>

    <div class="container card p-4">


        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($model, 'to_date')->widget(DateTimePicker::class, [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd hh:ii ',
                        'autoclose' => true
                    ]
                ]) ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'type')->dropDownList(['1' => 'NON-QUORUM', '2' => 'SHORT_PERIOD_OF_TIME'], [
                    'prompt' => 'Select Type',
                ]) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'fk_bac_composition_member_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map($bacMembers, 'id', 'employee_name'),
                    'options' => ['placeholder' => 'Search for a Employee ...'],
                ]) ?>
            </div>
        </div>

        <div class="row justify-content-end">
            <div class="form-group col-sm-2">
                <button type="button" class="btn btn-success" @click='addItem()'><i class="fa fa-plus"></i> Add</button>
            </div>
        </div>
        <table class="table">
            <thead>
                <th>RFQ Number</th>
                <th>From Date</th>
                <th>to Date</th>
                <td></td>
            </thead>
            <tbody>
                <tr v-for="(item,idx) in items" :key='idx'>
                    <td>
                        <select :name="'items[' + idx + '][fk_rfq_id]'" class="form-control rfq-select" style="width: 100%;">
                            <option disabled selected v-if="!item.fk_rfq_id">Search RFQ No.</option>
                            <option selected v-if="item.fk_rfq_id" :value='item.fk_rfq_id'>{{item.rfq_number}}</option>
                        </select>
                    </td>

                    <td>
                        <input type="date" :name="'items['+idx+'][from_date]'" class="form-control" v-model="item.from_date">
                    </td>
                    <!-- <td>
                        <input type="date" :name="'items['+idx+'][to_date]'" class="form-control" v-model="item.to_date">
                    </td> -->
                    <td>
                        <button type="button" @click='removeRow(idx)' class=" btn-xs btn-danger"><i class="fa fa-times"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="row justify-content-center">

            <div class="form-group col-sm-2">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <!-- <vuejs-datepicker ></vuejs-datepicker> -->
</div>
<?php
$csrfToken = Yii::$app->request->csrfToken;
Select2Asset::register($this);
?>
<script>
    function RfqSelect() {
        $(".rfq-select").select2({
            ajax: {
                url: window.location.pathname + "?r=pr-rfq/search-nop-rfq",
                dataType: "json",
                data: function(params) {
                    return {
                        q: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function(data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data.results,
                        pagination: data.pagination,
                    };
                },
            },
        });
    }
    $(document).ready(function() {
        // Vue.component('vuejs-datepicker', vuejsDatepicker);
        new Vue({
            el: '#main',

            data: {
                items: <?= json_encode($model->getItemsA()) ?? [] ?>,

            },
            watch: {


            },
            updated() {

            },
            mounted() {},
            computed: {},
            updated() {
                RfqSelect()
            },
            methods: {

                addItem() {
                    const defaultFromDate = new Date('2021-10-10');
                    this.items.push({
                        rfq_id: null,
                        // from_date: defaultFromDate.toISOString().substr(0, 10),
                        from_date: '',
                        to_date: '',

                    })
                },
                removeRow(index) {

                    this.items.splice(index, 1)
                }

            },
            filters: {
                uppercase(value) {
                    return value.toUpperCase();
                },
            }




        })
    })
</script>

<?php
SweetAlertAsset::register($this);
$js = <<< JS

    $('#NoticeOfPostponement').on('beforeSubmit',function(e){
        e.preventDefault()
        const form  =$(this)
        $.ajax({
            url:form.attr('action'),
            type:form.attr('method'),
            data:form.serialize(),
            success:function(data){
                swal({
                    icon:'error',
                    title:data,
                    timer: 3000,
                    closeOnConfirm: false,
                    closeOnCancel: false
                })
            }
        })
        return false;
    })
JS;
$this->registerJs($js);
?>