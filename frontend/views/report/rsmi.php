<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
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

$this->title = "RSMI";
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
                        'allowClear' => true
                    ]
                ])
                ?>

            </div>
            <div class="col-sm-3">
                <label for="book_id">Book</label>
                <?php

                echo Select2::widget([
                    'data' => ArrayHelper::map(Books::find()

                        ->asArray()->all(), 'name', 'name'),
                    'name' => 'book',
                    'id' => 'book_id',
                    'pluginOptions' => [
                        'placeholder' => 'Select Book',
                        'required' => true,
                        'allowClear' => true
                    ]
                ])
                ?>


            </div>
            <div class="col-sm-3">
                <label for="province">Province</label>
                <?php
                echo Select2::widget([
                    'name' => 'province',
                    'id' => 'province',
                    'data' => [
                        'adn' => 'ADN',
                        'sdn' => 'SDN',
                        'ads' => 'ADS',
                        'sds' => 'SDS',
                        'pdi' => 'PDI',
                    ],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'placeholder' => 'Select Province'
                    ]
                ])

                ?>
            </div>

            <div class="col-sm-3" style="margin-top: 2.5rem;">
                <button class="btn btn-success" id="generate" type="submit">Generate</button>
            </div>

        </div>
        <div class="col-sm-3">

        </div>
    </form>

    <?php Pjax::begin(['id' => 'tax_container', 'clientOptions' => ['method' => 'POST']]) ?>
    <div id="con">
        <table class="" id="data_table">

            <thead>
                <th>Row Labels</th>
                <th class="amount">Sum of Vat/NonVat</th>
                <th class="amount">Sum of Expanded Tax</th>
            </thead>
            <tbody>
                <?php
                if (!empty($dataProvider)) {
                    $total_vat = 0;
                    $total_exp = 0;
                    foreach ($dataProvider as $i=>$data) {
         
                        // return ob_get_clean();
                        echo "<tr>
                        <td>{$i}</td>
                        <td class='amount'></td>
                        <td class='amount'></td>
                        <td class='amount'></td>
                        </tr>";
                        $total_vat = 0;
                        foreach ($data as $val) {
                            echo "<tr>
                            <td></td>
                            <td>{$val['dv_number']}</td>
                            <td class='amount'>" . number_format($val['total_withdrawal'], 2) . "</td>
                            <td class='amount'></td>
                            
                            </tr>";
                             $total_vat += $val['total_withdrawal'];
                        }
                        echo "<tr>
                        <td> Total</td>
                        <td class='amount'>" . number_format($total_vat, 2) . "</td>
                        <td class='amount'>" . number_format($total_exp, 2) . "</td>
                        
                        </tr>";


                        // $total_vat += $data['total_vat'];
                        // $total_exp += $data['total_vat'];
                    }
                //     echo "<tr>
                // <td> Total</td>
                // <td class='amount'>" . number_format($total_vat, 2) . "</td>
                // <td class='amount'>" . number_format($total_exp, 2) . "</td>
                
                // </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php Pjax::end() ?>
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

<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<?php
SweetAlertAsset::register($this);
$script = <<< JS


    function addToDataTable(data){

        
    }
    $(document).on('pjax:success', function() {
        setTimeout(() => {
            $('#con').show()
        $('#dots5').hide()
        }, 1000);
     
    });
    $('#filter').submit(function(e){
        e.preventDefault();
        $('#dots5').show()
        $.pjax({
            container:'#tax_container',
            type:'POST',
            url:window.location.pathname +'?r=report/rsmi',
            data:$("#filter").serialize()
       
        })
    })
JS;
$this->registerJs($script);
?>