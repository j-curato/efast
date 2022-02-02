<link href="/afms/frontend/web/css/select2.min.css" rel="stylesheet" />
<!-- <link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre.min.css">
<link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre-exp.min.css">
<link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre-icons.min.css"> -->

<?php

use app\models\Books;
use kartik\date\DatePicker;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\datetime\DateTimePicker;
use kartik\money\MaskMoney;
use kartik\grid\GridView;
use kartik\select2\Select2;
use kartik\time\TimePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;



$reporting_period = '';
$payee_id  = '';
$payee_name = '';
$particular = '';
$row = 1;
$book_id = '';
$update_create = '?r=dv-aucs/create-tracking';
$transaction_type = '';
$date_recieve = '';
if (!empty($model->id)) {
    $book_id = $model->book_id;
    $reporting_period = $model->reporting_period;
    $particular = $model->particular;
    $payee_id = $model->payee_id;
    $payee_name = $model->payee->account_name;
    $transaction_type = $model->transaction_type;
    $date_recieve = DateTime::createFromFormat('Y-m-d H:i:s', $model->recieved_at)->format('Y-m-d h:i A');
    $update_create = '?r=dv-aucs/tracking-update&id=' . $model->id;
}
?>
<div class="test">




    <div id="container" class="container">
        <div class="row">
            <div class="col-sm-12" style="color:red;text-align:center">
                <h4 id="link">
                </h4>
            </div>
        </div>
        <form id='save_data' method='POST'>

            <?php
            $q = 0;
            if (!empty($update_id)) {

                $q = $update_id;
            }
            echo " <input type='text' id='update_id' name='update_id' value='$q' style='display:none' >";
            ?>
            <div class="row">

                <div class="col-sm-3">
                    <label for="reporting_period">Reporting Period</label>
                    <?php
                    echo DatePicker::widget([
                        'name' => 'reporting_period',
                        'id' => 'reporting_period',
                        'value' => $reporting_period,
                        'options' => ['required' => true],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm',
                            'startView' => "year",
                            'minViewMode' => "months",
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-sm-3">
                    <label for="payee">Payee</label>
                    <select required id="payee" name="payee" class="payee select" style="width: 100%; margin-top:50px">
                        <?php

                        if (!empty($model->id)) {

                            echo "  <option value='$payee_id'>$payee_name</option>";
                        } else {
                            echo "<option></option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-sm-3" style="height:60x">
                    <label for="transaction">Transaction Type</label>
                    <select required id="transaction" name="transaction_type" class="transaction select" style="width: 100%; margin-top:50px">
                        <option></option>



                    </select>
                </div>

                <div class="col-sm-3">
                    <label for="book_id">Books</label>
                    <?php

                    echo Select2::widget([
                        'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                        'name' => 'book_id',
                        'value' => $book_id,
                        'pluginOptions' => [
                            'placeholder' => 'Select Book'
                        ]


                    ])
                    ?>
                </div>


            </div>
            <div class="row">
                <div class="col-sm-3">

                    <?php


                    echo "<label for='date' style='text-align:center'>Date Recieve</label>";
                    echo DateTimePicker::widget([
                        'name' => 'date_recieve',
                        'id' => 'date_recieve',
                        'value' => $date_recieve,
                        'options' => [
                            'style' => 'background-color:white'
                        ],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd HH:ii P',
                            'autoclose' => true
                        ]
                    ]);


                    ?>
                </div>

                <div class="col-sm-3"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <textarea name="particular" readonly id="particular" placeholder="PARTICULAR" required rows="3"><?= $particular ?></textarea>
                </div>
            </div>

            <table id="transaction_table" class="table table-striped">
                <thead>
                    <th>Serial Number</th>
                    <th>Particular</th>
                    <th>Payee</th>
                    <th>Total Obligated</th>
                    <th>Amount Disbursed</th>
                    <th>2306 (VAT/ Non-Vat)</th>
                    <th>2307 (EWT Goods/Services)</th>
                    <th>1601C (Compensation)</th>
                    <th>Other Trust Liabilities</th>
                </thead>
                <tbody>
                    <?php

                    if (!empty($model->id)) {

                        $query  = Yii::$app->db->createCommand(" SELECT 
                            
                            dv_aucs_entries.process_ors_id,
                            process_ors.serial_number,
                            `transaction`.particular,
                            payee.account_name as payee_name,
                            dv_aucs_entries.amount_disbursed,
                            dv_aucs_entries.vat_nonvat,
                            dv_aucs_entries.ewt_goods_services,
                            dv_aucs_entries.compensation,
                            dv_aucs_entries.other_trust_liabilities,
                            total_obligated.total
                            FROM dv_aucs_entries
                            LEFT JOIN process_ors ON dv_aucs_entries.process_ors_id = process_ors.id
                            LEFT JOIN `transaction` ON process_ors.transaction_id = `transaction`.id
                            LEFT JOIN payee ON `transaction`.payee_id = payee.id
                            LEFT JOIN (SELECT 
                            dv_aucs_entries.process_ors_id as ors_id,
                            SUM(dv_aucs_entries.amount_disbursed) as total
                            FROM dv_aucs_entries
                            INNER JOIN dv_aucs ON dv_aucs_entries.dv_aucs_id = dv_aucs.id
                            INNER JOIN cash_disbursement ON dv_aucs.id = cash_disbursement.dv_aucs_id
                            WHERE
                            dv_aucs.is_cancelled = 0
                            AND
                            cash_disbursement.is_cancelled=0
                            GROUP BY dv_aucs_entries.process_ors_id
                            ) as total_obligated ON process_ors.id=total_obligated.ors_id
                            WHERE
                            dv_aucs_entries.dv_aucs_id = :id")
                            ->bindValue(':id', $model->id)
                            ->queryAll();
                        foreach ($query as $val) {


                            echo "<tr>
                                <td style='display:none' ><input value='{$val['process_ors_id']}' type='hidden' name='process_ors_id[$row]'/></td>
                                <td> {$val['serial_number']}</td>
                                <td> 
                                {$val['particular']}
                                </td>
                                <td> {$val['payee_name']}</td>
                                <td> {$val['total']}</td>
                                <td>
                                 <input value='{$val['amount_disbursed']}' name='amount_disbursed[$row]' type='text'  class='amount_disbursed'/>
                                </td>
                                <td> <input value='{$val['vat_nonvat']}' type='text' name='vat_nonvat[$row]' class='vat'/></td>
                                <td> <input value='{$val['ewt_goods_services']}' type='text' name='ewt_goods_services[$row]' class='ewt'/></td>
                                <td> <input value='{$val['compensation']}' type='text' name='compensation[$row]' class='compensation'/></td>
                                <td> <input value='{$val['other_trust_liabilities']}' type='text' name='other_trust_liabilities[$row]' class='liabilities'/></td>
                                <td><button  class='btn-xs btn-danger ' onclick='remove(this)'><i class='glyphicon glyphicon-minus'></i></button></td></tr>
                            ";
                            $row++;
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Total</th>
                    <th>
                        <div id="total_disbursed"></div>
                    </th>
                    <th>
                        <div id="total_vat"></div>
                    </th>
                    <th>
                        <div id="total_ewt"></div>
                    </th>
                    <th>
                        <div id="total_compensation"></div>
                    </th>
                    <th>
                        <div id="total_liabilities"></div>
                    </th>

                </tfoot>

            </table>




            <button type="submit" class="btn btn-success" style="width: 100%;" id="save" name="save"> SAVE</button>
        </form>

        <form name="add_data" id="add_data">

            <div style="display: none;">
                <input type="text" id="transaction_type" name="transaction_type">
                <input type="text" id="dv_count" name="dv_count">
            </div>

            <!-- PROCESS ORS ANG MODEL -->
            <!-- NAA SA CREATE CONTROLLER NAKO GE CHANGE -->
            <?php
            $dataProvider->pagination = ['pageSize' => 10];

            ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    // 'type' => GridView::TYPE_PRIMARY,
                    'heading' => 'List of Areas',
                ],
                'floatHeaderOptions' => [
                    'top' => 50,
                    'position' => 'absolute',
                ],
                'export' => false,
                'pjax' => true,
                'showPageSummary' => true,
                'columns' => [

                    'serial_number',
                    'transaction.particular',
                    'transaction.payee.account_name',
                    [
                        'label' => 'Book',
                        'attribute' => 'book_id',
                        'value' => 'book.name',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => [
                                'allowClear' => true,
                                'placeholder' => 'Select Book'
                            ]
                        ],
                        'format' => 'raw'
                    ],

                    [
                        'label' => 'Total Obligated',
                        'value' => function ($model) {
                            $query = Yii::$app->db->createCommand("SELECT SUM(process_ors_entries.amount)as total
                      
                            FROM process_ors_entries
                            where process_ors_entries.process_ors_id = :ors_id
                           
                         
                          ")
                                ->bindValue(":ors_id", $model->id)
                                ->queryOne();
                            return $query['total'];
                        },
                        'format' => ['decimal', 2],
                        'pageSummary' => true
                    ],

                    [
                        'class' => '\kartik\grid\CheckboxColumn',
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                            return ['value' => $model->id, 'onchange' => 'enableDisable(this)', 'style' => 'width:20px;', 'class' => 'checkbox', ''];
                        }
                    ],
                    [
                        'label' => 'Amount Disbursed',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ' ' .  MaskMoney::widget([
                                'name' => "amount_disbursed[$model->id]",
                                'disabled' => true,
                                'id' => "amount_disbursed_$model->id",
                                'options' => [
                                    'class' => 'amounts',
                                ],
                                'pluginOptions' => [
                                    'prefix' => '₱ ',
                                    'allowNegative' => true
                                ],
                            ]);
                        }
                    ],
                    [
                        'label' => '2306 (VAT/ Non-Vat)',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ' ' .  MaskMoney::widget([
                                'name' => "vat_nonvat[$model->id]",
                                'disabled' => true,
                                'id' => "vat_nonvat_$model->id",
                                'options' => [
                                    'class' => 'amounts',
                                ],
                                'pluginOptions' => [
                                    'prefix' => '₱ ',
                                    'allowNegative' => true
                                ],
                            ]);
                        }
                    ],
                    [
                        'label' => '2307 (EWT Goods/Services)',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ' ' .  MaskMoney::widget([
                                'name' => "ewt_goods_services[$model->id]",
                                'disabled' => true,
                                'id' => "ewt_goods_services_$model->id",
                                'options' => [
                                    'class' => 'amounts',
                                ],
                                'pluginOptions' => [
                                    'prefix' => '₱ ',
                                    'allowNegative' => true
                                ],
                            ]);
                        }
                    ],
                    [
                        'label' => '1601C (Compensation)',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ' ' .  MaskMoney::widget([
                                'name' => "compensation[$model->id]",
                                'disabled' => true,
                                'id' => "compensation_$model->id",
                                'options' => [
                                    'class' => 'amounts',
                                ],
                                'pluginOptions' => [
                                    'prefix' => '₱ ',
                                    'allowNegative' => true
                                ],
                            ]);
                        }
                    ],
                    [
                        'label' => 'Other Trust Liabilities',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ' ' .  MaskMoney::widget([
                                'name' => "other_trust_liabilities[$model->id]",
                                'disabled' => true,
                                'id' => "other_trust_liabilities_$model->id",
                                'options' => [
                                    'class' => 'amounts',
                                ],
                                'pluginOptions' => [
                                    'prefix' => '₱ ',
                                    'allowNegative' => true
                                ],
                            ]);
                        }
                    ],

                ],
            ]); ?>
            <button type="submit" class="btn btn-primary" name="submit" id="submit" style="width: 100%;"> ADD</button>
        </form>







    </div>
