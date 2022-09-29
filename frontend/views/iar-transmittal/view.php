<?php

use app\models\Assignatory;
use app\models\EmployeePosition;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PurchaseOrderTransmittal */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'IAR Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="purchase-order-transmittal-view">


    <div class="container">

        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </p>
        <div class="row as">


        </div>

        <div class="row" style="float:right">
            <?= Html::img(Yii::$app->request->baseUrl . '/frontend/web/dti3.png', ['alt' => 'some', 'class' => 'pull-left img-responsive', 'style' => 'width: 100px;height:100px;margin-left:auto']); ?>
        </div>
        <div class="row" style="margin-top: 130px;">
            <div class="row head" style=" margin-bottom:2rem"><?php echo date('F d, Y', strtotime($model->date)) ?></div>
            <div class="row head" style="font-weight: bold;">ADA JUNE M. HORMILLADA</div>
            <div class="row head">State Auditor III</div>
            <div class="row head">OIC - Audit Team Leader</div>
            <div class="row head">COA - DTI Caraga</div>
            <div class="row head" style="padding-top: 2rem;padding-bottom: 2rem;">Dear Ma’am Hormillada:</div>
            <p style="font-size: 12pt;">

                We are hereby submitting the following Inspection Reports, with assigned Transmittal # <?php
                                                                                                        echo $model->serial_number;
                                                                                                        ?> of DTI Regional Office:
            </p>
        </div>



        <table class="">
            <thead style="border-top: 1px solid black;">
                <th>No.</th>
                <th>IAR Number</th>
                <th>IR Number</th>
                <th>RFI Number</th>
                <th>End-User</th>
                <th>Purpose</th>
                <th>Inspector</th>
                <th>Responsible Center</th>
                <th>PO Number</th>
                <th>Payee</th>
                <th>Requested By</th>
            </thead>

            <tbody>

                <?php
                $total = 0;
                foreach ($items as $i => $val) {

                    $iar_number = $val['iar_number'];
                    $ir_number = $val['ir_number'];
                    $rfi_number = $val['rfi_number'];
                    $end_user = $val['end_user'];
                    $purpose = $val['purpose'];
                    $inspector_name = $val['inspector_name'];
                    $responsible_center = $val['responsible_center'];
                    $po_number = $val['po_number'];
                    $payee_name = $val['payee_name'];
                    $requested_by_name = $val['requested_by_name'];
                    $i++;
                    echo "<tr>
                        <td>$i</td>
                        <td>$iar_number</td>
                        <td>$ir_number</td>
                        <td>$rfi_number</td>
                        <td>$end_user</td>
                        <td>$purpose</td>
                        <td>$inspector_name</td>
                        <td>$responsible_center</td>
                        <td>$po_number</td>
                        <td>$payee_name</td>
                        <td>$requested_by_name</td>
                    
                    </tr>";
                }
                ?>

                </tr>
            </tbody>
        </table>
        <div class="row head" style="margin-top:1rem">Thank you.</div>
        <div class="row head" style="margin-top:4rem">Very truly yours,</div>
        <div class="row head" style="margin-top:2rem">
            <div class="head" style="font-weight:bold;right:10;" id="asig_1">

            </div>
            <div class="head" id="oic">Regional Director</div>
        </div>
        <div class="row" style="margin-top:2rem">
            <div class="head" id="for_rd"></div>
        </div>
        <div class="row" style="margin-top: 2rem;">
            <div class="head" id='ass' style="font-weight: bold;"></div>
            <div class="head" id="oic_position_text"></div>
        </div>
        <div class="row" style="margin-top: 20px;">

            <div class="col-sm-3 as">
                <label for="assignatory_1">Regional Director </label>
                <select name="" id="assignatory_1" class="assignatory" onchange="regionalDirector(this)" style="width: 100%;">
                    <option value=""></option>
                </select>
            </div>
            <div class="col-sm-3 as">
                <label for="qwe">OIC</label>
                <!-- <select id="assignatory" onchange="sample(this)" name="assignatory" class=" select" style="width: 100%">
                    <option></option>
                </select> -->
                <?php
                echo Select2::widget([
                    'data' => ArrayHelper::map(Assignatory::find()->asArray()->all(), 'name', 'name'),
                    'name' => 'ass',
                    'options' => ['id' => 'assignatory', 'onChange' => 'sample(this)'],
                    'pluginOptions' => [
                        'placeholder' => 'select',
                        'allowClear' => true,

                    ],
                ])
                ?>
            </div>
            <div class="col-sm-3 as">
                <label for="oic">Regional Director </label>
                <select name="" id="oic_rd" onchange="oicRd(this)" style="width: 100%;">
                    <option value=""></option>
                </select>
            </div>
            <div class="col-sm-3 as">
                <label for="qwe">OIC Position</label>

                <?php
                echo Select2::widget([
                    'data' => ArrayHelper::map(EmployeePosition::find()->asArray()->all(), 'position', 'position'),
                    'name' => 'oic_position',
                    'id' => 'oic_position',
                    'options' => ['onChange' => 'oic_position(this)'],
                    'pluginOptions' => [
                        'placeholder' => 'select',
                        'allowClear' => true,
                    ],
                ])
                ?>
            </div>
        </div>
    </div>

</div>
<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/css/customCss.css", []);
?>
<style>
    table,
    td,
    th {
        background-color: white;

        border: 1px solid black;
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;

    }

    .container {
        background-color: white;
        width: 80%;
        margin-bottom: 10px;
    }

    .row {
        margin-left: 0;
        margin-right: 0;
    }

    .main-footer {
        display: none;
    }

    .head {
        font-size: 12pt;
    }

    @media print {
        .btn {
            display: none;
        }

        td {
            font-size: 10px;
        }

        .as {
            display: none;
        }

        .assignatory {
            display: none;
        }

        .container {
            width: 100%;
        }

        header.onlyprint {
            position: fixed;
            /* Display only on print page (each) */
            top: 0;
            /* Because it's header */
        }

        footer.onlyprint {
            position: fixed;
            bottom: 0;
            /* Because it's footer */
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
            padding-left: 0;
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
    var reference = []
    // $(document).ready(function() {
    //     reference = ["GAY A. TIDALGO"]
    //     $('#assignatory').select2({
    //         data: reference,
    //         placeholder: "Select ",

    //     })
    // })
    // $("#assignatory").change(function() {
    //     console.log("qwe")
    // })
    function oic_position(q) {
        console.log($(q).val())
        $('#oic_position_text').text($(q).val())

    }

    function oicRd(x) {
        $("#oic").text(x.value)
    }

    function regionalDirector(x) {
        $("#asig_1").text(x.value)
    }

    function sample(q) {
        console.log(q.value)

        $("#ass").text(q.value)
        if (q.value == '') {
            $("#for_rd").text('')
        } else {

            $("#for_rd").text('For the Regional Director')
        }


    }
    $(document).ready(function() {
        var oic_rd = ['Officer-in-Charge', 'Regional Director']
        $('#oic_rd').select2({
            data: oic_rd,
            placeholder: "Select ",
            allowClear: true,
            closeOnSelect: true
        })
        $.getJSON(window.location.pathname + '?r=assignatory/get-all-assignatory')

            .then(function(data) {

                var array = []
                $.each(data, function(key, val) {
                    array.push({
                        id: val.name,
                        text: val.name
                    })
                })
                assignatory = array
                $('.assignatory').select2({
                    data: assignatory,
                    placeholder: "Select ",
                    allowClear: true,
                    closeOnSelect: true
                })

            })
    })
</script>