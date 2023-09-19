<?php

use app\models\Raouds;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\select2\Select2Asset;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'DV Form';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">

    <?php

    $fund = Yii::$app->db->createCommand("SELECT fund_cluster_code.id,fund_cluster_code.name FROM fund_cluster_code")->queryAll();
    $books = Yii::$app->db->createCommand("SELECT books.id,books.name FROM books")->queryAll();

    ?>


    <div class="container card">
        <p>


            <?php
            if ($model->is_cancelled) {
                echo "
                    <button class='btn btn-success' id='cancel'>
                        Activate
                     </button>
                    ";
            } else {
                echo "
                <button class='btn btn-danger' id='cancel'>
                    Cancel
                 </button>
                ";
            }

            echo "<input type='text' value='$model->id' id='cancel_id' style='display:none;'/>";


            // $q = Raouds::find()
            //     ->where('raouds.process_ors_id = :process_ors_id', ['process_ors_id' => $model->processOrs->id])
            //     ->one();

            if (!empty($model->cashDisbursement)) {

                $t = yii::$app->request->baseUrl . "/index.php?r=cash-disbursement/view&id={$model->cashDisbursement->id}";
                echo  Html::a('Cash Disbursement Link', $t, ['class' => 'btn btn-success ']);
            }


            ?>
        </p>
        <div style="float:right">
            <span style="font-size: x-small;">
                <?php
                echo $model->dv_number;
                ?>
            </span>
        </div>
        <table style="margin-top:30px">
            <tbody>

                <tr>

                    <td colspan="5" style="text-align:center">
                        <div>
                            <h5 style="font-weight: bold;">Department of Trade and Industry - Caraga</h5>
                        </div>
                        <h5 class="head">
                            ENTITY NAME
                        </h5>
                        <h5 class="head">
                            DISBURSEMENT VOUCHER
                        </h5>

                    </td>
                    <td colspan="2">
                        <div style="padding-bottom:6px;">
                            <span>Fund Cluster:</span>
                            <span style="float: right;">__________________</span>
                        </div>
                        <div style="padding-bottom:6px;">
                            <span>Date:</span>
                            <span style="float: right;">__________________</span>
                        </div>
                        <div>
                            <span>DV No.:</span>
                            <span style="float: right;">
                                <?php
                                echo $model->dv_number;
                                ?>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        Mode of Payment
                    </td>
                    <td colspan="6">
                        <div style="display: flex;width:100%;justify-content:space-evenly">
                            <div>
                                <span class="box"></span>
                                <span>MDS Check</span>

                            </div>
                            <div>
                                <span class="box"></span>
                                <span>Commercial Check</span>

                            </div>
                            <div>
                                <span class="box"></span>
                                <span>ADA</span>

                            </div>
                            <div>
                                <span class="box"></span>
                                <span>Others (Please specify)</span>

                            </div>
                        </div>
                    </td>

                </tr>
                <tr>
                    <td colspan="1" class="head" rowspan="2">
                        Payee
                    </td>
                    <td colspan="4" rowspan="2">
                        <?php echo $model->payee->account_name; ?>
                    </td>
                    <td rowspan="1">
                        TIN/Employee No.
                    </td>
                    <td rowspan="1">
                        ORS/BURS No.
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px;"></td>
                    <td></td>
                </tr>

                <tr class="header">
                    <td colspan="1" class="head">
                        Address
                    </td>
                    <td colspan="6">
                    </td>
                </tr>
                <tr>

                    <td colspan="3">
                        Particulars
                    </td>
                    <td colspan="2">
                        MFO/PAP
                    </td>
                    <td>
                        Responsibility center
                    </td>
                    <td style="text-align: center;">
                        Amount
                    </td>
                </tr>
                <tr>
                    <td colspan='3' style='padding:10px'>
                        <?php echo $model->particular ?>
                    </td>
                    <td colspan="2">
                    </td>
                    <td>
                    </td>
                    <td>
                        <?php
                        //  echo number_format($model->gross_amount, 2) 
                        ?>
                    </td>
                </tr>
                <?php
                $x = 0;
                // while ($x < 7) {
                //     echo "
                //     <tr>
                //         <td colspan='3' style='padding:10px'>
                //         </td>
                //         <td>
                //         </td>
                //         <td>
                //         </td>
                //         <td>
                //         </td>
                //   </tr>
                //     ";
                //     $x++;
                // }
                $ors_serial_number = '';
                $total = 0;
                if (!empty($model->dvAucsEntries)) {

                    foreach ($model->dvAucsEntries as $val) {
                        if (!$val->is_deleted) {


                            $ors_serial_number = !empty($val->process_ors_id) ? $val->processOrs->serial_number : '';
                            $t = '';
                            if (!empty($val->process_ors_id)) {

                                $q = Raouds::find()
                                    ->where('raouds.process_ors_id = :process_ors_id', ['process_ors_id' =>  $val->process_ors_id])
                                    ->one();
                                // $q = !empty($val->process_ors_id) ? $val->process_ors_id : '';
                                if (!empty($q)) {

                                    $t = yii::$app->request->baseUrl . "/index.php?r=process-ors-entries/view&id=$q->id";
                                }
                            }

                            $amount = number_format($val->amount_disbursed, 2);
                            $total += $val->amount_disbursed;
                            echo "
                    <tr>
                        <td colspan='3' style='padding:0px'>
                        $ors_serial_number
                        </td>
                        <td colspan='2'>
                        </td>
                        <td>
                        </td>
                        <td style='padding-left:auto;text-align:right;padding-right:10px'>
                        $amount
                        </td>
                        <td class='link'>";
                            echo  !empty($val->process_ors_id) ? Html::a('ORS', ['process-ors/view', 'id' => $val->process_ors_id], ['class' => 'btn-xs btn-success ']) : '';
                            echo "</td></tr>";
                        }
                    }
                }


                ?>
                <tr>
                    <td class="head" style="text-align: center; font-size:12px" colspan="6">
                        Amount Due
                    </td>
                    <td style="text-align: right;padding-right:10px">
                        <?php
                        echo number_format($total, 2);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" style="padding: 12;">
                        <h6 style="margin-top:8px">A: Certified: Expenses/Cash Advance necessary, lawful and incurred under my direct supervision.</h6>

                        <div style="text-align: center;
                        margin-top:3rem;
                        margin-bottom:2rem;
                        font-size:10pt">

                            <select name="" id="assignatory_5" class="assignatory" style="width: 300px;padding:0;" onchange="setPosition(this,5)">
                                <option value=""></option>
                            </select>
                            <div style="padding:0;font-weight:normal" class="pos" id="position_5">

                            </div>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td colspan="7">
                        <h6 class="head">
                            B. Accounting Entry
                        </h6>
                    </td>
                </tr>
                <tr>
                    <td style='padding:10px' colspan='3'> Account Title</td>
                    <td colspan='2'>UACS Code</td>
                    <td>Debit</td>
                    <td>Credit</td>
                </tr>
                <?php
                $y = 0;
                while ($y < 4) {

                    echo "
                    <tr>
                        <td style='padding:10px' colspan='3'></td>
                        <td colspan='2'></td>
                        <td></td>
                        <td></td>
                    </tr>
                    ";
                    $y++;
                }

                ?>
                <tr>
                    <td colspan="3" style="border-bottom: 1px solid white;font-weight:bold"> C. Certified</td>
                    <td colspan="4 " style="border-bottom: 1px solid white;font-weight:bold">D:Approved for Payment</td>
                </tr>
                <tr>
                    <td colspan="3" style="padding:0;">
                        <span class='box'></span>
                        <span>Cash Available</span>
                        <br>
                        <span class='box'></span>
                        <span>Subject to Authority to Debit Account (when applicable)</span>
                        <br>
                        <span class='box'></span>
                        <span> Supporting documents complete and amount claimed </span>


                    </td>
                    <td colspan="4" style="padding:0;">
                        <!-- <h6 style="margin:0" style="float:left" class="head">D:Approved for Payment</h6> -->
                        <!-- <h5 style="text-align: center; margin:4rem">
                        </h5> -->

                    </td>
                </tr>
                <tr>

                    <td>Signature</td>
                    <td colspan="2"></td>
                    <td>Signature</td>
                    <td colspan="3"></td>
                </tr>
                <tr>

                    <td>Printed Name</td>
                    <td colspan="2">
                        <div>
                            <select name="" id="assignatory_3" class="assignatory" style="width: 100%;" onchange="setPosition(this,3)">
                                <option value=""></option>
                            </select>
                        </div>
                    </td>
                    <td>Printed Name</td>
                    <td colspan="3">
                        <div>
                            <select name="" class="assignatory" id="assignatory_4" style="width: 100%;" onchange="setPosition(this,4)">
                                <option value=""></option>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Postion</td>
                    <td colspan="2" id="position_3" class="pos">

                    </td>
                    <td>Postion</td>
                    <td colspan="3" id="position_4" class="pos">
                    </td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td colspan="2">
                        <?php

                        // echo DatePicker::widget([
                        //     'name' => 'dp_1',
                        //     'options' => [
                        //         'placeholder' => 'Select Date',
                        //         'style' => 'background-color:white'
                        //     ],
                        //     'readonly' => true,
                        //     'type' => DatePicker::TYPE_INPUT,
                        //     'value' => date('m/d/Y'),
                        //     'pluginOptions' => [
                        //         'autoclose' => true,
                        //         'format' => 'mm/dd/yyyy',
                        //     ],
                        // ]);

                        ?>
                    </td>
                    <td>Date</td>
                    <td colspan='3'>
                        <?php

                        // echo DatePicker::widget([
                        //     'name' => 'dp_1',
                        //     'options' => [
                        //         'placeholder' => 'Select Date',
                        //         'style' => 'background-color:white'
                        //     ],
                        //     'readonly' => true,
                        //     'type' => DatePicker::TYPE_INPUT,
                        //     'value' => date('m/d/Y'),
                        //     'pluginOptions' => [
                        //         'autoclose' => true,
                        //         'format' => 'mm/dd/yyyy',
                        //     ],
                        // ]);

                        ?>
                    </td>
                </tr>
                <!-- LETTER E -->
                <tr>
                    <td colspan="6" class="head">
                        E. Reciept Payment
                    </td>
                    <td rowspan="2" style="vertical-align: top;">JEV No.</td>
                </tr>
                <tr>

                    <td>Check/ADA No.:</td>
                    <td style="width:200px"></td>
                    <td>Date:</td>
                    <td style="width: 70px;"></td>
                    <td colspan="">Bank Name & Account Number:</td>
                    <td></td>

                </tr>
                <tr>
                    <td>
                        Signature:
                    </td>
                    <td>

                    </td>
                    <td>
                        Date:
                    </td>
                    <td style="width: 70px;"></td>
                    <td>
                        Printed Name:
                    </td>
                    <td></td>

                    <td rowspan="2" style="vertical-align: top;">
                        Date:
                    </td>
                </tr>
                <tr>
                    <td colspan="6">Official Receipt No. & Date/Other Documents</td>

                </tr>





            </tbody>
        </table>

        <!-- 
        <div class="container">
            <table class="table table-striped">
                <tbody>

                </tbody>
            </table>
        </div> -->


    </div>


    <style>
        .square-icon {
            font-size: 20px;
        }

        .box {
            border: 1px solid black;
            height: 12px;
            padding-left: 12px;
            margin: 3px
        }

        .select2-container--default .select2-selection--single,
        .select2-selection .select2-selection--single {
            /* border: 1px solid #d2d6de; */
            /* border-radius: 0; */
            /* padding: 6px; */
            text-align: center;
            vertical-align: bottom;
            /* height: 34px; */
            font-weight: bold;
        }

        .container {
            padding: 12px;
        }

        .serial {
            margin-top: 8px;
        }

        .head {
            text-align: center;
            font-weight: bold;
        }

        td {
            border: 1px solid black;
            padding: 3px;
            font-size: 15px;
        }

        table {
            margin: 12px;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
        }

        .pos {
            text-align: center;
        }


        @media print {
            .actions {
                display: none;
            }

            .select2-container {
                height: 20px;
            }

            .btn {
                display: none;
            }

            .link {
                display: none;
            }

            .krajee-datepicker {
                border: 1px solid white;
                font-size: 10px;
            }

            /* .select2-selection__rendered{
            text-decoration: underline;
        } */
            .select2-container--default .select2-selection--single {
                background-color: #fff;
                border: none;
                border-radius: 4px;
            }


            .select2-selection__arrow {
                display: none;
            }

            .select2-selection {
                border: 1px solid white;
            }

            select {
                -webkit-appearance: none;
                -moz-appearance: none;
                text-indent: 1px;
                text-overflow: '';
                border: none;
            }

            table,
            th,
            td {
                border: 1px solid black;
                padding: 5px;
                font-size: 10px;
            }

            @page {
                size: auto;
                margin: 0;
                margin-top: 0.5cm;
            }



            .container {
                margin: 0;
                top: 0;
            }

            .entity_name {
                font-size: 5pt;
            }

            td {
                border: 1px solid black;
                padding: 5px;
                font-size: x-small;
                background-color: white;
            }

            .container {

                border: none;
            }


            table {
                page-break-after: auto
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto
            }

            td {
                page-break-inside: avoid;
                page-break-after: auto
            }

            /* thead {
                display: table-header-group
            } */

            .main-footer {
                display: none;
            }
        }
    </style>