</div>

<style>
    textarea {
        max-width: 100%;
        width: 100%;
    }

    .grid-view td {
        white-space: normal;
    }

    .select {
        width: 500px;
        height: 2rem;
    }

    #submit {
        margin: 10px;
    }

    input {
        width: 100%;
        font-size: 15px;
        padding: 5px;
        border-radius: 5px;
        border: 1px solid black;

    }

    .row {
        margin: 5px;
    }

    .container {
        background-color: white;
        height: auto;
        padding: 10px;
        border-radius: 2px;
    }

    .accounting_entries {
        background-color: white;
        padding: 2rem;
        border: 1px solid black;
        border-radius: 5px;
    }

    .swal-text {
        background-color: #FEFAE3;
        padding: 17px;
        border: 1px solid #F0E1A1;
        display: block;
        margin: 22px;
        text-align: center;
        color: #61534e;
    }
</style>

<script src="<?= Url::base() ?>/frontend/web/js/scripts.js" type="text/javascript"></script>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
// $csrfTokenName = Yii::$app->request->csrfTokenName;
$csrfToken = Yii::$app->request->csrfToken;
?>

<!-- <script src="/dti-afms-2/frontend/web/js/select2.min.js"></script> -->
<script>
    var vacant = 0;
    var i = 1;
    var x = [0];
    var update_id = undefined;
    var cashflow = [];
    var accounts = [];
    var row = 1

    function enableDisable(checkbox) {
        var isDisable = true
        if (checkbox.checked) {
            isDisable = false
        }
        enableInput(isDisable, checkbox.value)

    }

    function removeItem(index) {
        // $(`#form-${index}`).remove();
        // arr_form.splice(index, 1);
        // vacant = index
        // $('#form' + index + '').remove();

        document.getElementById(`form-${index}`).remove()
        for (var y = 0; y < x.length; y++) {
            if (x[y] === index) {
                delete x[y]
                x.splice(y, 1)
            }
        }
        // console.log(x, Math.max.apply(null, x))
        getTotal()


    }






    $('.add-btn').click(function() {
        add()
        getTotal()
    })

    function getTotal() {
        var total_disbursed = 0;
        var total_vat = 0;
        var total_ewt = 0;
        var total_compensation = 0;
        var total_liabilities = 0;
        $(".amount_disbursed").each(function() {
            total_disbursed += parseFloat($(this).val()) || 0;
        });
        $(".vat").each(function() {
            total_vat += parseFloat($(this).val()) || 0;
        });
        $(".ewt").each(function() {
            total_ewt += parseFloat($(this).val()) || 0;
        });
        $(".compensation").each(function() {
            total_compensation += parseFloat($(this).val()) || 0;
        });
        $(".liabilities").each(function() {
            total_liabilities += parseFloat($(this).val()) || 0;
        });
        $("#total_disbursed").text(thousands_separators(total_disbursed))
        $("#total_vat").text(thousands_separators(total_vat))
        $("#total_ewt").text(thousands_separators(total_ewt))
        $("#total_compensation").text(thousands_separators(total_compensation))
        $("#total_liabilities").text(thousands_separators(total_liabilities))

    }

    function thousands_separators(num) {

        var number = Number(Math.round(num + 'e2') + 'e-2')
        var num_parts = number.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }

    function enableInput(isDisable, index) {
        $(`#amount_${index}-disp`).prop('disabled', isDisable);
        $(`#amount_${index}`).prop('disabled', isDisable);
        $(`#amount_disbursed_${index}-disp`).prop('disabled', isDisable);
        $(`#amount_disbursed_${index}`).prop('disabled', isDisable);
        $(`#vat_nonvat_${index}-disp`).prop('disabled', isDisable);
        $(`#vat_nonvat_${index}`).prop('disabled', isDisable);
        $(`#ewt_goods_services_${index}-disp`).prop('disabled', isDisable);
        $(`#ewt_goods_services_${index}`).prop('disabled', isDisable);
        $(`#compensation_${index}-disp`).prop('disabled', isDisable);
        $(`#compensation_${index}`).prop('disabled', isDisable);
        $(`#other_trust_liabilities_${index}-disp`).prop('disabled', isDisable);
        $(`#other_trust_liabilities_${index}`).prop('disabled', isDisable);
        // button = document.querySelector('.amount_1').disabled=false;

    }

    function remove(i) {
        i.closest("tr").remove()
        dv_count--
        getTotal()
    }

    function maskAmount() {

        $('.amount_disbursed').maskMoney({
            allowNegative: true
        });
        $('.vat').maskMoney({
            allowNegative: true
        });
        $('.ewt').maskMoney({
            allowNegative: true
        });
        $('.compensation').maskMoney({
            allowNegative: true
        });
        $('.liabilities').maskMoney({
            allowNegative: true
        });

    }




    var select_id = 0;

    var transaction_type = $("#transaction").val();
    var dv_count = 1;
    var tracking_sheet = []
    var sheet = []
    var net_asset = []



    function addDvToTable(result, row) {
        console.log(row)
        for (var i = 0; i < result.length; i++) {
            if ($('#transaction').val() == 'Single' && i == 1) {
                break;
            }
            $('#book_id').val(result[0]['book_id'])
            var amount_disbursed = result[i]['amount_disbursed'] ? result[i]['amount_disbursed'] : 0;
            var vat_nonvat = result[i]['vat_nonvat'] ? result[i]['vat_nonvat'] : 0;
            var ewt_goods_services = result[i]['ewt_goods_services'] ? result[i]['ewt_goods_services'] : 0;
            var compensation = result[i]['compensation'] ? result[i]['compensation'] : 0;
            var other_trust_liabilities = result[i]['other_trust_liabilities'] ? result[i]['other_trust_liabilities'] : 0;
            var row = `<tr>
                            <td style='display:none'> <input style='display:none' value='${result[i]['ors_id']}' type='text' name='process_ors_id[${row}]'/></td>
                            <td> ${result[i]['serial_number']}</td>
                            <td> 
                            ${result[i]['transaction_particular']}
                            </td>
                            <td> ${result[i]['transaction_payee']}</td>
                            <td> ${result[i]['total']}</td>
                            <td>
                             <input value='${amount_disbursed}' name='amount_disbursed[${row}]' type='text'  class='amount_disbursed'/>
                            </td>
                            <td> <input value='${vat_nonvat}' type='text' name='vat_nonvat[${row}]' class='vat'/></td>
                            <td> <input value='${ewt_goods_services}' type='text' name='ewt_goods_services[${row}]' class='ewt'/></td>
                            <td> <input value='${compensation}' type='text' name='compensation[${row}]' class='compensation'/></td>
                            <td> <input value='${other_trust_liabilities}' type='text' name='other_trust_liabilities[${row}]' class='liabilities'/></td>
                            <td><button  class='btn-xs btn-danger ' onclick='remove(this)'><i class="glyphicon glyphicon-minus"></i></button></td></tr>
                        `
            $('#transaction_table tbody').append(row);
            // total += amount_disbursed
            select_id++;
            dv_count++;

        }



        getTotal()
        $("#dv_count").val(dv_count)

    }



    $(document).ready(() => {
        row = "<?= $row ?>";

        // maskAmount()
        $('#payee').select2({
            ajax: {
                url: window.location.pathname + '?r=payee/search-payee',
                dataType: 'json',
                data: function(params) {

                    return {
                        q: params.term,
                    };
                },
                processResults: function(data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data.results
                    };
                }
            },
        });

        $('.chart-of-accounts').select2({
            ajax: {
                url: window.location.pathname + '?r=chart-of-accounts/search-accounting-code',
                dataType: 'json',
                data: function(params) {

                    return {
                        q: params.term,
                    };
                },
                processResults: function(data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data.results
                    };
                }
            },
            placeholder: 'Search Accounting Code'
        });
        // ADD ORS 
        $('#submit').click(function(e) {
            e.preventDefault();

            var transaction_type = $('#transaction').val()
            var count = $('#transaction_table tbody tr').length
            if (transaction_type == 'Single' && count >= 1) {
                swal({
                    title: "Error",
                    text: 'Transaction Type is Single',
                    type: "error",
                    timer: 6000,
                    button: false
                    // confirmButtonText: "Yes, delete it!",
                });
                return
            }
            $.ajax({
                url: window.location.pathname + '?r=dv-aucs/get-dv',
                method: "POST",
                data: $('#add_data').serialize(),
                success: function(data) {
                    var res = JSON.parse(data)
                    console.log(res)
                    if (res.isSuccess) {

                        addDvToTable(res.results, row)
                        row++

                    } else {
                        swal({
                            title: "Error",
                            text: res.error,
                            type: "error",
                            timer: 6000,
                            button: false
                            // confirmButtonText: "Yes, delete it!",
                        });
                    }
                    if (transaction_type == 'Single') {
                        $("#particular").val(res.results[0]['transaction_particular'])
                        var payeeSelect = $('#payee')
                        var option = new Option(res.results[0]['transaction_payee'], [res.results[0]['transaction_payee_id']], true, true);
                        payeeSelect.append(option).trigger('change');
                    }

                }
            });
            $('.checkbox').prop('checked', false); // Checks it
            $('.amounts').prop('disabled', true);
            $('.amounts').val(null);

        })

    })

    $('#transaction_table').on('change keyup', ['.amount_disbursed', '.ewt', '.vat', '.compensation', '.liabilities'], function() {
        getTotal()

    });
