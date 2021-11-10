<?php

use app\models\Agency;
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

                <button id="scan" type='button' class='btn btn-primary'>Scan QR</button>
            </div>
            <div class="col-sm-3">
                <video id="preview" style="transform: scaleX(1);"></video>
            </div>
        </div>

        <form id="ptr_form">
            <div class="row">
                <div class="col-sm-3">
                    <label for="date">Date</label>
                    <?php
                    $date = '';
                    $reason = '';
                    $transfer_type = null;
                    $model_id = null;
                    if (!empty($model->ptr_number)) {
                        $date = $model->date;
                        $transfer_type = $model->transfer_type_id;
                        $reason  = $model->reason;
                        $model_id = $model->ptr_number;
                    }
                    echo "<input type='hidden' value='$model_id' name='model_id' />";
                    echo DatePicker::widget([
                        'name' => 'date',
                        'id' => 'date',
                        'value' => $date,
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'autoclose' => true
                        ]
                    ]) ?>
                </div>
                <div class="col-sm-3">
                    <label for="transfer_type">Transfer Type</label>
                    <?= Select2::widget([
                        'id' => 'transfer_type',
                        'name' => 'transfer_type',
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
                <tr>
                    <th>PC Number</th>
                    <td id="pc_number"></td>
                    <th></th>
                    <td></td>
                </tr>

                </tbody>
            </table>

            <div class="row">
                <div class="col-sm-12">
                    <label for="reason">Reason</label>
                    <textarea name="reason" id="" cols="30" rows="5" style="width: 100%;max-width:100%;"><?= $reason; ?>
                </textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <label for="transfer_from">From </label>
                    <!-- <select class="from" id='from' style="width: 100%;" name="from">
                        <option></option>
                    </select> -->
                    <br>
                    <span id="transfer_from" class="transfer_from"></span>
                </div>
                <div class="col-sm-6">
                    <label for=""> To</label>
                    <?php
                    $employee = [];
                    $employee_id = '';
                    $agency_id = '';
                    $x = '';
                    if (!empty($model->employee_to)) {

                        $employee_query   = Yii::$app->db->createCommand("SELECT employee_id,UPPER(employee_name)  as employee_name FROM employee_search_view WHERE employee_id = :id")
                            ->bindValue(':id', $model->employee_to)
                            ->queryAll();
                        $employee_id =  $model->employee_to;
                        $employee = ArrayHelper::map($employee_query, 'employee_id', 'employee_name');


                        $x = [$employee_id];
                    }
                    if (!empty($model->agency_to_id)) {
                        $agency_id = $model->agency_to_id;
                    }
                    $q = Yii::$app->db->createCommand("SELECT * FROM agency")->queryAll();
                    $agency = ArrayHelper::map($q, 'id', 'name');
                    // 
                    // echo json_encode($agency);
                    echo "<div id='agency_container'>";
                    echo Select2::widget([
                        'data' => $agency,
                        'name' => 'agency_to',
                        'id' => 'agency_too',
                        'value' => $agency_id,
                        'pluginOptions' => [
                            'placeholder' => 'Select Agency'
                        ]
                    ]);
                    echo "</div>";
                    echo "<div id='employee_container'>";
                    echo Select2::widget([
                        'data' => $employee,
                        'name' => 'employee_id',
                        'id' => 'employee_id',
                        'value' => $x,
                        'options' => ['placeholder' => 'Search for a Fund Source ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 1,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                            ],
                            'ajax' => [
                                'url' => Yii::$app->request->baseUrl . '?r=employee/search-employee',
                                'dataType' => 'json',
                                'delay' => 250,
                                'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                                'cache' => true
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                            'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                        ],

                    ]);
                    echo "</div>";

                    ?>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Save</button>
        </form>


    </div>

</div>
<style>
    #agency_container,
    #employee_container {
        display: none;
    }

    #preview {
        display: none;
        height: 280px;
        width: 280px;
        margin: 0;
        padding: 0;
        transform: scaleX(1);

    }

    video {
        transform: scaleX(1);
        transform: rotateY(180deg);
        -webkit-transform: rotateY(180deg);
        /* Safari and Chrome */
    }

    .row {
        margin: 5px;
    }

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
<script>
    let scanner = new Instascan.Scanner({
        video: document.getElementById('preview')
    });
    Instascan.Camera.getCameras().then(function(cameras) {
        if (cameras.length > 0) {
            scanner.start(cameras[0]);
        } else {
            console.error('No cameras found.');
        }
    }).catch(function(e) {
        console.error(e);
    });
    scanner.addListener('scan', (c) => {
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=par/get-par',
            data: {
                id: c
            },
            success: function(data) {
                var res = JSON.parse(data)
                var studentSelect = $('#par_number');
                var option = new Option([res], [res], true, true);
                studentSelect.append(option).trigger('change');

            }
        })
        play()
        scanner.stop()
        $('#preview').hide()

    })

    $('#scan').click(() => {
        navigator.permissions.query({
                name: 'camera'
            })
            .then((permissionObj) => {
                console.log(permissionObj.state);
            })
            .catch((error) => {
                console.log('Got error :', error);
            })

        if ($('#preview').is(':visible')) {
            $('#preview').hide()

        } else {
            $('#preview').show()

        }




    })


    function play() {
        var beepsound = new Audio(
            '/afms/frontend/web/beep.mp3');
        beepsound.play();
    }
