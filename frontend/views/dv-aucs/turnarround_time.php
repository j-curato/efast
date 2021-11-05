<?php

use app\models\DvAucsEntriesSearch;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\time\TimePicker;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DvAucsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Turn Arround Time';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dv-aucs-index">




    <?php
    date_default_timezone_set('Asia/Manila');
    $time = date('h:i:s A');
    $date = date('Y-M-d');
    Modal::begin(
        [
            //'header' => '<h2>Create New Region</h2>',
            'id' => 'qwe',
            'size' => 'modal-md',
            'clientOptions' => ['backdrop' => 'static', 'keyboard' => TRUE, 'class' => 'modal modal-primary '],
            'options' => [
                'tabindex' => false // important for Select2 to work properly
            ],
        ]
    );
    echo "<div class='box box-success' id='modalContent'></div>";
    echo "<form id='timestampForm'>";
    echo "<a id='link' href=''></a>";
    echo "<div class='row'>";
    echo "<div class='col-sm-6'>";
    echo "<label for='time'> Time</label>";
    echo TimePicker::widget([
        'name' => 'time',
        'id' => 'time',
        'value' => $time
    ]);
    echo "</div >";
    echo "<div class='col-sm-6'>";
    echo "<label for='date' style='text-align:center'>Date</label>";
    echo DatePicker::widget([
        'name' => 'date',
        'id' => 'date',
        'value' => $date,
        'options' => [
            'readOnly' => true,
        ],
        'pluginOptions' => [
            'format' => 'yyyy-M-dd',
            'autoclose' => true
        ]
    ]);
    echo "</div >";
    echo "</div>";

    echo "<button type='submit' class='btn btn-success' id='save'>Save</button>";
    echo '<form>';
    Modal::end();
    $exportSearchModel = new DvAucsEntriesSearch();
    $exportDataProvider = $exportSearchModel->search(Yii::$app->request->queryParams);
    $dv_time = '';
    function q($q)
    {
        $dv_time = $q;
        return $dv_time;
    }
    $exportColumns = [
        [
            'label' => 'Reporting Period',
            'value' => 'dvAucs.reporting_period'
        ],

        [
            'label' => 'Payee',
            'value' => 'dvAucs.payee.account_name'
        ],
        [
            'label' => 'Particular',
            'value' => 'dvAucs.particular'
        ],


        [
            'label' => 'Gross Amount',
            'value' => function ($model) {
                $query  = Yii::$app->db->createCommand("SELECT SUM(process_ors_entries.amount) as total_disbursed 
                FROM process_ors_entries WHERE process_ors_id = :ors_id")
                    ->bindValue(':ors_id', $model->process_ors_id)
                    ->queryScalar();
                return $query;
            }

        ],
        [
            'label' => 'Net Disbursement',
            'value' => 'amount_disbursed'
            // function ($model) {
            //     $query  = Yii::$app->db->createCommand("SELECT SUM(dv_aucs_entries.amount_disbursed) as total_disbursed 
            //     FROM dv_aucs_entries WHERE dv_aucs_id = :dv_id")
            //         ->bindValue(':dv_id', $model->dvAucs->id)
            //         ->queryScalar();
            //     return $query;
            // }
        ],
        [
            'label' => "DV Number",
            'value' => "dvAucs.dv_number"
        ],
        [
            'label' => "ORS Number",
            'value' => "processOrs.serial_number"
        ],


        [
            'label' => 'DV Begin Time',
            'value' => 'dvAucs.transaction_begin_time',
        ],

        [
            'label' => 'DV Return Time',
            'value' => 'dvAucs.return_timestamp',
        ],
        [
            'label' => 'DV Accept Time',
            'value' => 'dvAucs.accept_timestamp',
        ],
        [
            'label' => 'DV Out Time',
            'value' => 'dvAucs.out_timestamp',
        ],
        [
            'label' => 'DV  Calculated',
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




                return $final_hrs;
            }
        ],
        [
            'label' => 'Cash Disbursement IN',
            'value' => 'dvAucs.cashDisbursement.begin_time'
        ],
        [
            'label' => 'Cash Disbursement OUT',
            'value' => 'dvAucs.cashDisbursement.out_time'
        ],
        [
            'label' => 'Cash Time Diff',
            'value' => function ($model) {

                $in_time = !empty($model->dvAucs->cashDisbursement->begin_time) ? $model->dvAucs->cashDisbursement->begin_time : '';
                $out_time = !empty($model->dvAucs->cashDisbursement->out_time) ? $model->dvAucs->cashDisbursement->out_time : '';

                if (!empty($in_time) && !empty($out_time)) {
                    $in = new DateTime($in_time); //start time
                    $out = new DateTime($out_time); //start time
                    $interval = $in->diff($out);
                    return  $interval->format('%H:%i:%s');
                }


                return '';
            }
        ],
        [
            'label' => 'Total',
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


                $in_time = !empty($model->dvAucs->cashDisbursement->begin_time) ? $model->dvAucs->cashDisbursement->begin_time : '';
                $out_time = !empty($model->dvAucs->cashDisbursement->out_time) ? $model->dvAucs->cashDisbursement->out_time : '';
                $cash_diff = '00:00:00';
                if (!empty($in_time) && !empty($out_time)) {
                    $in = new DateTime($in_time); //start time
                    $out = new DateTime($out_time); //start time
                    $interval = $in->diff($out);
                    $cash_diff = $interval->format('%H:%i:%s');
                }

                $q = strtotime($final_hrs) + strtotime($cash_diff) - strtotime('00:00:00');
                $time1 = '15:20:00';
                $time2 = '00:30:00';
                $time = strtotime($final_hrs) + strtotime($cash_diff) - strtotime('00:00:00');
                return $time = date('H:i:s', $time);
                return date('h:i:s', $q);
            }
        ]


    ];
    $columns =
        [
            // ['class' => 'yii\grid\SerialColumn'],

            'id',
            // 'process_ors_id',
            'dv_number',
            [
                'label' => 'Payee',
                'attribute' => 'payee_id',
                'value' => 'payee.account_name'
            ],
            [
                'label' => 'In Timestamp',
                'attribute' => 'in_timestamp'
            ],
            'out_timestamp',
            [
                'label'=>'Cash Disbursement IN',
                'value'=>'cashDisbursement.begin_time'
            ],
            [
                'label'=>'Cash Disbursement OUT',
                'value'=>'cashDisbursement.out_time'
            ],
            [
                'label' => 'action',
                'format' => 'raw',
                'value' => function ($model) {
                    return ' '
                        // . Html::a('View', ['turnarround-view', 'id' => $model->id], ['class' => 'btn-sm btn-primary'])
                        . Html::button(
                            'IN',
                            [
                                'value' => '/?r=dv-aucs/in&id=' . $model->id,
                                'class' => 'btn-sm btn-success turn-arround-btn',
                                'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector'
                            ]
                        )
                        . Html::button(
                            'Out',
                            [
                                'value' => Url::to('?r=dv-aucs/out&id=' . $model->id),
                                'class' => 'btn-sm btn-warning  turn-arround-btn',
                                'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector'
                                
                            ]
                        );
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
        // 'pjax' => true,
        'toolbar' => [
            [
                'content' => ExportMenu::widget([
                    'dataProvider' => $exportDataProvider,
                    'columns' => $exportColumns,
                    'filename' => "Turn Around Time",
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



</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 5rem;
        padding: 0;
    }

    .turn-arround-btn {
        margin: 1px;
    }
</style>
<?php
SweetAlertAsset::register($this);
$script = <<<JS
    $('.turn-arround-btn').click(function() {    
        $('#qwe').modal('show').find('#link').attr('href',$(this).attr('value'))
    });
    $("#timestampForm").submit(function(e){
        e.preventDefault();
        var link =$('#link').attr('href')
        console.log(link)
        $.ajax({
            type:'POST',
            url:window.location.pathname + link,
            data:$('#timestampForm').serialize(),
            success:function(data){
                console.log(data)
                var res = JSON.parse(data)
                if (res.success){
                    location.reload()
                }
                else{
                    swal({
                        type:'error',
                        button:false,
                        time:3000,
                        title:res.error
                    })
                }
            }
        })
    })
JS;
$this->registerJs($script);
?>