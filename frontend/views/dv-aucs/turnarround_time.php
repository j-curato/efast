<?php

use app\models\DvAucsEntriesSearch;
use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DvAucsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dv Aucs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dv-aucs-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Dv Aucs', ['create'], ['class' => 'btn btn-success']) ?>
        <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Import</button>
    </p>

    <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">UPLOAD WFP</h4>
                </div>
                <div class='modal-body'>
                    <center><a href="/afms/frontend/web/import_formats/DV_Format.xlsx">Download Template Here to avoid error during Upload.</a></center>
                    <hr>
                    <label for="ledger"> SELECT GENERAL LEDGER</label>
                    <?php
                    $ledger = Yii::$app->db->createCommand("SELECT chart_of_accounts.id, CONCAT(chart_of_accounts.uacs,' - ',chart_of_accounts.general_ledger) as name FROM chart_of_accounts")->queryAll();
                    ?>
                    <?php

                    $form = ActiveForm::begin([
                        'action' => ['dv-aucs/import'],
                        'method' => 'POST',
                        'id' => 'import',
                        'options' => [
                            'enctype' => 'multipart/form-data',
                        ], // important
                    ]);

                    // echo '<input type="file">';
                    echo "<br>";
                    echo FileInput::widget([
                        'name' => 'file',
                        // 'options' => ['multiple' => true],
                        'id' => 'fileupload',
                        'pluginOptions' => [
                            'showPreview' => true,
                            'showCaption' => true,
                            'showRemove' => true,
                            'showUpload' => true,
                        ]
                    ]);
                    ActiveForm::end();
                    ?>

                </div>
            </div>
        </div>
    </div>
    <?php
    $exportSearchModel = new DvAucsEntriesSearch();
    $exportDataProvider = $exportSearchModel->search(Yii::$app->request->queryParams);
    $exportColumns = [
        [
            'label' => "DV Number",
            'value' => "dvAucs.dv_number"
        ],
        [
            'label' => "Reporting Period",
            'value' => "dvAucs.reporting_period"
        ],
        [
            'label' => "ORS Number",
            'value' => "processOrs.serial_number"
        ],

        'dvAucs.created_at',
        'dvAucs.transaction_begin_time',
        'dvAucs.return_timestamp',
        'dvAucs.accept_timestamp',
        'dvAucs.out_timestamp',
        'processOrs.created_at',
        'processOrs.transaction.created_at',
        [
            'label' => 'calculated',
            'value' => function ($model) {
                $year = explode('-', $model->dvAucs->return_timestamp);
                $begin_timestamp = $model->dvAucs->transaction_begin_time;
                $time_out = $model->dvAucs->out_timestamp;

                // echo $begin_timestamp;
                if (empty($time_out) && explode('-', $time_out)[0] <= 0) {
                    return '';
                }
                if (!empty($model->dvAucs->return_timestamp) && $year[0] > 0) {
                    $begin_timestamp = $model->dvAucs->accept_timestamp;
                }


                $begin_date = date('Y-m-d', strtotime($begin_timestamp));
                $begin_time = date('H:i:s', strtotime($begin_timestamp));
                $out_date = date('Y-m-d', strtotime($time_out));
                $out_time = date('H:i:s', strtotime($time_out));
                $hrs = 0;
                $mnt = 0;
                $sec = 0;
                $final_hrs = 0;
                $final_mnts = 0;
                if ($begin_date !== $out_date) {


                    $start_date = new DateTime($begin_timestamp);
                    $since_start = $start_date->diff(new DateTime($begin_date . '17:00:00'));

                    if (strtotime($begin_time) > strtotime('17:00:00')) {
                        $hrs = 0;
                        $mnt = 30;
                        $sec = 0;
                    } else {
                        $hrs = $since_start->h;
                        $mnt = $since_start->i;
                        $sec = $since_start->s;
                    }

                    $end_date = new DateTime(date('Y-m-d H:i:s', strtotime($out_date . ' 08:00:00')));
                    $total_end_date = $end_date->diff(new DateTIme(date(
                        'Y-m-d H:i:s',
                        strtotime($time_out . "+{$hrs} hours +{$mnt} minutes +$sec seconds")
                    )));
                    $final_hrs = $total_end_date->format('%H:%I:%S');
                    // $final_mnts = $total_end_date->i;
                } else {

                    $q = new DateTime($begin_timestamp);
                    $x = $q->diff(new DateTime($time_out));
                    $final_hrs = $x->format('%H:%I:%S');
                    // $final_mnts = $q->i;
                }



                // echo date_diff(date('Y-m-d H:i:s',strtotime($out_date .' 08:00:00')),$time_out);

                // echo 'qweqwe';
                // echo $out_date .' 08:00:00' . ' end date begin<br>';
                // echo $begin_timestamp . ' begin_timestamp<br>';
                // echo $time_out . ' timeout<br>';
                // echo $since_start->h . ' hours<br>';
                // echo $since_start->i . ' minutes<br>';
                // echo $since_start->s . ' seconds<br>';

                // echo $since_start->d . ' days<br>';
                // echo $total_end_date->h . ' end hours<br>';
                // echo $total_end_date->i . ' end minutes<br>';
                // echo $total_end_date->s . ' end sec<br>';

                // // $total_end_date->i +20;
                // echo $total_end_date->format('%H:%I:%S');
                return $final_hrs;
            }
        ]

    ];
    $columns =
        [
            // ['class' => 'yii\grid\SerialColumn'],

            'id',
            // 'process_ors_id',
            // 'raoud_id',
            'dv_number',
            'created_at',
            'transaction_begin_time',
            'return_timestamp',
            'accept_timestamp',
            'out_timestamp',
            [
                'label' => 'action',
                'format' => 'raw',
                'value' => function ($model) {

                    $t = yii::$app->request->baseUrl . "/index.php?r=advances/update&id=$model->id";
                    $r = yii::$app->request->baseUrl . "/index.php?r=advances/view&id=$model->id";

                    return ' '
                        . Html::a('View', ['turnarround-view', 'id' => $model->id], ['class' => 'btn-sm btn-primary'])
                        . Html::a('Return', ['return', 'id' => $model->id], [
                            'class' => 'btn-sm btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to return this item?',
                                'method' => 'post',
                            ],
                        ])
                        . Html::a('Accept', ['accept', 'id' => $model->id], [
                            'class' => 'btn-sm btn-success',
                            'data' => [
                                'confirm' => 'Are you sure you want to accept this item?',
                                'method' => 'post',
                            ],
                        ])
                        . Html::a('Out', ['out', 'id' => $model->id], [
                            'class' => 'btn-sm btn-warning',
                            'data' => [
                                'confirm' => 'Are you sure you want to out this item?',
                                'method' => 'post',
                            ],
                        ]);
                }
            ],
        ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of DV',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'pjax' => true,
        'toolbar' => [
            [
                'content' => ExportMenu::widget([
                    'dataProvider' => $exportDataProvider,
                    'columns' => $exportColumns,
                    'filename' => "DV",
                    'exportConfig' => [
                        ExportMenu::FORMAT_CSV => false,
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_HTML => false,
                        ExportMenu::FORMAT_EXCEL => false,

                    ]
                ]),
                'options' => [
                    'class' => 'btn-group mr-2', 'style' => 'margin-right:20px'
                ]
            ]
        ],
        'pjax' => true,
        'export' => false,
        'columns' => $columns
    ]); ?>


    <style>
        .grid-view td {
            white-space: normal;
            width: 5rem;
            padding: 0;
        }
    </style>
</div>