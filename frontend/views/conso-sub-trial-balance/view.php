<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Conso Sub Trial Balances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="index">

    <?php

    $fund = Yii::$app->db->createCommand("SELECT fund_cluster_code.id,fund_cluster_code.name FROM fund_cluster_code")->queryAll();
    $books = Yii::$app->db->createCommand("SELECT books.id,books.name FROM books")->queryAll();

    ?>


    <p>

        <?= Yii::$app->user->can('update_ro_conso_sub_trial_balance') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate btn btn-primary']) : '' ?>
        <button id="export" type='button' class="btn-xs btn-success" style="margin:1rem;"><i class="glyphicon glyphicon-export"></i></button>
    </p>
    <div class="container card">

        <table id="data_table">
            <thead>
                <tr class="header" style="border: none;">

                    <td colspan="5">


                        <div style="width: 100%; display:flex;align-items:center; justify-content: center;">
                            <div style="margin-right: 20px;left:-10px">
                                <?= Html::img('frontend/web/dti.jpg', ['alt' => 'some', 'class' => 'pull-left img-responsive', 'style' => 'width: 100px;height:100px;']); ?>
                            </div>
                            <div style="text-align:center;" class="headerItems">
                                <h6>DEPARTMENT OF TRADE AND INDUSTRY</h6>
                                <h6>CARAGA REGIONAL OFFICE</h6>
                                <h6>TRIAL BALANCE
                                    <?php if (!empty($fund_cluster_code)) {
                                        echo strtoupper($fund_cluster_code);
                                    } ?>
                                </h6>
                                <h6>As of <span id="month"></span>

                            </div>

                        </div>

                    </td>


                </tr>
                <tr class="header" style="border: none;">
                    <td colspan="3" style="border: none;">
                        <span>
                            Entity Name:
                        </span>
                        <span>
                            DEPARTMENT OF TRADE AND INDUSTRY - CARAGA
                        </span>
                    </td>

                    <td colspan="3" style="border: none;">
                        <span>
                            Fund Cluster:
                        </span>
                        <span id="book_name"></span>
                    </td>


                </tr>


                <tr style="border-top:1px solid black">


                    <td style="border-top:1px solid black">
                        Account Name
                    </td>
                    <td style="border-top:1px solid black">
                        Code
                    </td>

                    <td style="border-top:1px solid black">
                        Debit
                    </td>
                    <td style="border-top:1px solid black">
                        Credit
                    </td>

                </tr>
            </thead>
            <tbody></tbody>
        </table>


    </div>
    <div id="dots5">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <style>
        #reporting_period {
            background-color: white;
            border-radius: 3px;
        }

        .container {
            display: none;
        }

        .headerItems>h6 {
            font-weight: bold;
        }

        .amount {
            text-align: right;
        }


        .table {
            position: relative;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 12px;
            background-color: white;
        }


        table {
            border: 1px solid black;
            width: 100%;
        }

        .container {
            margin-top: 5px;
            position: relative;
            padding: 10px;

        }

        thead>tr>td {
            border: 1px solid black;
            padding: 10px;
            font-weight: bold;
        }

        #fund {
            display: none;
        }

        .actions {
            padding: 20px;
            position: relative;
        }

        @media print {
            .actions {
                display: none;
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

            table,
            th,
            td {
                border: 1px solid black;
                padding: 5px;
                background-color: white;
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

        #bars1 {
            display: none;
        }

        .table {
            display: none;
        }
    </style>

</div>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/thousands_separator.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/consoSubTrialBalanceJs.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css");
$csrfToken = Yii::$app->request->csrfToken;
?>
<script>
    $(document).ready(function() {
        const reporting_period = "<?php echo $model->reporting_period ?>";
        const book_type = "<?php echo $model->book_type ?>";
        setTimeout(() => {
            query('<?= $csrfToken ?>', reporting_period, book_type)
        }, 500);
        $('#export').click(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=conso-sub-trial-balance/export',
                data: {
                    reporting_period: reporting_period,
                    book_type: book_type
                },
                success: function(data) {
                    var res = JSON.parse(data)
                    window.open(res)
                }

            })
        })
    })
</script>