</script>
<?php
$js = <<<JS
    var property_card = $('#par_number')
    $('#ptr_form').submit((e)=>{
        e.preventDefault()
        $.ajax({
            type:"POST",
            url:window.location.pathname + '?r=ptr/insert-ptr',
            data:$('#ptr_form').serialize(),
            success:function(data){
                var res = JSON.parse(data)
                console.log(res)
            }
        })
    })
    $('#transfer_type').change(()=>{
        var selectedText = $("#transfer_type option:selected").html();
   
        if (selectedText.toLowerCase() == 'donation' || selectedText.toLowerCase() == 'relocate'
        || selectedText.toLowerCase() == 'disposal'){
            // $('#employee_id').val(null).trigger("change");
            // $.getJSON('/afms/frontend/web/index.php?r=agency/get-agency')
            //     .then(function(data) {

            //         var array = []
            //         $.each(data, function(key, val) {
            //             array.push({
            //                 id: val.id,
            //                 text: val.name
            //             })
            //         })
            //         transaction = array
            //         $('#from').select2({
            //             data: transaction,
            //             placeholder: "Select to",

            //         }).show()
            //         $('#to').select2({
            //             data: transaction,
            //             placeholder: "Select to",

            //         }).show()

            //      });
            
        }
        else{
            // $('#agency_too').val(null).trigger("change");
            // $("#to").select2("val", "");
    
            // $('#from').select2({
            //     ajax: {
            //         url: window.location.pathname + '?r=chart-of-accounts/search-accounting-code',
            //         dataType: 'json',
            //         data: function(params) {

            //             return {
            //                 q: params.term,
            //                 page: params.page
            //             };
            //         },
            //         processResults: function(data,params) {
            //             // Transforms the top-level key of the response object to 'items' to 'results'
            //             params.page = params.page || 1;
            //             return {
            //                 results: data.results,
            //                 pagination: {
            //                    more: (params.page * 30) < data.total_count
            //                 }
            //             };
            //         }
            //     },
            //     placeholder:"Select to ",
            //     minimumInputLength: 1,
      

            // }).show();
            // $('#to').select2({
            //     ajax: {
            //         url: window.location.pathname + '?r=chart-of-accounts/search-accounting-code',
            //         dataType: 'json',
            //         data: function(params) {

            //             return {
            //                 q: params.term,
            //                 page: params.page
            //             };
            //         },
            //         processResults: function(data,params) {
            //             // Transforms the top-level key of the response object to 'items' to 'results'
            //             params.page = params.page || 1;
            //             return {
            //                 results: data.results,
            //                 pagination: {
            //                    more: (params.page * 30) < data.total_count
            //                 }
            //             };
            //         }
            //     },
            //     placeholder:"Select to ",
            //     minimumInputLength: 1,
      

            // }).show();
     
        }
        changeFrom()
    })
    var employee_data=[]
    var employee_id=''
    var employee_name=''
    var agency_name = ''
    function changeFrom(){
        var fromSelect = $('.from');
       const transfer_type =  $("#transfer_type option:selected").html()
       if (transfer_type.toLowerCase() == 'donation'
       || transfer_type.toLowerCase() == 'relocate'
       ||transfer_type.toLowerCase() == 'disposal'){
            // fromSelect.val(agency_id).trigger('change');
            $('#transfer_from').text(agency_name)
            $('#agency_container').show()
            $('#employee_container').hide()
       }
       else{
           $('#transfer_from').text(employee_name)
           $('#employee_container').show()
             $('#agency_container').hide()
        // $('#from').text(employee_data[employee_id])
        // var option = new Option( [employee_name],[employee_id], true, true);
        // fromSelect.append(option).trigger('change');
        // fromSelect.trigger({
        //     type: 'select2:select',
        //     params: {
        //     data: employee_data
        //     }
        // });
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
                $('#pc_number').text(res.pc_number)
                $('#par_table').show()
                agency_name=res.agency_name
                employee_name=res.rcv_by_employee_name
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