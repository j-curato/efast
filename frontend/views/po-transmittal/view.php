<?php

use app\models\Assignatory;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PoTransmittal */

$this->title = $model->transmittal_number;
$this->params['breadcrumbs'][] = ['label' => 'Po Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="po-transmittal-view">



    <div class="container">
        <div class="row as">
            <p>
                <?= Html::a('Update', ['update', 'id' => $model->transmittal_number], ['class' => 'btn btn-primary']) ?>
                <?php
                $color = '';
                $action = '';
                if (strtolower($model->status) == 'pending_at_ro') {
                    $color = 'btn-success';
                    $action = 'Accept';
                } else {
                    $color = 'btn-danger';
                    $action = 'Pending';
                }
                if (Yii::$app->user->identity->province === 'ro_admin') {
                    echo Html::a($action, ['accept', 'id' => $model->transmittal_number], [
                        'class' => "btn $color",
                        'data' => [
                            'confirm' => "Are you sure you want to $action this item?",
                            'method' => 'post',
                        ],
                    ]);
                }
                $province =strtolower( Yii::$app->user->identity->province);
                $prov ='';
                $provinces = [
                    'adn'=>'Agusan Del Norte',
                    'ads'=>'Agusan Del Sur',
                    'sdn'=>'Surigao Del Norte',
                    'sds'=>'Surigao Del Sur',
                    'pdi'=>'Dinagat Islands',
                ];
                if (
                    $province === 'adn' ||
                    $province === 'ads' ||
                    $province === 'sdn' ||
                    $province === 'sds' ||
                    $province === 'pdi'
                ) {
                    $prov = $provinces[$province];
                }
                ?>
            </p>

        </div>

        <div class="row" style="float:right">
            <?= Html::img(Yii::$app->request->baseUrl . '/frontend/web/dti3.png', ['alt' => 'some', 'class' => 'pull-left img-responsive', 'style' => 'width: 100px;height:100px;margin-left:auto']); ?>
        </div>

        <table id="header" style="border:none">

            <tbody style="border:none">

                <tr>

                    <td style="width: 250px;">
                        For
                    </td>
                    <td>
                        <span class="head bold">

                            GAY A. TIDALGO,CESO IV
                        </span> <br>
                        <span style="font-size:12px">
                            OIC Regional Director, DTI-CARAGA
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        From
                    </td>
                    <td>
                        <span class="head bold" id='asig_1'>

                            <!-- BRENDA B. CORVERA -->

                        </span> <br>
                        <span class="head" id='asig_1_position' style="font-size:12px">
                            <!-- Position -->
                        </span>

                    </td>
                </tr>

                <tr>
                    <td>
                        Date
                    </td>
                    <td>
                        <span>

                            <?php
                            echo date('F d, Y', strtotime($model->date));
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Subject
                    </td>
                    <td class="bold">
                        <span>DV Transmittal No. </span>
                        <span>
                            <?php
                            echo $model->transmittal_number;
                            ?>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>

        <hr>

        <span style="font-size: 15px;">
            We are hereby submitting the following DV, with assigned Transmittal# <?php echo $model->transmittal_number ?> of DTI <?php echo $prov?>.
        </span>

        <table class="data_table">
            <thead style="border-top: 1px solid black;border-bottom: 1px solid black;">
                <th>No.</th>
                <th>DV Number</th>
                <th>Check/ADA</th>
                <th>Check/ADA Date</th>
                <th>Payee</th>
                <th>Particulars</th>
                <th>Amount</th>
                <th class='status'>Status</th>
            </thead>

            <tbody>

                <?php
                $total = 0;
                $q = 1;
                foreach ($model->poTransmittalEntries as $i => $val) {
                    $query = (new \yii\db\Query())
                        ->select(["SUM(liquidation_entries.withdrawals) as total_disbursed"])
                        ->from('liquidation')
                        ->join("LEFT JOIN", "liquidation_entries", "liquidation.id = liquidation_entries.liquidation_id")
                        ->where("liquidation.id =:id", ['id' => $val->liquidation_id])
                        ->one();

                    $qwe = '';
                    $display = 'display:none;';
                    $payee = !empty($val->liquidation->payee) ? $val->liquidation->payee : $val->liquidation->poTransaction->payee;
                    $particular = !empty($val->liquidation->particular) ? $val->liquidation->particular : $val->liquidation->poTransaction->particular;

                    echo "<tr>
                        <td>$q</td>
                        <td>{$val->liquidation->dv_number}</td>
                        <td>{$val->liquidation->check_number}</td>
                        <td>{$val->liquidation->check_date}</td>
                        <td>{$payee}</td>
                        <td>{$particular}</td>
       
                        <td style='text-align:right'>" . number_format($query['total_disbursed'], 2) . "</td>
                    ";
                    if (Yii::$app->user->identity->province === 'ro_admin') {

                        $status = 'Remove';
                        $color = 'btn-danger';
                        if ($val->liquidation->status === 'at_po') {
                            $status = 'ibalik';
                            $color = 'btn-success';
                        }
                        $qwe = Html::a($status, ['return', 'id' => $val->id], [
                            'class' => "btn $color ",
                            'data' => [
                                'confirm' => "Are you sure you want to  this item?",
                                'method' => 'post',
                            ],
                        ]);
                        echo "  <td class='status'>" .
                            $qwe
                            . " </td>";
                    } else {
                        if ($val->status === 'returned') {

                            echo "<td class='status'> Returned</td>";
                        }
                    }
                    echo " </tr>";
                    $total += $query['total_disbursed'];
                    $q++;
                }
                // }
                ?>
                <tr>

                    <td colspan="6" style="font-weight: bold;text-align:center"> Total</td>
                    <td style='text-align:right'> <?php echo number_format($total, 2); ?></td>
                </tr>
            </tbody>
        </table>
        <div class="row head" style="margin-top:1rem;">Thank you.</div>
        <!-- <div class="row head" style="margin-top:4rem">Very truly yours,</div> -->
        <div class="row head" style="margin-top:2rem">

        </div>
        <div class="row" style="margin-top:2rem">
            <div class="head" id="for_rd"></div>
        </div>
        <div class="row" style="margin-top: 2rem;">
            <div class="head" id='ass' style="font-weight: bold;"></div>
            <div class="head" id='position' style="font-size:12px"></div>
        </div>
        <div class="row" style="margin-top: 20px;">

            <div class="col-sm-3 as">
                <label for="assignatory_1">Provincial Director </label>
                <select name="" id="assignatory_1" class="asignatory" onchange="regionalDirector(this)" style="width: 100%;">
                    <option value=""></option>
                </select>
            </div>
            <div class="col-sm-4 as">
                <label for="qwe">OIC</label>
                <select id="assignatory" onchange="sample(this)" name="" class=" asignatory" style="width: 100%">
                    <option></option>
                </select>
                <?php
                // echo Select2::widget([
                //     'data' => ArrayHelper::map(Assignatory::find()->asArray()->all(), 'name', 'name'),
                //     'name' => 'ass',
                //     'options' => ['id' => 'assignatory', 'onChange' => 'sample(this)'],
                //     'pluginOptions' => [
                //         'placeholder' => 'select',
                //         'allowClear' => true,

                //     ],
                // ])
                ?>
            </div>
            <!-- <div class="col-sm-3 as">
                <label for="oic">Provincial Director </label>
                <select name="" id="oic_rd" onchange="oicRd(this)" style="width: 100%;">
                    <option value=""></option>
                </select>
            </div> -->
        </div>
    </div>
</div>
<style>
    .data_table {
        width: 100%;
        padding: 10px;
    }

    .bold {
        font-weight: bold;
    }

    #header {
        width: 100%;
        margin-bottom: 20px;
        padding: 10px;
    }

    #header td {
        border: none
    }

    .container {
        padding: 50px;
    }

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

    @page {
        margin: 30px;
    }

    @media print {
        td {
            font-size: 14px;
        }

        body {
            margin: 25mm 25mm 25mm 25mm;
        }

        .container {
            padding: 0;
        }

        #header td {
            font-size: 15px;
        }

        .status {
            display: none;
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

<!-- 
<link href="/afms/frontend/web/js/select2.min.js" />
    <link href="/afms/frontend/web/css/select2.min.css" rel="stylesheet" /> -->
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

    function oicRd(x) {
        $("#oic").text(x.value)
    }

    function regionalDirector(x) {
        $("#asig_1").text(x.value.toUpperCase())
        if (x.value == '') {
            $("#for_rd").text('')
            $("#asig_1_position").text('')
        } else {
            var qwer = json_assignatory.filter(record => record.name === x.value)

            // console.log(toTitleCase(qwer[0].position))
            $("#asig_1_position").text(qwer[0].position)
        }
    }

    function toTitleCase(str) {
        return str.replace(/\w\S*/g, function(txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
    }

    function sample(q) {



        $("#ass").text(q.value.toUpperCase())
        if (q.value == '') {
            $("#for_rd").text('')
            $("#position").text('')
        } else {
            var qwer = json_assignatory.filter(record => record.name === q.value)

            console.log(qwer[0].position)
            $("#position").text(qwer[0].position)
            $("#for_rd").text('For the Provincial Director')
        }


    }
    var json_assignatory = undefined
    $(document).ready(function() {
        var oic_rd = ['Officer-in-Charge', 'Provincial Director']
        $('#oic_rd').select2({
            data: oic_rd,
            placeholder: "Select OIC",
            allowClear: true,
            closeOnSelect: true
        })
        $.getJSON('/afms/frontend/web/index.php?r=po-assignatory/get-all-assignatory')

            .then(function(data) {

                var array = []
                json_assignatory = data
                $.each(data, function(key, val) {
                    array.push({
                        id: val.name,
                        text: val.name
                    })
                })

                assignatory = array

                $('.asignatory').select2({
                    data: assignatory,
                    placeholder: "Select ",
                    allowClear: true,
                    closeOnSelect: true
                })

            })
    })
</script>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>