<?php

use yii\widgets\Pjax;
use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "CIBR";
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="dots5">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
</div>
<div class="cibr_view" style="background-color: white;display:none;">

    <?php
    $prov = [];
    $color = $model->is_final === 1 ? 'btn-danger' : 'btn-success';
    $document_link = '';
    // return json_encode($dataProvider);
    ?>
    <div class="row">

        <div class="col-sm-5">
            <input type="hidden" value="<?= $model->id ?>" name='id' id='model_id'>
            <button type='button' id="export" class="btn btn-warning">Export</button>
            <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Add Link</button>
            <?php
            if (Yii::$app->user->can('create_cibr')) {

                echo Html::a($model->is_final === 1 ? 'Draft' : 'Final', ['final', 'id' => $model->id], ['class' => "btn $color"]);
            }
            $document_link = '';
            if (!empty($model->document_link)) {
                $document_link = $model->document_link;
                echo Html::a('Soft Copy Link', $document_link, ['class' => 'btn btn-info ']);;
            }
            ?>


        </div>

        <div class="col-sm-2">
            <div>


            </div>
        </div>

    </div>

    <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="add_link">
                    <div class='modal-body'>
                        <hr>
                        <label for="ledger"> Insert Link</label>

                        <input type="text " style="display: none;" class="form-control" name="id" value='<?= $model->id ?>'>

                        <input type="text " class="form-control" name="link" value='<?= $document_link ?>'>
                    </div>
                    <div class="row" style="margin: 10px;padding:12px">
                        <div class="col-sm-3">

                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="con">

        <?php Pjax::begin(['id' => 'cibr', 'clientOptions' => ['method' => 'POST']]) ?>
        <table>

            <thead>
                <tr>
                    <th colspan="12" style="text-align: center;border:1px solid white">CASH IN BANK REGISTER</th>
                </tr>
                <tr>
                    <th colspan="12" style="text-align: center;border:1px solid white">

                        <span>
                            For the month of <?php
                                                if (!empty($reporting_period)) {
                                                    echo date('F, Y', strtotime($reporting_period));
                                                }
                                                ?>
                        </span>
                    </th>
                </tr>
                <tr>
                    <th colspan="9" class="header">
                        <span> Entity Name:Department of Trade and Industry</span>
                    </th>
                    <th colspan="3" class="header">
                        <span>
                            Sheet No. :_____________________
                        </span>
                    </th>
                </tr>
                <tr>
                    <th colspan="9" class="header">
                        <span> Sub-Office/District/Division: Provincial Office</span>
                    </th>
                    <th colspan="3" class="header">
                        <span>
                            Name of Disbursing Officer: <?php

                                                        if (!empty($province)) {
                                                            // echo $prov[$province]['officer'];
                                                            $prov = Yii::$app->memem->cibrCdrHeader($province);
                                                            echo $prov['officer'];
                                                            // echo Yii::$app->memem->cibrCdrHeader($province)['officer'];


                                                        }
                                                        ?>
                        </span>
                    </th>
                </tr>
                <tr>
                    <th colspan="9" class="header">
                        <span> Municipality/City/Province: <?php

                                                            if (!empty($province)) {
                                                                // echo $prov[$province]['province'];
                                                                // echo Yii::$app->memem->cibrCdrHeader($province)['province'];
                                                                echo $prov['province'];
                                                            }
                                                            ?></span>
                    </th>
                    <th colspan="3" class="header">
                        <span>
                            Station:
                            <?php

                            if (!empty($province)) {
                                // echo $prov[$province]['province'];
                                // echo Yii::$app->memem->cibrCdrHeader($province)['province'];
                                echo $prov['province'];
                            }
                            ?>
                        </span>
                    </th>
                </tr>
                <tr>
                    <th colspan="9" rowspan="2" style="border-left:1px solid white;border-right:1px solid white;">
                        <span> Fund Cluster : <?php
                                                if (!empty($book)) {

                                                    echo $book;
                                                }
                                                ?></span>
                    </th>
                    <th colspan="3" class="header" style="border-left:1px solid white;border-right:1px solid white;">
                        <span>
                            Bank : Landbank of the Philippines
                        </span>
                    </th>
                </tr>
                <tr>
                    <th colspan="3" style="border-left:1px solid white;border-right:1px solid white;">
                        <span>
                            Location: <?php
                                        if (!empty($province)) {
                                            // echo Yii::$app->memem->cibrCdrHeader($province)['location'];
                                            echo $prov['location'];
                                        }
                                        ?>
                        </span>
                    </th>

                </tr>
                <tr>

                    <th class='head' rowspan="6">Date</th>
                    <th class='head' rowspan="6">Check No.</th>
                    <th class='head' rowspan="6">Particular</th>
                    <th class='head' rowspan="3" colspan="3">CASH IN BANK</th>
                </tr>

                <tr>
                    <th colspan="6" style="text-align: center;">BREAKDOWN </th>
                </tr>
                <tr>
                    <th class='head' colspan="2">PERSONNEL SERVICES </th>
                    <th class='head' colspan="1">MAINTENANCE AND OTHER OPERATING EXPENSES </th>
                    <th class='head' colspan="3">OTHERS</th>
                </tr>


                <tr>
                    <th class='head' rowspan="3">Deposits</th>
                    <th class='head' rowspan="3">Withdrawals</th>
                    <th class='head' rowspan="3"> Balances</th>
                </tr>
                <tr>
                    <th rowspan="1">Salaries and Wages-Casual</th>
                    <th rowspan="1">Salaries and Wages -Casual/ Contractual</th>
                    <!-- <th rowspan="1"> 3</th> -->
                    <th rowspan="1"> Office Supplies Expenses </th>
                    <!-- <th rowspan="1"> 5</th>
                <th rowspan="1"> 6</th> -->
                    <th class='head' rowspan="2"> Account Description</th>
                    <th class='head' rowspan="2">UACS Code</th>
                    <th class='head' rowspan="2">Amount</th>
                </tr>
                <tr>
                    <th rowspan="1">50101020</th>
                    <th rowspan="1">50101020</th>
                    <!-- <th rowspan="1"> 3</th> -->
                    <th rowspan="1"> 50201010</th>
                    <!-- <th rowspan="1"> 5</th>
                <th rowspan="1"> 6</th> -->
                </tr>
            </thead>
            <tbody>

                <?php
                $total_deposit = 0;
                $total_withdrawals = 0;
                $balance = !empty($beginning_balance) ? $beginning_balance : 0;
                $x = 0;
                if (!empty($dataProvider)) {
                    foreach ($dataProvider as $i => $data) {
                        $balance += (float)$data['amount'] - (float)$data['withdrawals'];
                        // if ($data['reporting_period'] === $reporting_period) {
                        if ($x === 0) {
                            echo "<tr>
                  
                            <td></td>
                            <td></td>
                            <td style='text-align:center'>Beginning Balance</td>
                            <td ></td>
                            <td ></td>
                            <td style='text-align:right'>" . number_format($balance - (float)$data['amount'] + (float)$data['withdrawals'], 2) . "</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td ></td>
                         </tr>";
                            $x++;
                        }
                        echo "<tr>
                  
                            <td>" . $data['check_date'] . "</td>
                            <td>" . $data['check_number'] . "</td>
                            <td >" . $data['particular'] . "</td>
                            <td style='text-align:right'>" . number_format((float)$data['amount'], 2) . "</td>
                            <td style='text-align:right'>" . number_format((float)$data['withdrawals'], 2) . "</td>
                            <td>" . number_format($balance, 2) . "</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>" . $data['gl_account_title'] . "</td>
                            <td>" . $data['gl_object_code'] . "</td>
                            <td style='text-align:right'>" . number_format((float)$data['withdrawals'], 2)  . "</td>
                         </tr>";
                        $total_deposit += floatval((float)$data['amount']);
                        $total_withdrawals += floatval((float)$data['withdrawals']);
                        // }
                    }

                    echo "<tr>
                <td></td>
                <td colspan='2' style='text-align:center;font-weight:bold'>Total</td>
                <td style='text-align:right;font-weight:bold'>" . number_format($total_deposit, 2) . "</td>
                <td style='text-align:right;font-weight:bold'>" . number_format($total_withdrawals, 2) . "</td>
                <td style='text-align:right;font-weight:bold'>" . number_format($balance, 2) . "</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
             
                <td style='text-align:right;font-weight:bold'>" . number_format($total_withdrawals, 2) . "</td>
                </tr>";
                    //     echo "<pre>";
                    //         var_dump($dataProvider);
                    //    echo" </pre>";
                }


                ?>

                <tr>
                    <td colspan="8" style="border-right:none;"> </td>
                    <td colspan="2" style="text-align: center;border-left:none;border-right:none">

                        <div class="foot" style="text-align: left;margin-top:2rem;">Certified Correct:</div>
                        <div class="foot" style="font-weight: bold; margin-top:2rem;text-transform:uppercase"><?php

                                                                                                                if (!empty($province)) {
                                                                                                                    echo $prov['officer'];
                                                                                                                }
                                                                                                                ?></div>
                        <div class="foot">Signature</div>
                        <div class="foot">Disbursing Officer</div>

                    </td>
                    <td colspan="2" style="border-left: none;"></td>
                </tr>

            </tbody>

        </table>
        <?php Pjax::end() ?>
    </div>


