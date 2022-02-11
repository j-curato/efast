<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RoFur */
/* @var $form yii\widgets\ActiveForm */

$divisions_data = [
    'all' => 'All',
    'cpd' => 'CPD',
    'fad' => 'FAD',
    'idd' => 'IDD',
    'ord' => 'ORD',
    'sdd' => 'SDD'
];
$document_recieve_data = Yii::$app->db->createCommand("SELECT 'all' as id, 'ALL' as `name`
UNION
SELECT id,`name`FROM document_recieve 
")->queryAll();
?>

<div class="ro-fur-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">

        <div class="col-sm-2">
            <?= $form->field($model, 'to_reporting_period')->widget(DatePicker::class, [
                'name' => 'to_reporting_period',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm',
                    'minViewMode' => 'months'
                ]
            ]) ?>
        </div>
        <?php

        $user = Yii::$app->user->can('super-user');
        if ($user) { ?>
            <div class="col-sm-2">
                <?= $form->field($model, 'division')->widget(Select2::class, [
                    'name' => 'division',
                    'id' => 'division',
                    'data' => $divisions_data,
                    'options' => ['placeholder' => 'Select Division'],
                ]) ?>
            </div>
        <?php }; ?>

        <div class="col-sm-3">
            <?= $form->field($model, 'document_recieve_id')->widget(Select2::class, [
                'name' => 'document_recieve',
                'id' => 'document_recieve',
                'data' => ArrayHelper::map($document_recieve_data, 'id', 'name'),
                'options' => ['placeholder' => 'Select Document'],
            ]) ?>
        </div>
        <div class="col-sm-2" style="margin-top: 2.5rem;">
            <div class="form-group">
                <button class="btn btn-primary" id="generate" type="button">Generate</button>

                <?php

                if ($user) {
                    echo   Html::submitButton('Save', ['class' => 'btn btn-success']);
                }
                ?>
            </div>
        </div>

    </div>


    <?php ActiveForm::end(); ?>

</div>
<div class="jev-preparation-index " style="background-color: white;padding:20px">



    <!-- <div id="con"> -->

    <div id='con'>

        <table class="" id="fur_table" style="margin-top: 30px;">
            <thead>
                <tr>

                    <th>Division</th>
                    <th style="width: 250px;">MFO/PAP </th>
                    <th>Account</th>
                    <th>Beginning Balance</th>
                    <th>Allotment Recieved</th>
                    <th>Obligation Incured</th>
                    <th> Balance</th>
                    <th> FUR%</th>
                </tr>


            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <!-- </div> -->
    <div id="dots5" style="display: none;">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<style>
    table,
    th,
    td {
        border: 1px solid black;
        text-align: center;
        padding: 12px;
    }

    #summary_table {
        margin-top: 30px;
    }

    /* #con {
        display: none;
    } */

    .amount {
        text-align: right;
    }

    @media print {
        #summary_table {
            margin-top: 0;
        }

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

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/module_js_css/roFur/ro-fur.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/scripts.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    var mfo = []
    var allotment_balances = []
    $('#generate').click((e) => {
        e.preventDefault();
        $('#con').hide()
        $('#dots5').show()
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=ro-fur/division-fur',
            data: $("#w0").serialize(),
            success: function(data) {
                var res = JSON.parse(data)
                mfo = res.mfo_pap
                allotment_balances = res.allotments
                setTimeout(function() {

                    addData(res.result)
                    $('#con').show()
                    $('#dots5').hide()
                }, 1000)

            }

        })
    })
</script>