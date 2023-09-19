<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AllotmentModificationAdvice */

$this->params['breadcrumbs'][] = ['label' => 'TA Blank', 'url' => ['index']];
\yii\web\YiiAsset::register($this);

?>
<div class="maf-view" id="main">



    <div class="container" style="background-color:white;padding:20px" v-for="(item,idx) in items">
        <table style="margin-top: 10px; margin-bottom:10px">
            <tbody>
                <tr>
                    <th colspan="4" class="ctr">
                        <h4 class="bold">TECHNICAL ASSISTANCE</h4>
                    </th>
                </tr>
                <tr>
                    <th>Office:</th>
                    <td style="width: 250px;"></td>
                    <th>Serial No.:</th>
                    <td style="width: 250px;">{{item.serial_number}}</td>
                </tr>
                <tr>

                    <th>Date Requested:</th>
                    <td></td>
                    <th>Date Accomplish:</th>
                    <td></td>
                </tr>

                <tr>
                    <th colspan="2" class="ctr"> DESCRIPTION </th>
                    <th colspan="2" class="ctr">ACTION TAKEN</th>
                </tr>
                <tr>
                    <td colspan="2" style="height: 2%;width:50%"></td>
                    <td colspan="2" style="height: 15rem;width:50%"></td>
                </tr>
                <tr>
                    <th colspan="4" class='ctr'>Acceptance</th>

                </tr>
                <tr>
                    <th class="no-bdr" colspan="2">Requested By</th>
                    <th class="no-bdr" colspan="2">Actioned By</th>
                </tr>
                <tr>
                    <td colspan="2" class="ctr" style="border-top:0 ; border-right:0;">
                        <br>
                        <b><u></u></b>
                        <br>
                        <span></span>
                        <br>
                        <br>


                    </td>
                    <td colspan="2" class="ctr" style="border-top:0 ;border-left:0;">
                        <br>
                        <b><u>{{item.employee_name}}</u></b>
                        <br>
                        <span>{{item.position}}</span>
                        <br>
                        <br>
                    </td>
                </tr>

            </tbody>
        </table>
        <div class="page-break" v-if="isEven(idx+1)"></div>
    </div>

</div>
<style>
    .ctr {
        text-align: center;
    }

    .no-bdr {
        border: 0;
    }

    .border-left {
        border-left: 1px solid black;
    }

    .container {
        background-color: white;
        padding: 3rem;
    }

    table {
        width: 100%;
        border: 1px solid black;

    }


    th,
    td {
        border: 1px solid black;
        padding: 1rem;
    }

    @media print {
        .main-footer {
            display: none;

        }

        .btn {
            display: none;
        }

        th,
        td {
            border: 1px solid black;
            padding: .5rem;
            font-size: 12px;
        }

        .page-break {
            page-break-before: always;
        }
    }
</style>
<script>
    $(document).ready(function() {

        new Vue({
            el: '#main',
            data: {
                items: <?= json_encode($items) ?>,

            },
            computed: {

            },
            methods: {
                formatAmount(amount) {
                    amount = parseFloat(amount)
                    if (typeof amount === 'number' && !isNaN(amount)) {
                        return amount.toLocaleString()
                    }
                    return 0;
                },
                isEven(num) {
                    console.log(num)
                    return num % 2 === 0

                }

            }
        })
    })
</script>