<link href="/dti-afms-2/frontend/web/css/site.css" rel="stylesheet" />
<?php

use app\models\AdvancesEntries;
use app\models\Books;
use app\models\DvAucs;
use app\models\MajorAccounts;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use kartik\select2\Select2;
use Mpdf\Tag\Select;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "List of Pending DV's";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index panel" style="background-color: white;padding:20px">



    <form id="filter">
        <div class="row">
            <div class="col-sm-3">
                <label for="reporting_period">Reporting Peiod</label>
                <?php

                echo DatePicker::widget([
                    'name' => 'reporting_period',
                    'id' => 'reporting_period',
                    'pluginOptions' => [
                        'minViewMode' => 'months',
                        'autoclose' => true,
                        'format' => 'yyyy-mm',
                        'required' => true,

                    ]
                ])
                ?>

            </div>
            <div class="col-sm-3">
                <label for="book_id">Book</label>
                <?php

                echo Select2::widget([
                    'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                    'name' => 'book_id',
                    'id' => 'book_id',
                    'pluginOptions' => [
                        'placeholder' => 'Select Book',
                        'required' => true,
                    ]
                ])
                ?>

            </div>
            <div class="col-sm-3">
                <label for="allotment_class">Allotment Class</label>
                <?php

                echo Select2::widget([
                    'data' => ArrayHelper::map(MajorAccounts::find()->asArray()->all(), 'name', 'name'),
                    'name' => 'allotment_class',
                    'id' => 'allotment_class',
                    'pluginOptions' => [
                        'required' => true,
                        'placeholder' => 'Select Allotment'
                    ]
                ])
                ?>

            </div>
            <div class="col-sm-3" style="margin-top: 2.5rem;">
                <button class="btn btn-success" id="generate">Generate</button>
            </div>

        </div>
    </form>

    <div id="con">
        <table class="" id="data_table">

            <thead>
                <th>MFO/PAP Code</th>
                <th>MFO/PAP </th>
                <th class="amount">Allotment/Appropriation Recieved</th>
                <th class="amount">Obligation Incured</th>
                <th class="amount">Disbursements</th>
                <th class="amount">Taxes Withheld</th>
                <th>MFO/PAP Description</th>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <div id="dots5" style="display: none;">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 5rem;
        padding: 0;
    }

    #con {
        display: none;
        margin-top: 20px;
    }

    table,
    th,
    td {
        border: 1px solid black;
        text-align: center;
        padding: 12px;
    }

    .amount {
        text-align: right;
    }

    @media print {

        table,
        th,
        td {
            padding: 5px;
            font-size: 10px;
        }

        .row {
            display: none
        }

        .main-footer {
            display: none;
        }

        .panel {
            padding: 0;
        }

    }
</style>
<!-- <script src="/dti-afms-2/frontend/web/js/jquery.min.js" type="text/javascript"></script>
<link href="/dti-afms-2/frontend/web/js/select2.min.js" />
<link href="/dti-afms-2/frontend/web/css/select2.min.css" rel="stylesheet" />
<link href="/dti-afms-2/frontend/web/js/jquery.dataTables.js" />
<link href="/dti-afms-2/frontend/web/css/jquery.dataTables.css" rel="stylesheet" /> -->


<script>
    $('#generate').click(function(e) {
        e.preventDefault()
        $('#con').hide()
        $('#dots5').show()
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=report/conso-detailed-dv',
            data: $('#filter').serialize(),
            success: function(data) {
                var res = JSON.parse(data)
                console.log(res)
                $("#data_table > tbody").html("");
                var taxes_withheld = 0
                var obligation = 0
                var disbursement = 0
                var total_allotment_recieve = 0
                var total_conso_total_allotment_recieve = 0
                var total_conso_obligation = 0
                var total_conso_disbursement = 0
                var total_conso_tax = 0
                for (var i = 0; i < res.length; i++) {
                    obligation = res[i]['conso_total_obligation']
                    taxes_withheld = parseFloat(res[i]['conso_total_ewt']) + parseFloat(res[i]['conso_total_vat']);
                    disbursement = parseFloat(res[i]['conso_total_dv'])
                    total_allotment_recieve = res[i]['total_allotment_recieve']
                    if (total_allotment_recieve == null) {
                        total_allotment_recieve = 0
                    }
                    if (obligation == null) {
                        obligation = 0
                    }
                    console.log
                    total_conso_total_allotment_recieve += parseFloat(total_allotment_recieve)
                    total_conso_obligation += parseFloat(obligation)
                    total_conso_disbursement += parseFloat(disbursement)
                    total_conso_tax += parseFloat(taxes_withheld)
                    console.log(total_conso_total_allotment_recieve)
                    var mfo_code = res[i]['mfo_code'] ? res[i]['mfo_code'] : ''
                    var mfo_name = res[i]['mfo_name'] ? res[i]['mfo_name'] : 'Prior Year Accounts Payable'
                    var mfo_description = res[i]['mfo_description'] ? res[i]['mfo_description'] : ''
                    var row = `<tr>

                        <td>${mfo_code}</td>
                        <td>${mfo_name}</td>
                        <td class='amount' >${thousands_separators(total_allotment_recieve)}</td>
                        <td class='amount'>  ${thousands_separators(parseFloat(obligation).toFixed(2))}</td>
                        <td class='amount'>  ${thousands_separators(parseFloat(disbursement).toFixed(2))}</td>
                        <td class='amount'>  ${thousands_separators(parseFloat(taxes_withheld).toFixed(2))}</td>
                        <td>${mfo_description}</td>
                    </tr>`

                    $('#data_table tbody').append(row)
                }
                var row = `<tr>

                        <td></td>
                        <td>TOTAL</td>
                        <td class='amount' >${thousands_separators(total_conso_total_allotment_recieve)}</td>
                        <td class='amount'>  ${thousands_separators(parseFloat(total_conso_obligation).toFixed(2))}</td>
                        <td class='amount'>  ${thousands_separators(parseFloat(total_conso_disbursement).toFixed(2))}</td>
                        <td class='amount'>  ${thousands_separators(parseFloat(total_conso_tax).toFixed(2))}</td>
                        <td>${mfo_description}</td>
                    </tr>`

                $('#data_table tbody').append(row)
            },
            complete: function() {
                $('#con').show()
                $('#dots5').hide()
            }
        })
    })

    $(document).ready(function() {
        // $('#dots5').hide()
    })
</script>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/jquery.dataTables.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/scripts.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<?php
SweetAlertAsset::register($this);
$script = <<< JS

JS;
$this->registerJs($script);
?>