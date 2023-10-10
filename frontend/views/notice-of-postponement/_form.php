<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2Asset;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $model app\models\NoticeOfPostponement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="notice-of-postponement-form" id="main">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>

    <div class="container card">


        <button type="button" class="btn btn-success" @click='addItem()'>Add</button>
        <table class="table">
            <thead>
                <th>AOQ Number</th>
                <th>From Date</th>
                <th>to Date</th>
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
                    <td>
                        <input type="date" :name="'items['+idx+'][to_date]'" class="form-control" v-model="item.to_date">
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <!-- <vuejs-datepicker ></vuejs-datepicker> -->
</div>
<?php
$csrfToken = Yii::$app->request->csrfToken;
$this->registerJsFile("https://unpkg.com/vuejs-datepicker", ['position' => $this::POS_HEAD]);
Select2Asset::register($this);
?>
<script>
    function RfqSelect() {
        $(".rfq-select").select2({
            ajax: {
                url: window.location.pathname + "?r=pr-rfq/search-rfq",
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
            computed: {

            },
            updated() {
                RfqSelect()
            },
            methods: {

                addItem() {
                    const defaultFromDate = new Date('2021-10-10');
                    this.items.push({
                        rfq_id: null,
                        from_date: defaultFromDate.toISOString().substr(0, 10),
                        to_date: '',

                    })
                    console.log(this.items)
                },

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