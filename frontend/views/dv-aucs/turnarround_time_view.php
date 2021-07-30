<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DvAucs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dv-aucs-form">

    <?php

    // echo $since_start->days . ' days total<br>';
    // echo $since_start->y . ' years<br>';
    // echo $since_start->m . ' months<br>';
    // echo $since_start->d . ' days<br>';
    // echo $since_start->h . ' hours<br>';
    // echo $since_start->i . ' minutes<br>';
    // echo $since_start->s . ' seconds<br>';
    $q = explode('-', $model->return_timestamp);
    $begin_timestamp = $model->transaction_begin_time;
    $time_out = $model->out_timestamp;
    // echo $begin_timestamp;

    if (!empty($model->return_timestamp) && $q[0] > 0) {
        $begin_timestamp = $model->accept_timestamp;
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

    echo  date('H', strtotime($begin_timestamp)) . '<br>';
    echo date('H', strtotime($time_out)) . '<br>';
    if (
        date('H', strtotime($time_out)) > 12
        && date('H', strtotime($begin_timestamp)) < 12
    ) {
        $time_out = date('Y-m-d H:i:s', strtotime($time_out . "-1 hours"));
        echo  $model->out_timestamp . '<br>';
        echo $time_out . '<br>';
    } else if (
        $begin_date !== $out_date
        && date('H', strtotime($time_out)) > 12
    ) {
        $time_out = date('Y-m-d H:i:s', strtotime($time_out . "-1 hours"));
    }
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
    // echo $time_out. '<br>';
    // if (date('H', strtotime($time_out)) > 12) {
    //     $time_out = date(
    //         'Y-m-d H:i:s',
    //         strtotime($time_out . "-1 hours ")
    //     );
    //     echo $time_out. '<br>';
    // }

    echo $final_hrs;


    ?>
    <table>

        <thead>
            <tr>

                <th colspan="5">
                    <div class="row" style="float:right">
                        <?= Html::img(Yii::$app->request->baseUrl . '/frontend/web/dti3.png', ['alt' => 'some', 'class' => 'pull-left img-responsive', 'style' => 'width: 100px;height:100px;margin-left:auto']); ?>
                    </div>
                </th>
            </tr>
            <tr>
                <th colspan="3">payee</th>
            </tr>
            <tr>
                <th colspan="3">Gross Amount:</th>
            </tr>
            <tr>
                <th colspan="3">Net Amount</th>
            </tr>

        </thead>
        <tbody>

            <tr>
                <td> </td>
                <td>DATE</td>
                <td>TIME-IN</td>
                <td>TIME-OUT</td>
                <td>REMARKS</td>

            </tr>
            <tr>
                <td colspan="5" style="text-align: center;">Vouchers at Accounting Unit </td>

            </tr>
            <tr>
                <td>Accounting Staff</td>
                <td><?php ?></td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
            </tr>
            <tr>
                <td>Budget Officer</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
            </tr>
            <tr>
                <td>Accountant II</td>
                <td>
                </td>
                <td>

                    <?php
                    echo $begin_time;
                    ?>
                </td>
                <td>1</td>
                <td>1</td>
            </tr>
            <tr>
                <td>Chief Accountant</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
            </tr>
            <tr>
                <td colspan="5">Note: Accounting Staff, then ,encodes to the web-based system the time-in and time-out from Accountants II and II,
                    before forwarding the DV's for RD's Signature. The "Process DV" Module shall be used for This
                </td>

            </tr>
            <tr>
                <td>Cashier</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
            </tr>
            <tr>
                <td>Cashier</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
            </tr>
            <tr>
                <td colspan="5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Minus dolorum laborum dolor reiciendis, nesciunt at consequuntur placeat eius nihil quam? Ullam, odio est. Ipsum, placeat totam? Sunt exercitationem odit praesentium.</td>

            </tr>
            <tr>
                <td colspan="5" style="margin-left:auto;">TURN AROUND TIME :</td>

            </tr>

        </tbody>
    </table>
    <?php


    ?>
</div>

<style>
    table {
        width: 100%;
    }

    table,
    td,
    tr {
        border: 1px solid black;
        padding: 12px;
    }
</style>