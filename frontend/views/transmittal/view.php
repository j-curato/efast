<?php

use app\models\Assignatory;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Transmittal */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="transmittal-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p> -->


    <div class="container">
        <div class="row assignatory">

            <div class="col-sm-4">
                <label for="assignatory"></label>
                <!-- <select id="assignatory" onchange="sample(this)" name="assignatory" class=" select" style="width: 100%">
                    <option></option>
                </select> -->
                <?php
                echo Select2::widget([
                    'data' => ArrayHelper::map(Assignatory::find()->asArray()->all(), 'name', 'name'),
                    'name' => 'assignatory',
                    'options' => ['id' => 'assignatory', 'onChange' => 'sample(this)'],
                    'pluginOptions' => [
                        'placeholder' => 'select',
                        'allowClear' => true
                    ]
                ])
                ?>
            </div>
        </div>

        <div class="row" style="float:right">

            <?= Html::img('@web/dti3.png', ['alt' => 'some', 'class' => 'pull-left img-responsive', 'style' => 'width: 100px;height:100px;margin-left:auto']); ?>
        </div>
        <div class="row" style="margin-top: 130px;">
            <div class="row head" style=" margin-bottom:2rem"><?php echo date('F d, Y', strtotime($model->date)) ?></div>
            <div class="row head" style="font-weight: bold;">MARION T. MONROID</div>
            <div class="row head">State Auditor III</div>
            <div class="row head">OIC - Audit Team Leader</div>
            <div class="row head">COA - DTI Caraga</div>
            <div class="row head" style="padding-top: 2rem;padding-bottom: 2rem;">Dear Ma’am Monroid:</div>
            <p style="font-size: 12pt;">

                We are hereby submitting the following DVs, with assigned Transmittal # <?php echo $model->transmittal_number; ?> of DTI Regional Office:
            </p>
        </div>



        <table class="">
            <thead style="border-top: 1px solid black;">
                <th>No.</th>
                <th>DV Number</th>
                <th>Check/ADA</th>
                <th>Check/ADA Date</th>
                <th>Payee</th>
                <th>Particulars</th>
                <th>Amount</th>
            </thead>

            <tbody>

                <?php
                $total = 0;
                foreach ($model->transmittalEntries as $val) {
                    $query = (new \yii\db\Query())
                        ->select(["SUM(dv_aucs_entries.amount_disbursed) as total_disbursed"])
                        ->from('dv_aucs')
                        ->join("LEFT JOIN", "dv_aucs_entries", "dv_aucs.id = dv_aucs_entries.dv_aucs_id")
                        ->where("dv_aucs.id =:id", ['id' => $val->cashDisbursement->dv_aucs_id])
                        ->one();
                    echo "<tr>
                        <td></td>
                        <td>{$val->cashDisbursement->dvAucs->dv_number}</td>
                        <td>{$val->cashDisbursement->check_or_ada_no}</td>
                        <td>{$val->cashDisbursement->issuance_date}</td>
                        <td>{$val->cashDisbursement->dvAucs->payee->account_name}</td>
                        <td>{$val->cashDisbursement->dvAucs->particular}</td>
                        <td style='text-align:right'>" . number_format($query['total_disbursed'], 2)
                        . "</td>
                    </tr>";
                    $total += $query['total_disbursed'];
                }
                ?>
                <tr>

                    <td colspan="6" style="font-weight: bold;text-align:center"> Total</td>
                    <td style='text-align:right'> <?php echo number_format($total, 2); ?></td>
                </tr>
            </tbody>
        </table>
        <div class="row head" style="margin-top:1rem">Thank you.</div>
        <div class="row head" style="margin-top:4rem">Very truly yours,</div>
        <div class="row head" style="margin-top:2rem">
            <div class="head" style="font-weight:bold">EDWIN O. BANQUERIGO</div>
            <div class="head">Regional Director</div>
        </div>
        <div class="row" style="margin-top:2rem">
            <div class="head" id ="for_rd"></div>
        </div>
        <div class="row" style="margin-top: 2rem;">
            <div class="head" id='ass' style="font-weight: bold;"></div>
        </div>
    </div>
    <script src="/dti-afms-2/frontend/web/js/jquery.min.js" type="text/javascript"></script>
    <link href="/dti-afms-2/frontend/web/js/select2.min.js" />
    <link href="/dti-afms-2/frontend/web/css/select2.min.css" rel="stylesheet" />
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

        function sample(q) {
            console.log(q.value)

            $("#ass").text(q.value)
            $("#for_rd").text('For the Regional Director')

        }
    </script>
</div>

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
        td {
            font-size: 10px;
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


    }
</style>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<?php
$script = <<< JS
    $("#assignatory").change(function(){
      
        console.log("qwe")
    })

JS;

?>