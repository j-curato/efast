<?php

use app\models\FundClusterCode;
use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\JevPreparation */


?>
<div class="jev-preparation-view">






    <div class="container ">

        <table>

            <thead>
                <tr class="head">

                    <th rowspan="2" colspan="2">
                        JOURNAL ENTRY VOUCHER
                    </th>

                    <th rowspan="1" colspan="3">
                        JEV #: ADADJ-01-2021-01-0011
                    </th>

                </tr>
                <tr>
                    <th rowspan="1" colspan="4">
                        Date: 2021-03-01
                        Reporting Period: 2021-01
                    </th>

                </tr>
                <tr>
                    <th rowspan="3">
                        Responsibility Center
                    </th>
                    <th rowspan="1" colspan="4">
                        ACCOUNTING ENTRIES
                    </th>
                </tr>
                <tr>
                    <th rowspan="2">
                        Accounts and Explanation
                    </th>
                    <th rowspan="2">
                        UACS Object Code
                    </th>
                    <th colspan="2">
                        Amount
                    </th>

                </tr>
                <tr>
                    <th>
                        Debit
                    </th>
                    <th>
                        Credit
                    </th>
                </tr>

            </thead>
            <tbody>
                <tr>
                    <td>qqweqw </td>
                    <td>qqweqw </td>
                    <td>qqweqw </td>
                    <td>qqweqw </td>
                    <td>qqweqw </td>

                </tr>

                <tr>
                    <td colspan="2">

                    qwe
                    </td>
                    <td colspan="3">

                    qweqw
                    </td>

                </tr>
            </tbody>
            </th>
        </table>
    </div>
    <style>
        .container {
            /* border: 1px solid black; */
            height: auto;
            background-color: white;
            /* box-shadow: 12px; */
            border-radius: 5px;
            padding: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        .row-2 {
            text-align: center;
            height: auto;
            border: none;
        }

        .date {
            display: flex;
            /* grid-template-columns: 1fr 1fr; */
            /* grid-row: 1fr; */
            position: relative;
            height: 50%;
            padding: 0;
            margin: 0;

        }

        .date>div {
            width: 100%;
            height: 100%;
            padding: 2px;
            text-align: center;
            margin-top: auto;
            margin-bottom: auto;

            ;
        }

        .date>div>span {
            margin: 2px;
        }

        .h-debit-credit {
            display: grid;
            grid-template-columns: 1fr 1fr;
            text-align: center;
            height: 100%;

        }



        .acc-exp-row {
            display: flex;
            width: 100%;
            height: 100%;

        }

        .acc-exp-row>div {
            width: 100px;
            /* border: 1px solid black; */
        }

        .row-2 {
            display: flex;
            width: 100%;

        }

        .row-2>div {
            border: 1px solid black;

            width: 20%;
        }

        .row-1 {
            display: grid;
            height: 100px;
            width: 100%;
            padding: 0;
            margin: 0;
            grid-template-columns: 1fr 1fr;
        }

        .row-1>div {
            width: 100%;
            padding: 0;
            margin: 0;
            border: 1px solid black;
            font-weight: bold;
        }

        h5 {
            font-weight: bold;
        }

        @media print {
            .actions {
                display: none;
            }

            h5 {
                font-size: 12px;
            }

            span {
                font-size: 12px;
            }

            /* 
            .form-wrapper {
                margin-top: 20px;
                background-color: red;
            } */

            .print {
                display: none;
            }

            /* @page {
                margin-top: 20cm;
                margin-bottom: 5cm;
            } */



        }
    </style>
</div>

<?php
SweetAlertAsset::register($this);
$script = <<< JS
    

            $(".delete").click(function(e){
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover thi data!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Delete',
                    cancelButtonText: "Cancel",
                    closeOnConfirm: true,
                    closeOnCancel: true,
                    timer: 2000,
                },
                function(isConfirm){

                if (isConfirm){
                    swal("Shortlisted!", "Candidates are successfully shortlisted!", "success");
                    
                    var x= $('.delete').val()
                    $.ajax({
                        type: "POST",
                        url: x,
                        // data: data,
                        // success: success,
                        // dataType: dataType
                    });
                    } 
                else {
                    // swal("Cancelled", "Your imaginary file is safe :)", "error");
                        // e.preventDefault();
                    }
                });
            })
    JS;
$this->registerJs($script);
?>