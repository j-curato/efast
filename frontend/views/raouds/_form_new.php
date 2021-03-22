<link href="/dti-afms-2/frontend/web/css/select2.min.css" rel="stylesheet" />
<!-- <link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre.min.css">
<link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre-exp.min.css">
<link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre-icons.min.css"> -->

<?php

use kartik\date\DatePicker;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\grid\GridView;
use yii\helpers\Html;
?>
<div class="test">


    <div id="container" class="container">

        <form name="add_data" id="add_data">
            <?php
            $q = 0;
            if (!empty($model)) {

                $q = $model;
            }
            echo " <input type='text' id='update_id' name='update_id'  style='display:none'>";
            ?>
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

            </div>



            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    'document_recieve_id',
                    'fund_cluster_code_id',
                    'financing_source_code_id',
                    'fund_category_and_classification_code_id',
                    //'authorization_code_id',
                    //'mfo_pap_code_id',
                    //'fund_source_id',
                    //'reporting_period',
                    //'serial_number',
                    //'allotment_number',
                    //'date_issued',
                    //'valid_until',
                    //'particulars',


                    ['class' => 'yii\grid\ActionColumn'],
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                            return ['value' => $model->id, 'onchange' => 'sample(this)', 'style' => 'width:20px;',];
                        }
                    ],
                    [
                        'label' => 'Actions',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ' ' . Html::input('text', "amount[$model->id]", '', ['class' => 'amounts']);
                        }
                    ]

                ],
            ]); ?>
            <input type="submit" name="submit">


        </form>


    </div>
    <style>
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

    <!-- <script src="/dti-afms-2/frontend/web/js/jquery.min.js" type="text/javascript"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- <script src="/dti-afms-2/frontend/web/js/select2.min.js"></script> -->
    <script>
        function sample(checkbox) {
            var isDisable = true
            if (checkbox.checked) {
                isDisable = false
            }
            enableDisable(isDisable,checkbox.value)

        }

        function enableDisable(isDisable,index) {
            $(`:input[name="amount[${index}]"]`).prop('disabled', isDisable);
        }
    </script>
</div>


<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<?php

$script = <<< JS
      $(document).ready(function() {
                $('form input[class="amounts"]').prop("disabled", true);
            })
    JS;
$this->registerJs($script);
?>