<?php

use kartik\icons\Icon;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TravelOrder */

$this->title = $model->to_number;
$this->params['breadcrumbs'][] = ['label' => 'Travel Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$budget_officer = '';



if (!empty($model->fk_budget_officer)) {
    $budget_officer = YIi::$app->db->createCommand("SELECT employee_name,position FROM employee_search_view WHERE employee_id = :employee_id ")
        ->bindValue(':employee_id', $model->fk_budget_officer)
        ->queryOne();
}
$approved_by = '';
if (!empty($model->fk_approved_by)) {


    $approved_by = YIi::$app->db->createCommand("SELECT employee_name,position FROM employee_search_view WHERE employee_id = :employee_id ")
        ->bindValue(':employee_id', $model->fk_approved_by)
        ->queryOne();
}
$recommending_approval = '';
if (!empty($model->fk_recommending_approval)) {


    $recommending_approval = YIi::$app->db->createCommand("SELECT employee_name,position FROM employee_search_view WHERE employee_id = :employee_id ")
        ->bindValue(':employee_id', $model->fk_recommending_approval)
        ->queryOne();
}
$rows = count($items);
$y =  !empty($model->expected_outputs) ? 10 : 9;
$x = $rows + $y;

$date_format = DateTime::createFromFormat('Y-m-d', $model->date);
$date = $date_format->format('F d, Y');

?>

<div class="travel-order-view">




    <div class="container">
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        </p>
        <table>
            <thead>
                <tr>

                    <th colspan="4">
                        <div style="float: right;" class="header-space">
                            <?= Html::img('frontend/web/dti.jpg', ['alt' => 'some', 'class' => 'pull-left img-responsive', 'style' => 'width: 100px;']); ?>

                        </div>
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <th rowspan="<?= $x ?>" class="margin-col" style="border:0">
                    </th>

                    <th colspan="3" class="center">
                        <h4>TRAVEL ORDER</h4>
                    </th>
                </tr>
                <tr>
                    <td>


                        <span><?= !empty($model->to_number) ? $model->to_number : '' ?></span>
                    </td>
                    <td></td>
                    <td style="text-align: right;">

                        <span>Date: <?= $date ?></span>

                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <p> A. The following personnel are hereby directed to the places indicated opposite their respective names:
                        </p>

                    </td>
                </tr>
                <tr>
                    <th class="center bordered">NAME/DESIGNATION</th>
                    <th class="center bordered">INCLUSIVE DATE OF TRAVEL </th>
                    <th class="center bordered">DESTINATION/PLACES TO BE VISITED </th>
                </tr>
                <?php

                foreach ($items as $i => $item) {

                    $employee_name = !empty($item['employee_name']) ? $item['employee_name'] : '';
                    $position = !empty($item['position']) ? $item['position'] : '';
                    $from_date = !empty($item['from_date']) ? DateTime::createFromFormat('Y-m-d', $item['from_date']) : '';
                    $to_date = !empty($item['to_date']) ? DateTime::createFromFormat('Y-m-d', $item['to_date']) : '';
                    $travel_date = '';
                    if (!empty($from_date) && !empty($to_date)) {


                        if ($from_date->format('m-Y') ===  $to_date->format('m-Y')) {
                            $travel_date =  $from_date->format('F d') . '-' . $to_date->format('d, Y');
                        } else if ($from_date->format('Y') ===  $to_date->format('Y')) {
                            $travel_date =  $from_date->format('F d') . '-' . $to_date->format('F d, Y');
                        } else {
                            $travel_date =  $from_date->format('F d, Y') . '-' . $to_date->format('F d, Y');
                        }
                    } else {
                        $travel_date = !empty($from_date) ? $from_date->format('F d, Y') : '';
                    }

                    $last_row = $i === $rows - 1 ? 'border-bottom:1px solid black;' : '';
                    echo "<tr class='qqq' style='border-right:1px solid black;$last_row'>
                    <td class=''  style='border-left:1px solid black;border-right:1px solid black;'><b>$employee_name</b> / $position</td>
                    <td class=''  style='border-left:1px solid black;border-right:1px solid black;'>$travel_date</td>
                    ";
                    if ($i === 0) {
                        echo "<td rowspan='$rows' class='center'>{$model->destination}</td>";
                    }
                    echo "</tr>";
                }
                // for ($x = 0; $x < 30; $x++) {

                //     echo "<tr>
                //     <td>lorem</td>
                //     </tr>";
                // }
                ?>
                <tr>
                    <td colspan="3">
                        B. Purpose:&ensp;


                        <?= preg_replace('#\[n\]#', "<br> &emsp;  &emsp;", $model->purpose) ?>
                    </td>
                </tr>

                <?php

                if (!empty($model->expected_outputs)) {
                    echo "<tr>
                            <td colspan='3'>C. Expected Outputs: &nbsp;" . preg_replace('#\[n\]#', "<br> &emsp; &emsp; ", $model->expected_outputs) . "</td>
                         </tr>";
                }
                ?>

                <tr>
                    <td colspan="3">
                        <?= !empty($model->expected_outputs) ? 'D ' : 'C' ?>. Air, Land or Water Transportation is authorized. Traveling expenses allowed will be in accordance with Executive Order No. 77 dated 15 March 2019, subject to availability of fund and the usual accounting and auditing regulations.
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <?= !empty($model->expected_outputs) ? 'E' : 'D' ?>. Submit immediately the required Certificate of Travel Completed together with transportation tickets, Certificate of Appearance and other necessary supporting papers, if any.
                    </td>
                </tr>

                <tr>
                    <td class='center'>
                        <br>
                        <br>
                        <?php
                        $budget_officer_name = !empty($budget_officer['employee_name']) ? $budget_officer['employee_name'] : '';
                        $budget_officer_position = !empty($budget_officer['position']) ? $budget_officer['position'] : '';
                        if (!empty($model->fk_recommending_approval)) {

                            echo "
                            <span>Funds Available:</span>
                            <br>
                            <br>
                            <br>
                            <span class='bold underlined'>
                            $budget_officer_name
                            </span>
                            <br>
                            <span>$budget_officer_position</span>
                       ";
                        }
                        ?>
                    </td>
                    <td></td>
                    <?php

                    if (!empty($model->fk_recommending_approval)) {

                        $recommending_approval_name = !empty($recommending_approval['employee_name']) ? $recommending_approval['employee_name'] : '';
                        $recommending_approval_position = !empty($recommending_approval['position']) ? $recommending_approval['position'] : '';
                        echo " <td class='center'>
                        <br><br>
                            <span>Recommending Approval:</span>
                            <br>
                            <br>
                            <br>
                            <span class='bold underlined'>
                            $recommending_approval_name
                            </span>
                            <br>
                            <span>$recommending_approval_position</span>
                        </td>";
                    } else {

                        echo "
                     
                        <td class='center'>
                        <br><br>
                            <span>Funds Available:</span>
                            <br>
                            <br>
                            <br>
                            <span class='bold underlined'>
                            $budget_officer_name
                            </span>
                            <br>
                            <span>$budget_officer_position</span>
                        </td>";
                    }
                    ?>

                </tr>
                <tr>
                    <td colspan="3" class="center">
                        <span>Approved By:</span>
                        <br>
                        <br>
                        <br>
                        <span class="bold underlined">
                            <?= $approved_by['employee_name'] ?>
                        </span>
                        <br>
                        <span><?= $approved_by['position'] ?></span>
                    </td>
                </tr>
            </tbody>
            <tfoot>

                <tr>
                    <td colspan="4">
                        <div class='float-container'>

                            <div class='float-child green'>
                                <span>
                                    <?= Icon::show('map-marker', ['framework' => Icon::FA]) ?>

                                    <?php
                                    if ($model->type === 'national') {
                                        echo '6F Trade and Industry Building 361 Senator Gil. J. Puyat Avenue, 1200 Makati City, Philippines';
                                    } else if ($model->type === 'regional') {
                                        echo 'DTI-Caraga Regional Office, West Wing, 3rd Floor, D&V Plaza Building,  J.C. Aquino Avenue, Butuan City, Philippines';
                                    }
                                    ?>
                                </span>

                            </div>

                            <div class='float-child blue'>
                                <span style='margin-right:5.5rem ;margin-left:1rem'><?= Icon::show('phone', ['framework' => Icon::FA]) ?><?= $model === 'national' ? '(632) 751.3334' : '(085) 816-0079' ?></span>
                                <span><?= Icon::show('fax', ['framework' => Icon::FA]) ?><?= $model === 'national' ? '(632) 890.4685' : '(085) 815-1271' ?></span><br>
                                <span style='margin-right:1rem;margin-left:1rem'><?= Icon::show('globe', ['framework' => Icon::FA]) ?><?= $model === 'national' ? 'www.dti.gov.ph' : 'www.dti.gov.ph/caraga' ?></span>
                                <?= Icon::show('envelope', ['framework' => Icon::FA]) ?><span class='underlined'><?= $model === 'national' ? 'rog@dti.gov.ph' : 'caraga@dti.gov.ph' ?></span>

                            </div>

                        </div>
                    </td>
                </tr>


            </tfoot>
        </table>
    </div>



</div>

<?php
// $this->registerCssFile(Yii::$app->request->baseUrl . "/css/customCss.css", ['depends' => [\yii\web\JqueryAsset::class]]);

?>
<style>
    .float-container {
        border: 3px solid #fff;
        bottom: 0;
    }


    .float-child {
        width: 50%;
        float: left;
        padding: 10px;
    }

    /*  */
    /* .ff {
        margin-top: auto;
        left: 0;
        bottom: 2in;
        width: 100%;
        background-color: red;
        color: white;
    } */

    .bold {
        font-weight: bold;
    }

    .underlined {
        text-decoration: underline;
    }

    th,
    td {
        padding: 12px;

    }


    .center {
        text-align: center;
    }

    .container {
        background-color: white;
        height: 100%;

    }

    table {
        width: 100%;
        padding-left: 1in;

    }

    .bordered {
        border: 1px solid black;
    }



    .blue {
        color: blue;
    }

    @page {
        size: A4;
        margin-left: 100px;
    }

    @media print {

        .margin-col {
            padding-left: .9in;
        }

        th,
        td {
            padding: 5px;
        }

        tfoot>td {
            position: fixed;
        }


        .btn {
            display: none;
        }

        .blue {
            color: blue;
        }

        .travel-order-view {
            height: 87vh;
        }


        ::-webkit-scrollbar {
            background: transparent;
        }



    }
</style>
<script>
    $(document).ready(function() {
        window.matchMedia('print').addListener(function(mql) {
            console.log('container:' +
                $('.container').height())
            const clone = $('.float-container')

            $(".main-footer").append(clone);
            $('.main-footer').css('border', 0)
            $('.main-footer').css('text-align', 'center')
        });

    })
</script>