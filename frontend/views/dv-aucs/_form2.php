<?php

use app\models\Books;
use kartik\date\DatePicker;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\datetime\DateTimePicker;
use kartik\money\MaskMoney;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

$payee = '';
$transaction_type = '';
$book = '';
$particular = '';
$date_receive = '';
?>
<div class="test">




    <div id="container" class="container">

        <div class="row">

            <div class="col-sm-3">
                <label for="reporting_period">Reporting Period</label>
                <?php
                echo DatePicker::widget([
                    'name' => 'reporting_period',
                    'id' => 'reporting_period',
                    // 'value' => '12/31/2010',
                    'options' => ['required' => true],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm',
                        'startView' => "year",
                        'minViewMode' => "months",
                    ]
                ]);
                ?>
            </div>


            <div class="row">
                <div class="col-sm-3">
                    <label for="payee">Payee</label>
                    <?php
                    echo Select2::widget([
                        'name' => 'payee',
                        'data' => $payee,

                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 1,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                            ],
                            'ajax' => [
                                'url' => Yii::$app->request->baseUrl . '?r=payee/search-payee',
                                'dataType' => 'json',
                                'delay' => 250,
                                'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                                'cache' => true
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                            'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                        ],

                    ])

                    ?>
                    <span class="payee_id_error form-error"></span>
                </div>
                <div class="col-sm-3" style="height:60x">
                    <label for="transaction">Transaction Type</label>
                    <?php
                    echo Select2::widget([
                        'id' => 'transaction_type',
                        'name' => 'transaction_type',
                        'data' => [
                            "Single"  => "Single",
                            "Multiple" => "Multiple",
                            "Accounts Payable"  => "Accounts Payable",
                            "Replacement to Stale Checks" =>  "Replacement to Stale Checks",
                            'Replacement of Check Issued' =>  'Replacement of Check Issued',
                        ],
                        'value' => $transaction_type,
                        'pluginOptions' => [
                            'placeholder' => 'Select Transaction Type'
                        ]
                    ])
                    ?>
                </div>
                <div class="col-sm-3" id='bok'>
                    <label for="book">Book</label>
                    <?php
                    echo Select2::widget([
                        'name' => 'book',
                        'id' => 'book',
                        'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                        'value' => $book,
                        'pluginOptions' => [
                            'placeholder' => 'Select Book'
                        ]
                    ])
                    ?>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">

                    <?php


                    echo "<label for='date' style='text-align:center'>Date Receive</label>";
                    echo DateTimePicker::widget([
                        'name' => 'date_receive',
                        'id' => 'date_receive',
                        'value' => $date_receive,
                        'options' => [
                            'style' => 'background-color:white',
                            'required' => true
                        ],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd HH:ii P',
                            'autoclose' => true
                        ]
                    ]);


                    ?>
                </div>

                <div class="col-sm-3"></div>
            </div>
            <div class="row">
                <label for="particular">Particular</label>
                <textarea name="particular" id="particular" placeholder="PARTICULAR" required rows="3"><?php echo $particular ?></textarea>
            </div>



        </div>
    </div>

    <style>
        textarea {
            max-width: 100%;
            width: 100%;
        }

        .grid-view td {
            white-space: normal;
        }

        .select {
            width: 500px;
            height: 2rem;
        }

        #submit {
            margin: 10px;
        }

        input {
            width: 100%;
            font-size: 15px;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid black;

        }

        .row {
            margin: 5px;
        }

        .container {
            background-color: white;
            height: auto;
            padding: 10px;
            border-radius: 2px;
        }

        .accounting_entries {
            background-color: white;
            padding: 2rem;
            border: 1px solid black;
            border-radius: 5px;
        }

        .swal-text {
            background-color: #FEFAE3;
            padding: 17px;
            border: 1px solid #F0E1A1;
            display: block;
            margin: 22px;
            text-align: center;
            color: #61534e;
        }
    </style>

    <script src="<?= Url::base() ?>/frontend/web/js/scripts.js" type="text/javascript"></script>

    <?php

    // $csrfTokenName = Yii::$app->request->csrfTokenName;
    $this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);

    $csrfToken = Yii::$app->request->csrfToken;
    ?>

    <!-- <script src="/dti-afms-2/frontend/web/js/select2.min.js"></script> -->
    <script>

    </script>


    <?php
    $this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
    SweetAlertAsset::register($this); ?>