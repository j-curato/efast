<?php

use app\models\Par;
use app\models\TransferType;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Ptr */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ptr-form">

    <div class="panel panel-default">

        <div class="row">
            <div class="col-sm-3">
                <label for="date">Date</label>
                <?php
                $date = '';
                $transfer_type = '';
                if (!empty($model)) {
                    $date = $model->date;
                    $transfer_type = $model->transfer_type;
                }
                echo DatePicker::widget([
                    'name' => 'date',
                    'id' => 'date',
                    'value' => $date,
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose'=>true
                    ]
                ]) ?>
            </div>
            <div class="col-sm-3">
                <label for="transfer_type">Transfer Type</label>
                <?= Select2::widget([
                    'id' => 'transfer_type',
                    'name' => 'transger_type',
                    'value' => $transfer_type,
                    'data' => ArrayHelper::map(TransferType::find()->asArray()->all(), 'id', 'type'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Transfer Type'
                    ]
                ]) ?>
            </div>

            <div class="col-sm-4">

                <label for="par_number">PAR Number</label>

                <?php
                $par = '';
                $par_number  = '';
                if (!empty($model)) {
                    $par_number  = $model->par_number;
                    $par = ArrayHelper::map(Par::find()->where(['par_number' => $model->par_number]), 'par_number', 'par_number');
                }
                ?>
                <?=
                Select2::widget(
                    [
                        'data' => $par,
                        'name' => 'par_number',
                        'value' => [$par_number],
                        'id' => 'par_number',
                        'options' => [
                            'placeholder' => 'Search for a PAR ...',
                        ],

                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 1,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                            ],
                            'ajax' => [
                                'url' => Yii::$app->request->baseUrl . '?r=par/search-par',
                                'dataType' => 'json',
                                'delay' => 250,
                                'data' => new JsExpression('function(params) { return {q:params.term ,province: params.province}; }'),
                                'cache' => true
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(par_number) { return par_number.text; }'),
                            'templateSelection' => new JsExpression('function (par_number) { return par_number.text; }'),
                        ],
                    ]
                )
                ?>
            </div>
        </div>
        <table id="par_table">
            <tr>
                <th>Property Number</th>
                <td id="property_number"></td>
                <th>PAR Number</th>
                <td id="par_no"></td>

            </tr>
            <tr>
                <th>Quantity</th>
                <td id="quantity"></td>
                <th>Amount</th>
                <td id="amount"></td>
            </tr>
            <tr>
                <th>Article</th>
                <td id="article"></td>
                <th>PAR Date</th>
                <td id="par_date"></td>
            </tr>
            <tr>
                <th>Description</th>
                <td>
                    <span>Model:</span>
                    <span id="model"></span>
                    <br>
                    <span>Serial Number:</span>
                    <span id="serial_number"></span>
                    <br>
                    <span>IAR Number:</span>
                    <span id="iar_number"></span>
                    <br>

                </td>
                <th>Recieved By</th>
                <td id="recieved_by"></td>
            </tr>
            <tr>
                <th>Book</th>
                <td id="book"></td>
                <th>Unit of Measure</th>
                <td id="unit_of_measure"></td>
            </tr>

            </tbody>
        </table>


        <div class="row">
            <div class="col-sm-6">
                <label for="from">From </label>
                <select class="from" id='from' style="width: 100%;" name="from">
                    <option></option>
                </select>
            </div>
            <div class="col-sm-6">
                <label for="to">To</label>
                <select class="to" id='to' style="width: 100%;" name="to">
                    <option></option>
                </select>
            </div>
        </div>






    </div>

</div>
<style>
    .panel {
        padding: 20px;
    }

    .to {
        display: none;
    }

    .from {
        display: none;
    }

    table,
    th,
    td {
        border: 1px solid black;
        padding: 12px;
        margin: 20px;
    }

    table {

        width: auto;
    }

    #par_table {
        display: none;
    }

    .property-card-form {
        background-color: white;
        padding: 20px;
    }
