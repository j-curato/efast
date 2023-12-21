<?php

use app\components\helpers\MyHelper;
use app\models\ItHelpdeskCsf;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ItHelpdeskCsf */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'It Helpdesk Csfs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$client = MyHelper::getEmployee($model->maintenanceRequest->fk_requested_by, 'one');

?>
<div class="it-helpdesk-csf-view">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary lrgModal']) ?>
        <?= Html::a('TA/IR', ['it-maintenance-request/view', 'id' => $model->fk_it_maintenance_request], ['class' => 'btn btn-link']) ?>

    </p>


    <table>

        <tr>
            <td colspan="4" class="no-bdr"></td>
            <td colspan="1" class="no-bdr" style="float: right;">
                <span>Document Code:</span> <span>FM-CSF-05</span><br>
                <span>Version No:</span> <span>0</span><br>
                <span>Effectivity Date:</span> <span>01-Dec-2021</span>
            </td>
        </tr>
        <tr>
            <td colspan="5" class="ctr no-bdr">
                <b>DEPARTMENT OF TRADE AND INDUSTRY</b><br>
                <span>CARAGA REGIONAL OFFICE</span>
            </td>
        </tr>
        <tr>
            <td colspan="5" class="ctr">
                <b>CLIENT SATISFACTION FEEDBACK FORM</b> <br>
                <span style="color: blue;">Procurement | Janitorial |Driving | Processing of Financial Claims (Internal) |IT Helpdesk | IT Maintenance | Property Maintenance</span>
            </td>
        </tr>
        <tr>
            <td colspan="5" class="no-bdr">
                <b>CONSENT:</b> <span>I Hereby consent to the collection and processing by the DTI of my name,
                    contact details, and my feedback on its services for the purpose of monitoring, measuring, and analyzing customer
                    feedback and of improving DTI services. I shall notify the DTI in case of any changes in my personal information.
                    This consent shall be valid, unless revoked or withdrawn in writing subject to the applicable provisions of the <b>Data Privacy Act of 2012</b> or Republic Act No. 10173</span>
            </td>
        </tr>
        <tr>
            <td colspan="1" class="no-bdr ctr">
                <br><br>
                <span>____________________</span><br>
                <span>Client's Signature</span>
            </td>
            <td colspan="2" class="no-bdr ctr">

            </td>
            <td colspan="" class="no-bdr ctr">
                <br><br>
                <u><b>&emsp; <?= $model->date ?? '' ?>&emsp;</b></u><br>
                <span>Date</span>
            </td>
            <td class="no-bdr ctr"></td>
        </tr>
        <tr>
            <td colspan="5">

                <span> CLIENTS NAME (First Name and Last Name):</span>
                <u><b>&emsp; <?= $client['employee_name'] ?>&emsp;</b></u>
            </td>
        </tr>
        <tr>
            <td colspan="5">

                <span>ADDRESS:</span>
                <u><b></b></u>
            </td>
        </tr>
        <tr>
            <td colspan="3">

                <span> CONTACT NUMBER:</span>
                <u><b></b></u>

            </td>
            <td colspan="2">
                <span>E-MAIL ADDRESS:</span>
                <u><b></b></u>

            </td>

        </tr>
        <tr>
            <td colspan="5">
                <b>PART I. Our office is commited to continually improve our services to our external clients.</b> <span> Please answer this Form for us to know your feedback on the different aspects of our activity.
                    Your feedback and impressions will help us in improving our future activities in order to better server our clients. Rest assured all information you will provide shall be treated with strict confidentiality.</span>
            </td>
        </tr>
        <tr>
            <th colspan="5">A. Please check-mark the box that correspons to your answer.</th>
        </tr>
        <tr>
            <td class="no-bdr">
                <b>SEX</b><br>
                <span class="chk_box">
                    <?php
                    //  $model->sex === 'm' ? "<span>&#10003;</span>" :
                    echo "<span class='q'>...</span>"
                    ?>
                </span> <span>&nbsp; Male</span>
                <br>
                <span class="chk_box">
                    <?php
                    //  $model->sex === 'f' ? "<span>&#10003;</span>" : 
                    echo "<span class='q'>...</span>"
                    ?>
                </span><span>&nbsp; Female</span><br>
                <br><br>
            </td>
            <td class="no-bdr" style="min-width: 100px;">
                <b>AGE</b><br>
                <span class="chk_box">
                    <?php
                    //  ($model->age_group === '21-35') ? "<span>&#10003;</span>" : 
                    echo "<span class='q'>...</span>"
                    ?>
                </span> <span>&nbsp; 21 - 35 years old and below</span>
                <br>
                <span class="chk_box">
                    <?php
                    //  $model->age_group === '35-59' ? "<span>&#10003;</span>" : 
                    echo "<span class='q'>...</span>"
                    ?>
                </span> <span>&nbsp; Above 35 - below 60 years old</span>
                <br>
                <span class="chk_box">
                    <?php
                    //  $model->age_group === '60' ? "<span>&#10003;</span>" :
                    echo  "<span class='q'>...</span>"
                    ?>
                </span> <span>&nbsp; 60 years old & above</span>
                <br>
                <br>
            </td>
            <td class="no-bdr">
                <b>SOCIAL GROUP <i>(if applicable)</i></b><br>
                <span class="chk_box">
                    <?php

                    if (!empty($transfer_type) === 'donation') {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span><span>&nbsp; 4Ps</span>
                <br>
                <span class="chk_box">
                    <?php

                    if (!empty($transfer_type) === 'donation') {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span> <span>&nbsp; PWD</span><br>
                <span class="chk_box">
                    <?php

                    if (!empty($transfer_type) === 'donation') {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span> <span>&nbsp; Others:</span><br>
                <span>__________________</span>
            </td>
            <td colspan="2" class="no-bdr">
                <span class="chk_box">
                    <?php

                    if (!empty($transfer_type) === 'donation') {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
                <span>&nbsp; OFW</span>
                <br>
                <span class="chk_box">
                    <?php

                    if (!empty($transfer_type) === 'donation') {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span> <span>&nbsp; Indigenous Person</span>
            </td>

        </tr>
        <tr>
            <th colspan="5">SERVICE REQUESTED:</th>
        </tr>
        <tr>
            <td class='no-bdr'>
                <span class="chk_box">
                    <?= "<span class='q'>...</span>" ?>
                </span> <span>&nbsp; Procurement</span><br>
                <span class="chk_box">
                    <?= "<span class='q'>...</span>" ?>
                </span> <span>&nbsp; Processing of Financil Claims</span>
            </td>
            <td class='no-bdr'>
                <span class="chk_box">
                    <?= "<span class='q'>...</span>" ?>
                </span> <span>&nbsp; Janitorial</span><br>
                <span class="chk_box">
                    <?= "<span class='q'>...</span>" ?>
                </span> <span>&nbsp; Driving</span>
            </td>
            <td colspan="2" class='no-bdr'>
                <span class="chk_box">
                    <?= "<span>&#10003;</span>" ?>
                </span> <span>&nbsp; IT Helpdesk</span><br>
                <span class="chk_box">
                    <?= "<span>&#10003;</span>" ?>
                </span> <span>&nbsp; IT Maintenance</span>
            </td>
            <td class='no-bdr'>
                <span class="chk_box">
                    <?= "<span class='q'>...</span>" ?>
                </span> <span>&nbsp; Property Maintenance</span>
                <br>
                <br>
            </td>

        </tr>
        <tr>
            <th colspan="5">B. For each citerion below, please check-mark the box under the column pertaining to your Rating. Mark ONE BOX ONLY for each row. For eavery DISSATISFIED
                or VERY DISSATISFIED rating you will give, please provide reason/s in PART II below.
            </th>
        </tr>
        <tr>
            <th rowspan="2" class="ctr">CRITERIA FOR RATING</th>
            <th colspan="4" class="ctr">RATING</th>
        </tr>
        <tr>
            <th class="ctr">VERY SATISFIED</th>
            <th class="ctr">SATISFIED</th>
            <th class="ctr">DISSATISFIED</th>
            <th class="ctr">VERY DISSATISFIED</th>
        </tr>
        <tr>
            <th>1. DTI STAFF</th>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>a. Clarity and accuracy of information given</td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->clarity === 4) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->clarity === 3) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->clarity === 2) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->clarity === 1) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>


        </tr>
        <tr>
            <td>b. Skills/Knowledge</td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->skills === 4) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->skills === 3) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->skills === 2) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->skills === 1) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>

        </tr>
        <tr>
            <td>c. Professionalism</td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->professionalism === 4) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->professionalism === 3) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->professionalism === 2) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->professionalism === 1) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>

        </tr>
        <tr>
            <td>d. Courtesy</td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->courtesy === 4) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->courtesy === 3) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->courtesy === 2) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->courtesy === 1) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>

        </tr>
        <tr>
            <td>e. Response time</td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->response_time === 4) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->response_time === 3) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->response_time === 2) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->response_time === 1) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>

        </tr>
        <tr>
            <th>2. OUTCOME/Result of Service/s Requested</th>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->clarity === 4) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->clarity === 3) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->clarity === 2) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>
            <td class="ctr">
                <span class="chk_box">
                    <?php

                    if ($model->clarity === 1) {
                        echo "<span>&#10003;</span>";
                    } else {
                        echo "<span class='q'>...</span>";
                    }
                    ?>
                </span>
            </td>
        </tr>
        <tr>
            <th colspan="5">PART II. COMMENTS AND SUGGESTIONS</th>
        </tr>
        <tr>
            <td colspan="5">Please Write in the space below your reason/s for your "DISSATISFIED" OR "VERY DISSATISFIED" rating so that we will know in which area/s we need to improve.</td>
        </tr>
        <tr>
            <td colspan="5">

                <?= $model->vd_reason ?? '<br><br><br>' ?>
            </td>
        </tr>
        <tr>
            <td colspan="5">Please give comments/suggestions to help us improve our service/s</td>
        </tr>
        <tr>
            <td colspan="5">
                <?= $model->comment ?? '<br><br><br>' ?>
            </td>
        </tr>
        <tr>
            <th colspan="5" class="ctr">THANK YOU!</th>
        </tr>
    </table>
</div>
<style>
    .no-bdr {
        border: 0;
    }

    .ctr {
        text-align: center;
    }

    .chk_box {
        border: 1px solid black;

    }

    .q {
        visibility: hidden;
    }

    th,
    td {
        border: 1px solid black;
        padding: 10px;
    }

    table {
        width: 100%;
        border: 1px solid black;
    }

    .it-helpdesk-csf-view {
        background-color: white;
    }

    @media print {

        .main-footer,
        .btn {
            display: none;
        }

        th,
        td {
            font-size: 10px;
            padding: 2px;
        }
    }
</style>
<?php
$this->registerJsFile(
    '@web/frontend/web/js/globalFunctions.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
)
?>