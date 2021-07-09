<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PoTransaction */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Po Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="po-transaction-view">


    <p>
        <?= Html::button('Update', [
            'value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=po-transaction/update&id=' . $model->id),
            'id' => 'modalButtoncreate', 'class' => 'btn btn-primary', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector'
        ]); ?>
    </p>

    <div class="container panel panel-default">
        <div style="float:right">
            <h6>
                <?php
                echo $model->tracking_number;
                ?>
            </h6>
        </div>
        <table style="margin-top:30px;width:100%;">
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
                        <?php echo $model->payee; ?>
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

                    <td colspan="4">
                        Particulars
                    </td>
                    <td>
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
                    <td colspan='4' style='padding-bottom:10rem'>
                        <?php echo $model->particular ?>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td style="vertical-align: top; text-align: right;padding-right:10px">
                        <?php echo number_format($model->amount, 2) ?>
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
                    <td style="text-align: right;padding-right:10px"> <?php echo number_format($model->amount, 2) ?></td>
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
                    <td style='padding:10px' colspan='4'> Account Title</td>
                    <td>UACS Code</td>
                    <td>Debit</td>
                    <td>Credit</td>
                </tr>
                <?php
                $y = 0;
                while ($y < 4) {

                    echo "
                    <tr>
                        <td style='padding:10px' colspan='4'></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    ";
                    $y++;
                }

                ?>
                <tr>
                    <td colspan="4" style="border-bottom: 1px solid white;font-weight:bold"> C. Certified</td>
                    <td colspan="4" style="border-bottom: 1px solid white;font-weight:bold">D:Approved for Payment</td>
                </tr>
                <tr>
                    <td colspan="4" style="padding-left:10px;">
                        <!-- <h6 class="head">
                            C. Certified
                        </h6> -->


                        <div><i class="fa-square-o square-icon"></i>Cash Available</div>
                        <div><i class="fa-square-o square-icon"></i> Subject to Authority to Debit Account (when applicable)</div>
                        <div><i class="fa-square-o square-icon"></i> Supporting documents complete and amount claimed </div>

                    </td>
                    <td colspan="3" style="padding:0;">
                        <!-- <h6 style="margin:0" style="float:left" class="head">D:Approved for Payment</h6> -->
                        <!-- <h5 style="text-align: center; margin:4rem">
                        </h5> -->

                    </td>
                </tr>
                <tr>

                    <td>Signature</td>
                    <td colspan="3"></td>
                    <td>Signature</td>
                    <td colspan="2"></td>
                </tr>
                <tr>

                    <td>Printed Name</td>
                    <td colspan="3">
                        <div>
                            <select name="" id="assignatory_3" class="assignatory" style="width: 100%;" onchange="setPosition(this,3)">
                                <option value=""></option>
                            </select>
                        </div>
                    </td>
                    <td>Printed Name</td>
                    <td colspan="2">
                        <div>
                            <select name="" class="assignatory" id="assignatory_4" style="width: 100%;" onchange="setPosition(this,4)">
                                <option value=""></option>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Postion</td>
                    <td colspan="3" id="position_3" class="pos">

                    </td>
                    <td>Postion</td>
                    <td colspan="2" id="position_4" class="pos">
                    </td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td colspan="3">
                    </td>
                    <td>Date</td>
                    <td colspan='2'>
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

</div>
<style>
    .select2-selection--single {
        /* border: 1px solid #d2d6de; */
        border-radius: 0;
        /* padding: 6px ; */
        height: 34px;

    }


    .select2-container .select2-selection--single .select2-selection__rendered {
        margin-top: 0;
        vertical-align: bottom;

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


<script>
    function setPosition(q, pos) {
        $("#position_" + pos).text(q.value)
    }
    $(document).ready(function() {

        positions = ['Head', 'Budget', 'Division', 'Unit', 'Authorized Representative']
        $('.position').select2({
            data: positions,
            placeholder: "Select Position",

        })
        $.getJSON('/afms/frontend/web/index.php?r=po-assignatory/get-all-assignatory')

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
                    closeOnSelect: true

                })

            })
    })
</script>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<?php
$script = <<<JS
    

    
         $('#modalButtoncreate').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('.modalButtonedit').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });

JS;
$this->registerJs($script);
?>