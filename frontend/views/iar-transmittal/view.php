<?php

use app\models\Assignatory;
use app\models\EmployeePosition;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\PurchaseOrderTransmittal */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'IAR Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="">


    <div class="container">

        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </p>


        <div class="row" style="float:right">
            <div class="col-sm-12">

                <?= Html::img(Yii::$app->request->baseUrl . '/frontend/web/dti3.png', ['alt' => 'some', 'class' => 'pull-left img-responsive', 'style' => 'width: 100px;height:100px;margin-left:auto']); ?>
            </div>
        </div>
        <br>
        <table style="margin-top: 7rem;">
            <tr>
                <td colspan="11" style="border: 0;">
                    <span class=" head" style=" margin-bottom:2rem"><?php echo date('F d, Y', strtotime($model->date)) ?></span><br><br>
                    <b class=" head">ADA JUNE M. HORMILLADA</b><br>
                    <span class=" head">State Auditor III</span><br>
                    <span class=" head">OIC - Audit Team Leader</span><br>
                    <span class=" head">COA - DTI Caraga</span><br>

                </td>
            </tr>
            <tr>

                <td colspan="11" style="border: 0;">
                    <span class=" head" style="padding-top: 2rem;padding-bottom: 2rem;">Dear Ma’am Hormillada:</span><br><br>
                    <p style="font-size: 12pt;">
                        We are hereby submitting the following Purchase Orders, with assigned Transmittal # <?= $model->serial_number ?> of DTI Regional Office:
                    </p>
                </td>
            </tr>
        </table>


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
                    $division = $val['division'];
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
                        <td>$division</td>
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
        <div class=" head" style="margin-top:2rem">
            <div class="head" style="font-weight:bold;right:10;" id="signatory">

            </div>
            <div class="head" id="asig_pos">Regional Director</div>
        </div>
        <div class="row" style="margin-top:2rem">
            <div class="head" id="for_rd"></div>
        </div>
        <div class="row" style="margin-top: 2rem;">
            <div class="head" id='ass' style="font-weight: bold;"></div>
            <div class="head" id="oic_position_text"></div>
        </div>
        <div class="row" style="margin-top: 20px;">

            <div class="col select_row" style="margin-top: 20px;">
                <div class="col-sm-3">
                    <label for="employee">Select Signatory</label>
                    <?= Select2::widget([
                        'name' => 'employee',
                        'id' => 'employee',
                        'options' => ['placeholder' => 'Search for a Employee ...'],
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
                                'cache' => true,
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            // 'templateResult' => new JsExpression(''),
                            // 'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                        ],

                    ]) ?>
                </div>
            </div>
        </div>
    </div>

</div>
<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/css/customCss.css", []);
?>
<style>
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
        margin-bottom: 10px;
    }

    .row {
        margin-left: 0;
        margin-right: 0;
    }





    @media print {

        .select_row,
        .btn,
        .assignatory,
        .actions,
        .as,

        .main-footer,
        .links,
        .btn {
            display: none;
        }

        table {
            width: 75%;
        }


        .container {

            padding: 2rem;
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

        .select2-container--default .select2-selection--single,
        .select2-selection .select2-selection--single {
            /* border: 1px solid #d2d6de; */
            /* border-radius: 0; */
            padding: 0;

        }

        .select2-container {
            height: 20px;
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

        /* @page {
            size: auto;
            margin: 0;
            padding-right: 2rem;
        } */

        th,
        td {
            border: 1px solid black;
            padding: 5px;

        }



    }
</style>

<script>
    $(document).ready(function() {
        $('#employee').on('change', () => {
            const emp_id = $('#employee').val()
            $.ajax({
                url: window.location.pathname + "?r=employee/search-employee",
                data: {
                    id: emp_id
                },
                success: function(data) {
                    $('#asig_pos').text(data.results.position)
                    $('#signatory').text(data.results.text)
                }
            })
        })


    })
</script>