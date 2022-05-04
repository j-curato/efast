<?php

use app\models\ChartOfAccounts;
use app\models\FundClusterCode;
use app\models\ProcessOrsEntries;
use app\models\Raouds;
use app\models\ResponsibilityCenter;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\widgets\Pjax;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transaction Forms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index" id='doc'>

    <?php

    $fund = Yii::$app->db->createCommand("SELECT fund_cluster_code.id,fund_cluster_code.name FROM fund_cluster_code")->queryAll();
    $books = Yii::$app->db->createCommand("SELECT books.id,books.name FROM books")->queryAll();





    ?>


    <!-- FORM 1 -->
    <div class="container panel panel-default">
        <button class="btn btn-success" type="button" id="print">Print</button>
        <p>

            <?php


            // if (!empty($model->processOrs->id)) {

            //     $q = Raouds::find()
            //         ->where('raouds.process_ors_id = :process_ors_id', ['process_ors_id' => $model->processOrs->id])
            //         ->one();


            //     $t = yii::$app->request->baseUrl . "/index.php?r=process-ors-entries/view&id=$q->id";
            //     echo  Html::a('ORS Link', $t, ['class' => 'btn btn-success ']);
            // }


            ?>
        </p>

        <?php Pjax::begin(['id' => 'journal', 'clientOptions' => ['method' => 'POST']]) ?>
        <div style="float: right;">
            <h6>
                <?php
                echo $model->tracking_number;
                ?>
            </h6>
        </div>
        <table style="margin-top:30px">
            <tbody>

                <tr>

                    <td colspan="5" style="text-align: center;">
                        <h5 class="head">
                            OBLIGATION REQUEST AND STATUS
                        </h5>
                        <div>
                            <h5 style="font-weight: bold;">Department of Trade and Industry - Caraga</h5>
                        </div>
                        <h5 class="head">
                            ENTITY NAME
                        </h5>

                    </td>
                    <td colspan="3">
                        <div class="serial">
                            <span>Serial No.:</span>
                            <span style="float: right;"> _______________</span>
                        </div>
                        <div class="serial">
                            <span>Date:</span>
                            <span style="float: right;">_______________</span>
                        </div>
                        <div class="serial">
                            <span>Fund Cluster:</span>
                            <span style="float: right;">_______________</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Payee
                    </td>
                    <td colspan="6">
                        <?php echo $model->payee->account_name; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Office
                    </td>
                    <td colspan="6">
                    </td>
                </tr>
                <tr class="header">
                    <td colspan="2">
                        Address
                    </td>
                    <td colspan="6">
                    </td>
                </tr>
                <tr class="header">
                    <td colspan="1" style="width:100px">
                        Responsibility Center
                    </td>
                    <td colspan="2">
                        Particulars
                    </td>
                    <td colspan="2" style="min-width: 150px;">
                        MFO/PAP
                    </td>
                    <td colspan="2" style="min-width: 150px;">
                        UACS Object Code
                    </td>
                    <td colspan="1" style="width: 30px">
                        Amount
                    </td>
                </tr>
                <tr>
                    <td colspan='1' style="vertical-align: top;">
                        <?php
                        echo !empty($model->responsibilityCenter->name) ? $model->responsibilityCenter->name : '';
                        ?>
                    </td>
                    <td colspan='2' style="padding-bottom: 10rem;max-width:250px">
                        <?php echo $model->particular ?>
                    </td>
                    <td colspan='2' style="min-width: 150px;">
                    </td>
                    <td colspan='2' style="min-width: 150px;">
                    </td>
                    <td colspan='1' style="vertical-align: top;text-align: right;padding-right:10px">
                        <?php echo number_format($model->gross_amount, 2) ?>
                    </td>
                </tr>

                <?php
                $i = 0;
                // while ($i < 9) {
                //     echo "
                //     <tr >
                //     <td colspan='2' style='padding:10px'>
                //     </td>
                //     <td colspan='3'>
                //     </td>
                //     <td colspan='1'>
                //     </td>
                //     <td colspan='1'>
                //     </td>
                //     <td colspan='1'>
                //     </td>
                // </tr>

                //     ";
                //     $i++;
                // }

                ?>



                <tr style="border-top:1px solid black">
                    <td class="" style="border-top:1px solid white ;
                    border-bottom:1px solid white ;
                    padding:20px" colspan="3">A. Certified: Charges to appropriation/alloment arenecessary, lawful and under my direct
                        supervision;and supporting documents valid, proper and legal
                    </td>
                    <td colspan="5" style="border-top:1px solid white;
                    border-bottom:1px solid white;
                    padding:20px">
                        B. Certified: Allotment available and obligated for the
                        purpose/adjustment necessary as indicated above

                    </td>
                </tr>
                <tr>
                    <td class="ors_a" style="vertical-align:top;">
                        Signature
                    </td>
                    <td colspan="2" class="" style="border-bottom: 1px solid white;vertical-align:bottom;text-align:center">
                        ______________________________________
                    </td>
                    <td colspan="1" class=" ors_b">
                        Signature
                    </td>
                    <td colspan="4" style="border-bottom: 1px solid white;text-align:center">
                        _______________________________
                    </td>
                </tr>
                <tr>
                    <td style="width: 130px;" class="ors_a" style="vertical-align:top;padding:0">
                        Printed Name
                    </td>
                    <td colspan="2" class="" style="border-bottom: 1px solid white;padding:0">

                        <!-- ASSIGNATORY DROPDOWN -->
                        <select name="" id="assignatory_1" class="assignatory" style="width: 100%;" onchange="setPosition(this,1)">
                            <option value=""></option>
                        </select>
                    </td>
                    <td style="width: 130px;" class="ors_b">
                        Printed Name
                    </td>
                    <td colspan="4" style="border-bottom:1px solid white;">

                        <!-- ASSIGNATORY DROPDOWN -->
                        <select name="" id="assignatory_2" class="assignatory" style="width: 100%;" onchange="setPosition(this,2)">
                            <option value=""></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="ors_a">
                        Position:
                    </td>
                    <td colspan="2" style="border-bottom: 1px solid white;vertical-align:text-top" class="pos" id="position_1">

                    </td>
                    <td class="ors_b">
                        Position:
                    </td>
                    <td colspan="4" style="border-bottom:1px solid white;" class="pos" id="position_2">


                    </td>
                    </tr=>
                <tr>
                    <!-- style="border-top:1px solid white;border-right:1px solid white;" -->
                    <td style="border-top:1px solid white;border-right:1px solid white;">
                        Date:
                    </td>
                    <td colspan="2" style="text-align:center">
                        ______________________________________

                    </td>
                    <td style="border-left:1px solid white;border-right:1px solid white;">
                        Date:
                    </td>
                    <td colspan="4" style="text-align:center">
                        _______________________________

                    </td>
                </tr>
                <tr>
                    <td colspan="8">
                        <h6 class="head">
                            STATUS OF OBLIGATION
                        </h6>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="head">
                        REFERENCE

                    </td>
                    <td colspan="4" class="head">
                        AMOUNT
                    </td>
                </tr>
                <tr>
                    <td rowspan="2">date</td>
                    <td rowspan="2">particular</td>
                    <td rowspan="2">ORS/JEV/Check/ADA/TRA No.</td>
                    <td rowspan="2">Obligation</td>
                    <td rowspan="2">Payable</td>
                    <td rowspan="2">Payment</td>
                    <td colspan="2">Balance</td>
                </tr>
                <tr>
                    <td>
                        Not Yet Due
                    </td>
                    <td>
                        Due and Demandable
                    </td>
                </tr>

                <?php
                $x = 0;
                while ($x < 7) {
                    echo "
                    <tr>
                        <td style='padding:10px'></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    ";
                    $x++;
                }
                ?>



            </tbody>
        </table>
        <?php Pjax::end() ?>

    </div>


    <!-- FORM 1 END-->
    <p style='page-break-after:always;'></p>
    <!-- FORM 2-->
    <div class="container panel panel-default">
        <div style="float:right">
            <h6>
                <?php
                echo $model->tracking_number;
                ?>
            </h6>
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

                        <div class="serial">
                            <span>Fund Cluster:</span>
                            <span style="float: right;"> _________________</span>
                        </div>
                        <div class="serial">
                            <span>Date:</span>
                            <span style="float: right;">_________________</span>
                        </div>
                        <div class="serial">
                            <span>DV No.:</span>
                            <span style="float: right;">_________________</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        Mode of Payment
                    </td>
                    <td colspan="6" style="padding: 0;">
                        <div style="display: flex;width:100%;justify-content:space-evenly">
                            <div style="padding:0;margin:0">
                                <div>
                                    <span><i class="fa-square-o square-icon"></i>MDS Check</span>
                                </div>
                            </div>
                            <div style="padding:0;margin:0">
                                <span><i class="fa-square-o square-icon"></i>Commercial Check</span>
                            </div>
                            <div style="padding:0;margin:0">
                                <span><i class="fa-square-o square-icon"></i>ADA</span>
                            </div>
                            <div style="padding:0;margin:0">
                                <span><i class="fa-square-o square-icon"></i>Others (Please specify)</span>
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
                    <td style="padding: 10px;" colspan=""></td>
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

                    <td colspan="2">
                        Particulars
                    </td>
                    <td colspan="3">
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
                    <td colspan='2' style='padding-bottom:10rem'>
                        <?php echo $model->particular ?>
                    </td>
                    <td colspan="3">
                    </td>
                    <td style="vertical-align: top; text-align: center;">
                        <?php
                        echo !empty($model->responsibilityCenter->name) ? $model->responsibilityCenter->name : '';
                        ?>
                    </td>
                    <td style="vertical-align: top; text-align: right;padding-right:10px">
                        <?php echo number_format($model->gross_amount, 2) ?>
                    </td>
                </tr>
                <?php
                $x = 0;
                // while ($x < 2) {
                //     echo "
                //     <tr>
                //         <td colspan='4' style='padding:10px'>
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

                ?>
                <tr>
                    <td class="head" style="text-align: center; font-size:12px" colspan="6">
                        Amount Due
                    </td>
                    <td style="text-align: right;padding-right:10px"> <?php echo number_format($model->gross_amount, 2) ?></td>
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
                    <td colspan="2">UACS Code</td>
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
                    <td colspan="3" style="border-bottom: none;font-weight:bold"> C. Certified</td>
                    <td colspan="4" style="border-bottom: none;font-weight:bold">D:Approved for Payment</td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-left:10px;">



                        <div>
                            <span style="height: 4px;border:1px solid black;padding-left:15px;margin:4px"></span>
                            <span>
                                Cash Available
                            </span>
                        </div>

                        <div>
                            <span style="height: 4px;border:1px solid black;padding-left:15px;margin:4px"></span>
                            <span>
                                Subject to Authority to Debit Account (when applicable)
                            </span>
                        </div>
                        <div>
                            <span style="height: 4px;border:1px solid black;padding-left:15px;margin:4px"></span>
                            <span>
                                Supporting documents complete and amount claimed
                            </span>
                        </div>



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
                    </td>
                    <td>Date</td>
                    <td colspan='3'>
                    </td>
                </tr>
                <!-- LETTER E -->
                <tr>
                    <td colspan="6" class="head">
                        E. Reciept Payment
                    </td>
                    <td rowspan="2" style="width: 100px;vertical-align:top">JEV No.</td>
                </tr>
                <tr>

                    <td>Check/ADA No.:</td>
                    <td style="width:200px"></td>
                    <td>Date:</td>
                    <td></td>
                    <td colspan="">Bank Name & Account Number:</td>
                    <td></td>

                </tr>
                <tr>
                    <td>
                        Signature :
                    </td>
                    <td>

                    </td>
                    <td>
                        Date:
                    </td>
                    <td style="width:70px">

                    </td>
                    <td>
                        Printed Name:
                    </td>
                    <td></td>

                    <td rowspan="2" style="vertical-align:top">
                        Date:
                    </td>
                </tr>
                <tr>
                    <td colspan="6">Official Receipt No. & Date/Other Documents</td>

                </tr>





            </tbody>
        </table>
    </div>

    <div class="container paner panel-default links" style="background-color: white;">

        <table class="table ">
            <tr>
                <th>ORS Number</th>
                <th>Link</th>
            </tr>
            <tbody>

                <?php
                if (!empty($model->processOrs)) {
                    foreach ($model->processOrs as $val) {
                        if (!empty($val->id)) {

                            // $q = Raouds::find()
                            //     ->where('raouds.process_ors_id = :process_ors_id', ['process_ors_id' => $val->id])
                            //     ->one();


                            $t = yii::$app->request->baseUrl . "/index.php?r=process-ors-entries/view&id=$val->id";
                            // echo  Html::a('ORS Link', $t, ['class' => 'btn btn-success ']);

                        }
                        echo "<tr>
                            <td>$val->serial_number</td>
                            <td>" . Html::a('ORS Link', $t, ['class' => 'btn btn-success ']) . "</td>
                        </tr>";
                    }
                }


                ?>
            </tbody>
        </table>
    </div>
    <!-- FORM 2 END-->


    <script src="<?php echo Url::base() ?>/frontend/web/js/jquery.min.js" type="text/javascript"></script>
    <link href="<?php echo Url::base() ?>/frontend/web/js/select2.min.js" />
    <link href="<?php echo Url::base() ?>/frontend/web/css/select2.min.css" rel="stylesheet" />
    <script>
        var assignatory = []
        var positions = []

        function setPosition(q, pos) {
            $("#position_" + pos).text(q.value)
        }
        $(document).ready(function() {

            positions = ['Head', 'Budget', 'Division', 'Unit', 'Authorized Representative']
            $('.position').select2({
                data: positions,
                placeholder: "Select Position",

            })
            $.getJSON('<?php echo Url::base() ?>/frontend/web/index.php?r=assignatory/get-all-assignatory')

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
        // $("#assignatory").change(function(){
        //     console.log("qwe")
        // })
        // function sample(q) {
        //     console.log(q.value)

        //     $("#ass").text(q.value)

        // }
    </script>
</div>
<style>
    .select2-selection--single {
        /* border: 1px solid #d2d6de; */
        border-radius: 0;
        /* padding: 6px ; */
        height: 34px;

    }




    .pos {
        text-align: center;
        /* font-weight: bold; */
    }

    .select2 {
        margin: 0;
    }


    .select2-container--default .select2-selection--single,
    .select2-selection .select2-selection--single {
        /* border: 1px solid #d2d6de; */
        /* border-radius: 0; */
        padding: 6px;
        text-align: center;
        vertical-align: bottom;
        /* height: 34px; */
        font-weight: bold;
    }


    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-right: 0;
    }


    .container {
        padding: 12px;
    }

    .square-icon {
        font-size: 18px;
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
        padding: .5rem;
    }

    table {
        margin: 12px;
        margin-left: auto;
        margin-right: auto;
        width: 100%;
    }

    .ors_a {
        border-top: 1px solid white;
        border-right: 1px solid white;
        border-bottom: 1px solid white;
    }

    .ors_b {
        border-top: 1px solid white;
        border-right: 1px solid white;
        border-bottom: 1px solid white;
        border-left: 1px solid white;
    }

    @media print {
        .select2-container .select2-selection--single .select2-selection__rendered {
            margin-top: 0;
            vertical-align: bottom;

        }

        .actions {
            display: none;
        }

        .select2-container--default .select2-selection--single,
        .select2-selection .select2-selection--single {
            /* border: 1px solid #d2d6de; */
            /* border-radius: 0; */
            padding: 0;

        }

        .select2-container {
            height: 20px;
        }

        .links {
            display: none;
        }

        .btn {
            display: none;
        }

        .krajee-datepicker {
            border: 1px solid white;
            font-size: 10px;
            padding-left: 9px;
        }

        /* .select2-selection__rendered{
            text-decoration: underline;
        } */
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: none;
            padding: 0;
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

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<?php
SweetAlertAsset::register($this);
$script = <<< JS

    $('#print').click(function(){
        if (
            $('#assignatory_1').val()==''
            ||$('#assignatory_2').val()==''
            ||$('#assignatory_3').val()==''
            ||$('#assignatory_4').val()==''
            ||$('#assignatory_5').val()==''
        ){
            swal({
                title:'Please Choose Asignatory',
                type:'error',
                button:false,

            })
        }
        else{
            window.print()
        }
    })

JS;
$this->registerJs($script);
?>