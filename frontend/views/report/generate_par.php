<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php

use app\models\AdvancesEntries;
use app\models\Books;
use app\models\DvAucs;
use app\models\MajorAccounts;
use app\models\Office;
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

$this->title = "FUR";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index " style="background-color: white;padding:20px">



    <form id="filter">
        <div class="row">
            <div class="col-sm-2">
                <label for="province">Province</label>
                <?php
                echo Select2::widget([
                    'name' => 'office_id',
                    'id' => 'office_id',
                    'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                    'pluginOptions' => [
                        'autoclose' => true,
                        'placeholder' => 'Select Province'
                    ]
                ])

                ?>
            </div>
            <div class="col-sm-3" style="margin-top: 2.5rem;">
                <button class="btn btn-success" id="generate">Generate</button>
            </div>

        </div>

    </form>
    <div class="container" id="qq">

    </div>
</div>
<style>
    .container {
        background-color: white;
        padding: 3rem;
    }

    .amount {
        text-align: right;
    }

    .center {
        text-align: center;
    }

    .no-border {
        border: 0;
    }

    .cut_line {
        max-width: 100%;
        position: relative;
        padding: 1px;
        border: 1px solid black;
        float: left;
        margin-bottom: 15px;
    }

    .center {
        text-align: center;
    }

    table,
    th,
    td {
        padding: 12px;
        border: 1px solid black;
    }


    #sticker_table td {
        text-transform: uppercase;
        padding: 5px;

    }

    #sticker_table {
        margin: 20px;
        border: 0;
    }


    .qr {
        margin-left: auto;
        width: 50px;
    }


    @media print {

        table,
        th,
        td {
            padding: 5px;
            font-size: 10px;
        }

        table {
            margin: 10px;
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

        .page-break {
            page-break-after: always;
        }

    }
</style>


<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile("@web/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    function display(data) {
        $('#qq').html('')
        $.each(data, (key, val) => {
            // Parse the date string into a Date object
            var date = new Date(val.date);
            // Get the month name in full text format (e.g. "January", "February", etc.)
            var monthName = date.toLocaleString('en-US', {
                month: 'long'
            });
            // Get the day of the month with leading zero (e.g. "01", "02", etc.)
            var day = ('0' + date.getDate()).slice(-2);
            // Get the year (e.g. "2009")
            var year = date.getFullYear();
            // Concatenate the parts into the desired format
            var dateIssued = monthName + ' ' + day + ', ' + year;

            let tbl = `<br><table class="par_form" style="width: 100%;">
            <tbody>
                <tr>
                    <th colspan="6" class="center">
                        <br>
                        PROPERTY ACKNOWLEDGMENT RECEIPT
                        <br>
                    </th>
                </tr>
                <tr>
                    <th colspan="6">
                        <span>
                            Entity Name:
                        </span>
                        <span>Department of Trade and Industry - Caraga</span>

                    </th>

                </tr>
                <tr>
                    <th colspan="3">
                        <span>Fund Cluster:</span>
                        <span></span>
                    </th>
                    <th colspan="3">
                        <span>PAR No:</span>
                        <span>${val.par_number}</span>
                    </th>
                </tr>
                <tr>
                    <th class="center">Quantity</th>
                    <th class="center">Unit</th>
                    <th class="center">Description</th>
                    <th class="center">Property Number</th>
                    <th class="center">Date Acquired</th>
                    <th class="center">Amount</th>
                </tr>

                <tr>
                    <td class="center">1</td>
                    <td class="center">${val.unit_of_measure}</td>
                    <td style='max-width:300px'>
                        <span style='font-weight:bold;'>${val.article}</span>
                        <br>
                        <span style='font-style:italic;'>${val.description}</span>

                    </td>
                    <td class="center">${val.property_number}</td>
                    <td class="center">${dateIssued}</td>
                    <td class='amount'>${thousands_separators(val.acquisition_amount)}</td>
                </tr>

                <tr>
                    <th ><br></th>
                    <th ></th>
                    <th ></th>
                    <th > </th>
                    <th > </th>
                    <th ></th>
                </tr>
                <tr>
                    <th ><br></th>
                    <th ></th>
                    <th ></th>
                    <th > </th>
                    <th > </th>
                    <th ></th>
                </tr>
                <tr>
                    <th ><br></th>
                    <th ></th>
                    <th ></th>
                    <th > </th>
                    <th > </th>
                    <th ></th>
                </tr>
                <tr>
                    <th ><br></th>
                    <th ></th>
                    <th ></th>
                    <th > </th>
                    <th > </th>
                    <th ></th>
                </tr>
                <tr>
                    <th ><br></th>
                    <th ></th>
                    <th ></th>
                    <th > </th>
                    <th > </th>
                    <th ></th>
                </tr>
                <tr>
                    <th ><br></th>
                    <th ></th>
                    <th ></th>
                    <th > </th>
                    <th > </th>
                    <th ></th>
                </tr>

                <tr>
                    <th class='foot no-border' colspan="3">Received By</th>
                    <th class='foot no-border' colspan="3">Issued By</th>
                </tr>
                <tr>
                    <td class='foot no-border center' colspan="3">
                        <span style="text-decoration:underline">
                            <b>${val.rcv_by_nme}</b>
                        </span>
                        <br>

                        <span> Signatue over Printed Name of End User</span>
                    </td>
                    <td class='foot no-border center' colspan="3">
                        <span style="text-decoration:underline">
                            <b>${val.isd_by_nme}</b>

                        </span>
                        <br>
                        <span> Signatue over Printed Name of Supply and/or </span>
                        <br>
                        <span>Property Custodian</span>
                    </td>

                </tr>
                <tr>
                    <td class='foot no-border center' colspan="3">
                        <span style="text-decoration: underline;">
                            ${val.rcv_by_pos}
                        </span>
                        <br>
                        <span>Position</span>
                    </td>
                    <td class='foot no-border center' colspan="3">

                        <span style="text-decoration: underline;">
                           ${val.isd_by_pos}</span>
                        <br>
                        <span>Position</span>
                    </td>

                </tr>
                <tr>
                    <td class='foot no-border center' colspan="3" style="border-bottom: 1px solid black;">

                        <span>_______________</span>
                        <br>
                        <span>Date</span>
                    </td>
                    <td class='foot no-border center' colspan="3" style="border-bottom: 1px solid black;">
                        <span>_______________</span>
                        <br>
                        <span>Date</span>
                    </td>

                </tr>`;



            val.act_usr_nme ? tbl += ` <tr>
                    <td class='foot no-border' colspan='3' style='text-align:center;padding-top:5rem;border-bottom: 1px solid black;'>
                        <span style='text-decoration:underline'>
                            <b> <span>${val.act_usr_nme}</span></b>
                        </span>
                        <br>
                        <span> Signatue over Printed Name of Actual User</span>
                        <br>
                        <br>
                        <br>
                    </td>
                    <th class='foot no-border' colspan='3' style='text-align:center;padding-top:5rem;border-bottom: 1px solid black;'>

                    </th>
                </tr>` : '';
            tbl += `</tbody>
            </table>
        <br>
        <div class="page-break"></div>`
            $('#qq').append(tbl)

        })
    }
    $(document).ready(() => {
        $('#filter').submit((e) => {
            e.preventDefault()
            $.ajax({
                type: 'POST',
                url: window.location.href,
                data: $("#filter").serialize(),
                success: (data) => {
                    const res = JSON.parse(data)
                    display(res)
                }
            })
        })
    })
</script>