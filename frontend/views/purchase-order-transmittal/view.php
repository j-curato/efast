<?php

use app\models\Assignatory;
use app\models\EmployeePosition;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PurchaseOrderTransmittal */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Order Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="purchase-order-transmittal-view">



    <div class="container card" style="padding: 1rem;">
        <h5><?= Html::encode($this->title) ?></h5>
        <p>
            <?= Yii::$app->user->can('update_purchase_order_transmittal') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate btn btn-primary']) : '' ?>
        </p>

        <div class="row" style="float:right">
            <?= Html::img(Yii::$app->request->baseUrl . '/frontend/web/dti3.png', ['alt' => 'some', 'class' => 'pull-left img-responsive', 'style' => 'width: 100px;height:100px;margin-left:auto']); ?>
        </div>
        <!-- <div class="row" style="margin-top: 130px;">
            <span class=" head" style=" margin-bottom:2rem"><?php echo date('F d, Y', strtotime($model->date)) ?></span>
            <span class=" head" style="font-weight: bold;">ADA JUNE M. HORMILLADA</span>
            <span class=" head">State Auditor III</span>
            <span class=" head">OIC - Audit Team Leader</span>
            <span class=" head">COA - DTI Caraga</span>
            <span class=" head" style="padding-top: 2rem;padding-bottom: 2rem;">Dear Ma’am Hormillada:</span>
            <p style="font-size: 12pt;">
                We are hereby submitting the following Purchase Orders, with assigned Transmittal # <?php
                                                                                                    ?> of DTI Regional Office:
            </p>
        </div> -->


        <table>
            <tr>
                <td colspan="5" style="border: 0;">
                    <span class=" head" style=" margin-bottom:2rem"><?php echo date('F d, Y', strtotime($model->date)) ?></span><br><br>
                    <b class=" head">ADA JUNE M. HORMILLADA</b><br>
                    <span class=" head">State Auditor III</span><br>
                    <span class=" head">OIC - Audit Team Leader</span><br>
                    <span class=" head">COA - DTI Caraga</span><br>

                </td>
            </tr>
            <tr>

                <td colspan="5" style="border: 0;">
                    <span class=" head" style="padding-top: 2rem;padding-bottom: 2rem;">Dear Ma’am Hormillada:</span><br><br>
                    <p style="font-size: 12pt;">
                        We are hereby submitting the following Purchase Orders, with assigned Transmittal # <?= $model->serial_number ?> of DTI Regional Office:
                    </p>
                </td>
            </tr>
        </table>
        <table class="">
            <thead>


                <tr>
                    <th>No.</th>
                    <th>PO Number</th>
                    <th>Payee</th>
                    <th>Purpose</th>
                    <th>Amount</th>
                </tr>

            </thead>

            <tbody>

                <?php
                $total = 0;
                foreach ($items as $i => $val) {

                    $total += floatval($val['total_amount']);
                    $i++;
                    echo "<tr>
                        <td>$i</td>
                        <td>{$val['serial_number']}</td>
                        <td>{$val['payee']}</td>
                        <td>{$val['purpose']}</td>
                        <td class='amount'>" . number_format($val['total_amount'], 2) . "</td>
                    
                    </tr>";
                }
                ?>
                <tr>

                    <td colspan="4" style="font-weight: bold;text-align:center"> Total</td>
                    <td style='text-align:right'> <?php
                                                    echo number_format($total, 2);
                                                    ?></td>
                </tr>
            </tbody>
        </table>
        <div class="row head" style="margin-top:1rem">Thank you.</div>
        <div class="row head" style="margin-top:4rem">Very truly yours,</div>
        <div class=" head" style="margin-top:2rem">
            <div class="head" style="font-weight:bold;right:10;" id="signatory"></div>
            <div class="head" id="asig_pos">Regional Director</div>
        </div>

        <div class="row" style="margin-top:2rem">
            <div class="head" id="for_rd"></div>
        </div>
        <div class="row" style="margin-top: 2rem;">
            <div class="head" id='ass' style="font-weight: bold;"></div>
            <div class="head" id="oic_position_text"></div>
        </div>
        <div class="row select_row" style="margin-top: 20px;">
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


        .select_row,
        .as,
        .assignatory,
        .links,

        .btn,
        .actions,
        .main-footer {
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



        th,
        td {
            border: 1px solid black;
            padding: 5px;

        }

        @page {
            size: auto;
            margin: 0;
            margin-top: 0.5cm;
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