</script>


<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
SweetAlertAsset::register($this); ?>
<?php

$script = <<< JS
        var reporting_period = '';
        var transactions=[];
        var nature_of_transaction=[];
        var reference=[];
        var mrd_classification=[];
        var books=[];
        var bbb=undefined;

       



        $('.amount_disbursed').on('change keyup',function(){
            console.log('qwe');
        })

    
    $("#transaction").change(function(){


          
        var transaction_type=$("#transaction").val()
        $("#transaction_type").val(transaction_type)
        // var result=[]
        



        var result = [
            {"serial_number":"",
            "transaction_particular":"",
            "transaction_payee":"",
            "total":""}
        ];
        var count=$('#transaction_table tbody tr').length

        if (transaction_type !='Single'  && transaction_type !='Multiple'){
            addDvToTable(result,row)
        }
        if(transaction_type=='Multiple'){
            $('#particular').attr('readonly',false)
        }
        else{
            // $('#particular').attr('readonly',true)
            // $('#particular').val('')
        }
     
        if (transaction_type==='No Ors' || transaction_type ==='Accounts Payable' ){
            $("#bok").show();
            $("#book").prop('required',true);
        }   
        else{
            $("#bok").hide();
            $("#book").prop('required',false);
            
        }
    })




    $(document).ready(function() {



            // TRANSACTION TYPE
           var transaction = [
               "Single",
                 "Multiple",
                "Accounts Payable",
                "Replacement to Stale Checks",
                'Replacement of Check Issued'
        ]
            $('#transaction').select2({
                data: transaction,
                placeholder: "Select transaction",

            })  
            // $("#transaction option:not(:selected)").attr('disabled',true)    
            // INSERT ANG DATA SA DATABASE
           
                var _transaction_type = "{$transaction_type}";
           if(_transaction_type !=''){
            $('#transaction').val(_transaction_type).trigger('change')
           }

    })
 
    $('#save_data').submit(function(e) {
  

         e.preventDefault();

         var url  = window.location.pathname +"$update_create";
         $.ajax({
            url:url ,
            method: "POST",
            data: $('#save_data').serialize(),
            success: function(data) {
                var res=JSON.parse(data)
                if (res.isSuccess==true) {
                    swal({
                        title: "Success",
                        // text: "You will not be able to undo this action!",
                        type: "success",
                        timer: 3000,
                        button: false
                        // confirmButtonText: "Yes, delete it!",
                    }, function() {
                        window.location.href = window.location.pathname + '?r=dv-aucs/view&id='+res.id
                    });
                    $('#save_data')[0].reset();
                }
                else if(res.isSuccess==false){

                    swal({
                        title: "Error",
                        text: res.error,
                        type: "error",
                        timer: 6000,
                        button: false
                        // confirmButtonText: "Yes, delete it!",
                    });
                }
                else if(res.isSuccess=='exist'){
                    var dv_link = window.location.pathname + "?r=dv-aucs/view&id=" +res.id
                    $('#link').text('NAA NAY DV ANG ORS ')
            bbb = $(`<a type="button" href='`+ dv_link+`' >link here</a>`);
                        bbb.appendTo($("#link"));
                    swal({
                        title: "Error",
                        text: "Naa Nay DV",
                        type: "error",
                        timer: 6000,
                        button: false
                        // confirmButtonText: "Yes, delete it!",
                    });
                }
            }
        });

})

     



JS;
$this->registerJs($script);
?>