</div>
<?php
$this->registerJsFile("@web/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
Select2Asset::register($this);
?>
<script>
    var assignatory = []
    var positions = []

    function setPosition(q, pos) {
        $("#position_" + pos).text(q.value)
    }
    $(document).ready(function() {
        // reference = ["GAY A. TIDALGO"]
        // $('.assignatory').select2({
        //     data: reference,
        //     placeholder: "Select ",

        // })
        positions = ['Head', 'Budget', 'Division', 'Unit', 'Authorized Representative']
        $('.position').select2({
            data: positions,
            placeholder: "Select Position",

        })
        $.getJSON(window.location.pathname + '/frontend/web/index.php?r=assignatory/get-all-assignatory')
            .then(function(data) {

                var array = []
                $.each(data, function(key, val) {
                    array.push({
                        id: val.position,
                        text: val.name
                    })
                })
                assignatory = array
                $('.assignatory').select2({
                    data: assignatory,
                    placeholder: "Select ",

                })

            })
    })
</script>

<?php
SweetAlertAsset::register($this);
$script = <<< JS
    $("#cancel").click(function(){
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this imaginary file!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, I am sure!',
            cancelButtonText: "No, cancel it!",
            closeOnConfirm: false,
            closeOnCancel: true
         },
         function(isConfirm){

           if (isConfirm){
                $.ajax({
                type:'POST',
                url:window.location.pathname + "?r=dv-aucs/cancel",
                data:{id:$("#cancel_id").val()},
                success:function(data){
                    
                    var res = JSON.parse(data)
                    var cancelled = res.cancelled?"Successfuly Cancelled":"Successfuly Activated";
                    if(res.isSuccess){
                        swal({
                                title:cancelled,
                                type:'success',
                                button:false,
                                timer:3000,
                            },function(){
                                location.reload(true)
                            })
                    }
                    else{
                        swal({
                                title:"Error Cannot Cancel",
                                text:"Dili Ma  Cancel ang Disbursment Niya",
                                type:'error',
                                button:false,
                                timer:3000,
                            })
                    }
                }
            })


            } else {
                swal("Cancelled", "Your imaginary file is safe :)", "error"); 
            }
        })

    })

JS;
$this->registerJs($script);
?>