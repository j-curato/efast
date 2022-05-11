<?php


use yii\helpers\Html;
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




    $division = strtolower($model->responsibilityCenter->name);
    ?>


    <!-- FORM 1 -->
    <div class="container panel panel-default">
        <button class="btn btn-success" type="button" id="print">Print</button>


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


                        <!-- FORM 1 BOX A SIGNATORY -->
                        <select class="asignatory " data-pos='form1_box_a_signatory' id='form1_box_a_signatory' style="width: 100%;">
                            <option value=""></option>
                        </select>
                    </td>
                    <td style="width: 130px;" class="ors_b">
                        Printed Name
                    </td>
                    <td colspan="4" style="border-bottom:1px solid white;">

                        <!-- FORM 1 BOX B SIGNATORY -->
                        <select class="asignatory" style="width: 100%;" data-pos='form1_box_b_signatory' id="form1_box_b_signatory">
                            <option value=""></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="ors_a">
                        Position:
                    </td>
                    <td colspan="2" style="border-bottom: 1px solid white;vertical-align:text-top" class="pos form1_box_a_signatory" id="position_1">

                    </td>
                    <td class="ors_b">
                        Position:
                    </td>
                    <td colspan="4" style="border-bottom:1px solid white;" class="pos form1_box_b_signatory" id="position_2">


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
        <table style="margin-top:30px" id="dv_form">
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
                            <select class="asignatory" style="width: 300px;padding:0;" data-pos='form2_box_a_signatory' id="form2_box_a_signatory">
                                <option value=""></option>
                            </select>
                            <div style="padding:0;font-weight:normal" class="pos form2_box_a_signatory">

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
                            <select class="asignatory" style="width: 100%;" data-pos='form2_box_c_signatory' id="form2_box_c_signatory">
                                <option value=""></option>
                            </select>
                        </div>
                    </td>
                    <td>Printed Name</td>
                    <td colspan="3">
                        <div>
                            <select class="asignatory" style="width: 100%;" data-pos='form2_box_d_signatory' id="form2_box_d_signatory">
                                <option value=""></option>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Postion</td>
                    <td colspan="2" style="text-align: center;" class="form2_box_c_signatory">

                    </td>
                    <td>Postion</td>
                    <td colspan="3" style="text-align: center;" class="form2_box_d_signatory ">
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
    <?php

    if (Yii::$app->user->can('super-user')) {


    ?>
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
    <?php  } ?>
    <!-- FORM 2 END-->


</div>

<style>
    .select2-selection--single {
        /* border: 1px solid #d2d6de; */
        border-radius: 0;
        /* padding: 6px ; */
        height: 34px;

    }

    .select2-container--default .select2-selection--single {
        display: none;
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
        border: none;
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

    #ors_form>.select2-container--default .select2-selection--single {
        border: none;
    }


    .select2-selection {
        border: none;
    }

    @media print {

        .select2-container {
            height: 20px;
        }
        .select2-selection__arrow{
            display: none !important;
        }

        .select2-container--default .select2-selection--single {
            border: none !important;
        }


        .links {
            display: none;
        }

        .btn {
            display: none;
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


<script src="<?php echo Url::base() ?>/frontend/web/js/jquery.min.js" type="text/javascript"></script>
<link href="<?php echo Url::base() ?>/frontend/web/js/select2.min.js" />
<link href="<?php echo Url::base() ?>/frontend/web/css/select2.min.css" rel="stylesheet" />
<script>
    let asignatory = []
    var positions = []
    const division = '<?= $division ?>';



    $(document).ready(function() {
        console.log('division')
        positions = ['Head', 'Budget', 'Division', 'Unit', 'Authorized Representative']
        $('.position').select2({
            data: positions,
            placeholder: "Select Position",

        })

        $.getJSON('<?php echo Url::base() ?>/frontend/web/index.php?r=assignatory/get-all-assignatory')

            .then(function(data) {

                let array = []
                $.each(data, function(key, val) {
                    array.push({
                        id: val.name,
                        text: val.name,
                        emp: val.name,

                    })
                    asignatory.push({
                        position: val.position,
                        name: val.name,

                    })
                })
                console.log(asignatory)
                $('.asignatory').select2({
                    data: array,
                    placeholder: "Select ",

                })
                setDefaultSignatory(division)

            })
        $("#ors_form").on('change', '.asignatory', function() {
            const picked_signatory = $(this).val()
            const signatory_position = $(this).attr('data-pos')
            setSignatoryPosition(picked_signatory, signatory_position)
        })
        $("#dv_form").on('change', '.asignatory', function() {
            const picked_signatory = $(this).val()
            const signatory_position = $(this).attr('data-pos')
            setSignatoryPosition(picked_signatory, signatory_position)
        })

    })

    function setSignatoryPosition(signatory_name, signatory_position) {

        $.each(asignatory, function(key, val) {
            if (val.name == signatory_name) {
                $(`.${signatory_position}`).text(val.position)
                return
            }
        })
    }

    function setDefaultSignatory(_division) {
        if (_division == 'sdd') {
            $("#form1_box_a_signatory").val('JASMIN B. FAELNAR').trigger('change')
            $("#form2_box_a_signatory").val('JASMIN B. FAELNAR').trigger('change')
        } else if (_division == 'idd') {
            $("#form1_box_a_signatory").val('MARSON JAN S. DOLENDO').trigger('change')
            $("#form2_box_a_signatory").val('MARSON JAN S. DOLENDO').trigger('change')
        } else if (_division == 'cpd') {
            $("#form1_box_a_signatory").val('ATTY. KURT CHINO A. MONTERO').trigger('change')
            $("#form2_box_a_signatory").val('ATTY. KURT CHINO A. MONTERO').trigger('change')
        } else if (_division == 'fad') {
            $("#form1_box_a_signatory").val('JOHN VOLTAIRE S. ANCLA').trigger('change')
            $("#form2_box_a_signatory").val('JOHN VOLTAIRE S. ANCLA').trigger('change')
        } else if (_division == 'mssd') {
            $("#form1_box_a_signatory").val('BRENDA B. CORVERA, CESO V').trigger('change')
            $("#form2_box_a_signatory").val('BRENDA B. CORVERA, CESO V').trigger('change')
        } else if (_division == 'ord') {
            $("#form1_box_a_signatory").val('GAY A. TIDALGO, CESO IV').trigger('change')
            $("#form2_box_a_signatory").val('GAY A. TIDALGO, CESO IV').trigger('change')
        }
        
        $("#form1_box_b_signatory").val('JULIETA B. OGOY').trigger('change')
        $("#form2_box_c_signatory").val('CHARLIE C. DECHOS').trigger('change')
        $("#form2_box_d_signatory").val('GAY A. TIDALGO, CESO IV').trigger('change')
    }
</script>
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