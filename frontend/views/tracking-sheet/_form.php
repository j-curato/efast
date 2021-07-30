<?php

use app\models\Payee;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TrackingSheet */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tracking-sheet-form">

    <form id='tracking_form' style="padding: 30px;">
        <?php
        $id = '';
        if (!empty($model)){
            $id = $model->id;
        }
        echo "<input type='text' name='update_id' id='update_id' value='$id' style='display:none'/>";
        ?>
        <div class="row">
            <label for="transaction_type">Transaction Type</label>
            <select required id="transaction_type"  name="transaction_type" class="transaction_type select" style="width: 100%; margin-top:50px">
                <option></option>
            </select>
        </div>
        <div class="row">
            <label for="ors">ORS</label>
            <select id="ors" name="ors" class="ors select" style="width: 100%; margin-top:50px">
                <option></option>
            </select>
        </div>

        <div class="row">
            <label for="payee">Payee</label>
            <select required id="payee" name="payee" class="payee select" style="width: 100%; margin-top:50px">
                <option></option>
            </select>

        </div>

        <div class="row">
            <label for="particular">Particular</label>
            <textarea name="particular" id="particular" style="width: 100%; max-width:100%" rows="10"></textarea>
        </div>

        <button class="btn btn-success" type="submit">Save</button>
    </form>




</div>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/scripts.js", ['depends' => [\yii\web\JqueryAsset::class]]);
SweetAlertAsset::register($this);

?>

<?php
$script = <<<JS

    // var ors_id =$('#ors').val()
    // $('#transaction_type').change(function(){
    //     if ($('#transaction_type').val()!='Single'){
    //         $('#ors').val('').trigger('change')
    //         $('#ors').prop('disabled',true)
    //     }
    //     else{
    //         $('#ors').prop('disabled',false)
    //     }
    // })
    $("#ors").change(()=>{
        console.log($('#ors').val())

        if ($('#transaction_type').val() =='Single'){
            console.log('single')
            $.ajax({
                type:'POST',
                url:window.location.pathname +'?r=tracking-sheet/get-ors',
                data:{id:$('#ors').val()},
                success:function(data){
                    var res = JSON.parse(data)
                    $('#payee').val(res.payee_id).trigger('change')
                    $('#particular').val(res.particular).trigger('change')
                }
            })
        }
    })
    
    // save to database
    $('#tracking_form').submit((e)=>{
        e.preventDefault();
        
        $.ajax({
                type:'POST',
                url:window.location.pathname +'?r=tracking-sheet/create',
                data:$('#tracking_form').serialize(),
                success:function(data){
                    var res = JSON.parse(data)
                    console.log(res)
                    if (res.isSuccess){
                        swal({
                            title:'success',
                            type:'success',
                            button:false,
                            timer:6000

                        },function(){
                            window.location.href= window.location.pathname + '?r=tracking-sheet/view&id='+res.id
                        })
                    }
           

                }
            })
    })
    var all_ors=[]
    var update_id = $('#update_id').val()
    $(document).ready(()=>{
        getOrs(update_id).then(function (data) {
            var array = []
            $.each(data, function (key, val) {
                array.push({
                    id: val.id,
                    text: val.serial_number
                })
            })
            all_ors = array
            $('#ors').select2({
                data: all_ors,
                placeholder: "Select ORS No.",
                // allowClear:true

            })

        });
        var transaction = ["Single", "Multiple","No Ors"]
            $('#transaction_type').select2({
                data: transaction,
                placeholder: "Select transaction",

            })

        if ($("#update_id").val()!=''){
            $.ajax({
                type:'POST',
                url:window.location.pathname +'?r=tracking-sheet/update-sheet',
                data:{id:$('#update_id').val()},
                success:function(data){
                    var res = JSON.parse(data)
                    console.log(res[''])
                    $("#transaction_type").val(res['transaction_type']).trigger('change')
                    $("#ors").val(res['process_ors_id']).trigger('change')
                    $("#payee").val(res['payee_id']).trigger('change')
                    $("#particular").val(res['transaction_type'])
                }
            })
        } 
    })

JS;
$this->registerJs($script);
?>