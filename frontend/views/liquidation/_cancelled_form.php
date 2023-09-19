<?php

use app\models\AdvancesEntriesForLiquidationSearch;
use app\models\AdvancesEntriesSearch;
use app\models\Payee;
use app\models\PoTransaction;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\helpers\Html;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Liquidation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="liquidation-form">

    <?php
    $check_qry = (new \yii\db\Query())
        ->select([
            'id',
            "CONCAT(check_range.from,' to ',check_range.to) as range"
        ])
        ->from('check_range');
    if (!Yii::$app->user->can('ro_accounting_admin')) {
        $user_data = Yii::$app->memem->getUserData();
        $check_qry->where('province =:province', ['province' => $user_data->office->office_name]);
    }
    $check  = $check_qry->all();
    ?>
    <?php $form = ActiveForm::begin([
        'id' => 'CancelledForm',
    ]); ?>

    <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
        'name' => 'reporting_period',
        'pluginOptions' => [
            'format' => 'yyyy-mm',
            'startView' => 'months',
            'minViewMode' => 'months',
            'autoclose' => true
        ]
    ]) ?>



    <?= $form->field($model, 'check_date')->widget(DatePicker::class, [
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'autoclose' => true
        ]
    ]) ?>
    <?= $form->field($model, 'check_range_id')->widget(Select2::class, [
        'data' => ArrayHelper::map($check, 'id', 'range'),
        'pluginOptions' => [
            'placeholder' => 'Select Check Range',
        ]
    ]) ?>
    <?= $form->field($model, 'check_number')->textInput() ?>

        <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>
</div>
<style>
    table,
    tr {
        max-width: 100%;
    }

    td {
        padding: 5px;
    }

    .total_row {
        text-align: center;
        font-weight: bold;
    }

    .liquidation-form {
        padding: 2rem;
        background-color: white;
    }

    .grid-view td {
        white-space: normal;
        width: 10rem;
        padding: 0;
    }

    #add {
        width: 100%;
    }

    .form-control {
        border-radius: 5px;
    }

    #save {
        width: 100%;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .select2-container--krajee .select2-selection--single .select2-selection__arrow {
        border-left: none;
    }

    .select2-container .select2-selection--single {

        height: 34px;
    }

    .amount {
        margin-top: 15px;


    }
</style>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);

?>


<script>
    $(document).ready(function() {



    })
</script>


<?php
$script = <<<JS
    // ON change transction dropdown



    // SAVE DATA TO DATABASE
    // $('#save_data').submit(function(e) {
    //     e.preventDefault();
    //     // $('#save').attr('disabled',true)
    //     $.ajax({
    //         type: 'POST',
    //         url: window.location.pathname + '?r=liquidation/cancelled-form',
    //         data: $('#save_data').serialize(),
    //         success: function(data) {
    //             console.log(data)
    //             var res = JSON.parse(data)
    //             console.log(res.id)
    //             // addToTransactionTable(res)
    //             if (res.isSuccess){
    //                 swal({
    //                     title:'Success',
    //                     type:'success',
    //                     button:false,

    //                 },function(){
    //                     location.reload()
    //                 }

       
    //                 )
    //             }
    //             else{
    //                 swal({
    //                     title:res.error,
    //                     type:'error',
    //                     button:false,

    //                 })
    //             }

    //         }
    //     })
    // })

            
JS;
$this->registerJs($script);
?>
<?php
SweetAlertAsset::register($this);
$js = <<< JS
    $("#CancelledForm").on("beforeSubmit", function (event) {
        event.preventDefault();
        var form = $(this);
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: form.serialize(),
            success: function (data) {
                let res = JSON.parse(data)
                if (res.isSuccess){
                        
                    swal({
                        icon: 'success',
                        title: 'Successfuly Save',
                        type: "success",
                        timer: 3000,
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },function(){
                        location.reload()
                    })
                }else{

                swal({
                    icon: 'error',
                    title: res.error_message,
                    type: "error",
                    timer: 3000,
                    closeOnConfirm: false,
                    closeOnCancel: false
                })
              }

            },
            error: function (data) {
        
            }
        });
        return false;
    });
JS;
$this->registerJs($js);
?>