</style>
<?php
$js = <<<JS
    var property_card = $('#par_number')
    $('#transfer_type').change(()=>{
        var selectedText = $("#transfer_type option:selected").html();

        if (selectedText.toLowerCase() == 'donation' || selectedText.toLowerCase() == 'relocate'
        || selectedText.toLowerCase() == 'disposal'){
            $.getJSON('/afms/frontend/web/index.php?r=agency/get-agency')
                .then(function(data) {

                    var array = []
                    $.each(data, function(key, val) {
                        array.push({
                            id: val.id,
                            text: val.name
                        })
                    })
                    transaction = array
                    $('#from').select2({
                        data: transaction,
                        placeholder: "Select to",

                    }).show()
                    $('#to').select2({
                        data: transaction,
                        placeholder: "Select to",

                    }).show()

                 });
            
        }
        else{
            $('#from').select2({
                ajax: {
                    url: window.location.pathname + '?r=chart-of-accounts/search-accounting-code',
                    dataType: 'json',
                    data: function(params) {

                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function(data,params) {
                        // Transforms the top-level key of the response object to 'items' to 'results'
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                               more: (params.page * 30) < data.total_count
                            }
                        };
                    }
                },
                placeholder:"Select to ",
                minimumInputLength: 1,
      

            }).show();
            $('#to').select2({
                ajax: {
                    url: window.location.pathname + '?r=chart-of-accounts/search-accounting-code',
                    dataType: 'json',
                    data: function(params) {

                        return {
                            q: params.term,
                            page: params.page
                        };
                    },
                    processResults: function(data,params) {
                        // Transforms the top-level key of the response object to 'items' to 'results'
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                               more: (params.page * 30) < data.total_count
                            }
                        };
                    }
                },
                placeholder:"Select to ",
                minimumInputLength: 1,
      

            }).show();
     
        }
        changeFrom()
    })
    var employee_data=[]
    var employee_id=''
    var employee_name=''
    var agency_id = ''
    function changeFrom(){
        var fromSelect = $('.from');
       const transfer_type =  $("#transfer_type option:selected").html()
       console.log(agency_id)
       if (transfer_type.toLowerCase() == 'donation'
       || transfer_type.toLowerCase() == 'relocate'
       ||transfer_type.toLowerCase() == 'disposal'){
            fromSelect.val(agency_id).trigger('change');
       }
       else{
        var option = new Option( [employee_name],[employee_id], true, true);
        fromSelect.append(option).trigger('change');
        fromSelect.trigger({
            type: 'select2:select',
            params: {
            data: employee_data
            }
        });
       }
    }
    property_card.change(()=>{

        $.ajax({
            type:'POST',
            url:window.location.pathname +'?r=par/par-details',
            data:{
                par_number:property_card.val()
            },
            success:function(data){
                var res = JSON.parse(data)
                $('#par_no').text(res.par_number)
                $('#property_number').text(res.property_number)
                $('#article').text(res.article)
                $('#quantity').text(res.quantity)
                $('#par_date').text(res.par_date)
                $('#recieved_by').text(res.rcv_by_employee_name)
                $('#book').text(res.book_name)
                $('#unit_of_measure').text(res.unit_of_measure)
                $('#model').text(res.model)
                $('#serial_number').text(res.serial_number)
                $('#iar_number').text(res.iar_number)
                $('#amount').text(res.acquisition_amount)
                $('#par_table').show()
                agency_id = res.agency_id
                employee_id=res.employee_id
                employee_name=res.rcv_by_employee_name
                 employee_data = [
             
                    {
                        id: res.employee_id,
                        text: res.rcv_by_employee_name
                    },
                  
                ];

                changeFrom()

            }
        })
    })
    $(document).ready(()=>{
        if ($('#transfer_type').val()!=''){
            $('#transfer_type').trigger('change')
        }
        if (property_card.val()!=''){
            property_card.trigger('change')
        }
    

    })
JS;
$this->registerJs($js);
?>