</div>
<style>
    #con {
        display: none;
    }

    table,
    th,
    td {
        border: 1px solid black;
        padding: 10px;
    }

    .header {
        border: 1px solid white
    }

    .head {
        text-align: center;
    }

    @media print {

        td,
        th {
            font-size: 10px;
            padding: 2px;
        }

        #filter {
            display: none;
        }

        .main-footer {
            display: none;
        }
    }
</style>




<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/jquery.dataTables.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    $('#add_link').submit((e) => {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=cibr/add-link',
            data: $("#add_link").serialize(),
            success: function(data) {
                $('#uploadmodal').modal('toggle');
                var res = JSON.parse(data)
                if (res.isSuccess) {
                    swal({
                        title: 'Success',
                        type: 'success',
                        button: false,
                        timer: 3000,
                    }, function() {
                        location.reload(true)
                    })
                } else {
                    swal({
                        title: "Error Adding Fail",
                        type: 'error',
                        button: false,
                        timer: 3000,
                    })
                }
            }
        })
    })
    $(document).ready(function() {
        $('#export').click(function(e) {
            e.preventDefault();
            $.ajax({

                type: 'POST',
                url: window.location.pathname + '?r=cibr/export',
                data: {
                    id: $('#model_id').val()
                },
                success: function(data) {
                    console.log(data)
                    var res = JSON.parse(data)
                    window.open(res)
                }

            })
        })
    })
</script>
<?php
SweetAlertAsset::register($this);
$script = <<< JS
        $("#final").click((e)=>{
            e.preventDefault();
            $.ajax({
                type:'POST',
                url:window.location.pathname +'?r=cibr/final',

            })
        })

        $(document).ready(function(){
      
            setTimeout(() => {

                $(".cibr_view").show()
                $("#con").show()
                $("#dots5").hide()
            }, 1000);
        })
JS;
$this->registerJs($script);
?>