<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TripTicket */

$this->title = $model->serial_no;
$this->params['breadcrumbs'][] = ['label' => 'Trip Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);



$driver = '';

if (!empty($model->driver)) {

    $driver = YIi::$app->db->createCommand("SELECT employee_name FROM employee_search_view WHERE employee_id = :employee_id ")
        ->bindValue(':employee_id', $model->driver)
        ->queryScalar();
}
$authorized_by = '';
if (!empty($model->authorized_by)) {


    $authorized_by = YIi::$app->db->createCommand("SELECT employee_name,position FROM employee_search_view WHERE employee_id = :employee_id ")
        ->bindValue(':employee_id', $model->authorized_by)
        ->queryOne();
}
?>
<div class="trip-ticket-view">



    <div class="container card" style="padding: 1rem;">
        <p>
            <?= Yii::$app->user->can('update_trip_ticket') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate btn btn-primary']) : '' ?>
        </p>
        <table>

            <tbody>
                <tr>
                    <th colspan="6" class=" no-border" style="text-align: center;">DRIVERS TRIP TICKET</th>
                </tr>
                <tr>


                    <td class="first_col   no-border">Date</td>
                    <td colspan="2" class=" no-border underlined">
                        <span><?= DateTime::createFromFormat('Y-m-d', $model->date)->format('F d, Y') ?></span>
                        <?php
                        if (!empty($model->to_date)) {
                            echo ' - ' . DateTime::createFromFormat('Y-m-d', $model->to_date)->format('F d, Y');
                        }
                        ?>
                    </td>
                    <td colspan="3" class="    no-border">Plate No:
                        <span class="underlined" style="margin-left: 1rem;"><?= !empty($model->carType->car_name) ? strtoupper($model->carType->plate_number) : '' ?></span>
                    </td>
                </tr>
                <tr>
                    <td class=" first_col  no-border" style="min-width: 70px;">Driver</td>
                    <td colspan="2" class="   underlined no-border"><?= $driver ?></td>
                    <td class="  no-border" colspan="3">Series No: <span class="underlined" style="margin-left: 1rem;"><?= $model->serial_no ?></span></td>
                </tr>
                <tr>
                    <td colspan="3" class="first_col  no-border">
                        PURPOSE OF TRAVEL


                    </td>
                    <td colspan="3" class=" no-border ">AUTHORIZED BY:</td>
                </tr>
                <tr>
                    <td class="no-border"></td>
                    <td colspan="2" class="no-border">

                        <span class="underlined">
                            <?= $model->purpose ?>
                        </span>
                    </td>
                    <td colspan="3" style="text-align: center;" class="no-border">

                        <b class="underlined"><?= $authorized_by['employee_name'] ?></b>
                        <br>
                        <span><?= $authorized_by['position'] ?></span>
                    </td>
                </tr>
                <tr>
                    <td rowspan="3" class="first_col"> Trip No.</td>
                </tr>
                <tr>
                    <td colspan="2" class="center">DEPARTURE</td>
                    <td colspan="2" class="center">ARRIVAL</td>
                    <td rowspan="2">Signatue of Authorized <br>PASSENGER/S </td>

                </tr>
                <tr>
                    <td class="time">Time</td>
                    <td class="place">Place</td>
                    <td class="time">Time</td>
                    <td class="place">Place</td>
                </tr>
                <?php
                $item_no = 1;
                foreach ($items as $item) {
                    $departure_time = $item['departure_time'];
                    $departure_place = $item['departure_place'];
                    $arrival_time = $item['arrival_time'];
                    $arrival_place = $item['arrival_place'];
                    $employee_name = $item['employee_name'];
                    echo "<tr class='r'>
                    <td>$item_no</td>
                    <td>$departure_time</td>
                    <td>$departure_place</td>
                    <td>$arrival_time</td>
                    <td>$arrival_place</td>
                    <td>$employee_name</td>
                </tr>";
                    $item_no++;
                }
                $r = $item_no;
                for ($i = $r; $i <= 20; $i++) {
                    echo "<tr class='r'>
                        <td class='first_col'>$item_no</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>";
                    $item_no++;
                }
                ?>
                <tr>
                    <td colspan=" 2" class=" no-border">
                        <span>FUEL/ OIL USED</span>
                        <br>
                        <span> Bal. in tank Beg.</span>

                        <br>
                        <span> Issued from stock.</span>

                        <br>
                        <span>Purshase outside</span>

                        <br>
                        <span>TOTAL</span>

                        <br>
                        <span>Used during the trip</span>

                        <br>
                        <span>Bal. in tank End</span>

                        <br>
                        <span>Oil</span>
                        <br>
                        <span>Others(Specify)</span>
                        <br>

                        <br>
                    </td>
                    <td class=" no-border" colspan="2">
                        <br>
                        <span>_____________________ Liters</span> <br>
                        <span>_____________________ Liters</span> <br>
                        <span>_____________________ Liters</span> <br>
                        <span>_____________________ Liters</span> <br>
                        <span>_____________________ Liters</span> <br>
                        <span>_____________________ Liters</span> <br>
                        <span>_____________________ Qrt/s</span> <br>
                        <span>__________________________</span> <br>
                        <span>__________________________</span> <br>
                    </td>
                    <td colspan="3" class=" no-border">
                        <span> SPEEDOMETER READING ( if any):</span><br><br>
                        <span>End of Trip:</span>
                        <span>__________________ Kms</span> <br>
                        <span>Beg. Of Trip:</span>
                        <span>__________________ Kms</span> <br>
                        <span>TOTAL DIST.</span>
                        <span>__________________ Kms</span> <br>
                        <span>TRAVELLED:</span>
                        <span>__________________ Kms</span> <br>




                    </td>
                </tr>
                <tr>
                    <td colspan="3" class=" no-border"></td>
                    <td class=" no-border"></td>
                    <td colspan="2" style="text-align: center;" class=" no-border">

                        <span class="underlined"><?= $driver ?></span>
                        <br>
                        <span>Driver</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/css/customCss.css", ['depends' => [\yii\web\JqueryAsset::class]]);

?>
<style>
    .underlined {
        text-decoration: underline;
    }

    .r>td {
        padding: 3px;
    }

    .center {
        text-align: center;
    }

    th,
    td {
        padding: 12px;
        border: 1px solid black;
    }

    table {
        width: 100%;
    }

    .first_col {
        max-width: 50px;
    }

    .place {
        min-width: 150px;
        max-width: 150px;
    }

    .time {
        max-width: 80px;
        min-width: 80px;
    }

    @media print {

        .main-footer,
        .btn {
            display: none;
        }

    }
</style>