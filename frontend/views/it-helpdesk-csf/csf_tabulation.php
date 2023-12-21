<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\ItHelpdeskCsf;
use app\components\helpers\MyHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\ItHelpdeskCsf */


$this->params['breadcrumbs'][] = ['label' => 'It Helpdesk Csfs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="it-helpdesk-csf-view" id="mainVue">
    <div class="container">

        <div class="card p-2">

            <div class="row">
                <div class="col-sm-3">
                    <label for="">From
                        <?= DatePicker::widget([
                            'name' => 'from_period',
                            'id' => 'from_period',
                            'pluginOptions' => [
                                'format' => 'yyyy-mm',
                                'minViewMode' => 'months',
                                'autoclose' => true
                            ]
                        ]) ?>
                    </label>
                </div>
                <div class="col-sm-3">
                    <label for="">To
                        <?= DatePicker::widget([
                            'name' => 'to_period',
                            'id' => 'to_period',
                            'pluginOptions' => [
                                'format' => 'yyyy-mm',
                                'minViewMode' => 'months',
                                'autoclose' => true
                            ]
                        ]) ?>
                    </label>
                </div>
                <div class="col-sm-3 pt-4">
                    <button type="button" @click.prevent="apiCsfData" class="btn btn-success ">Generate</button>
                </div>
            </div>
        </div>
        <div class="card p-3 ">

            <table>
                <tr>
                    <th>Period </th>
                    <th colspan="4"></th>
                </tr>
                <tr>
                    <th>No. of Raters</th>
                    <th class="text-center " colspan="4">{{csf.details.number_of_raters}}</th>
                </tr>
                <tr>
                    <th></th>
                    <th>VERY SATISFIED </th>
                    <th>SATISFIED</th>
                    <th>DISSATISFIED </th>
                    <th>VERY DISSATISFIED</th>
                </tr>
                <tr>
                    <th>Clarity</th>
                    <th class="text-center"> {{getRates(4,'clarity')}}</th>
                    <th class="text-center"> {{getRates(3,'clarity')}}</th>
                    <th class="text-center"> {{getRates(2,'clarity')}}</th>
                    <th class="text-center"> {{getRates(1,'clarity')}}</th>
                </tr>
                <tr>
                    <th>Skills</th>
                    <th class="text-center"> {{getRates(4,'skills')}}</th>
                    <th class="text-center"> {{getRates(3,'skills')}}</th>
                    <th class="text-center"> {{getRates(2,'skills')}}</th>
                    <th class="text-center"> {{getRates(1,'skills')}}</th>
                </tr>
                <tr>
                    <th>Professionalism</th>
                    <th class="text-center"> {{getRates(4,'professionalism')}}</th>
                    <th class="text-center"> {{getRates(3,'professionalism')}}</th>
                    <th class="text-center"> {{getRates(2,'professionalism')}}</th>
                    <th class="text-center"> {{getRates(1,'professionalism')}}</th>
                </tr>
                <tr>
                    <th>Courtesy</th>
                    <th class="text-center"> {{getRates(4,'courtesy')}}</th>
                    <th class="text-center"> {{getRates(3,'courtesy')}}</th>
                    <th class="text-center"> {{getRates(2,'courtesy')}}</th>
                    <th class="text-center"> {{getRates(1,'courtesy')}}</th>
                </tr>
                <tr>
                    <th>Response Time</th>
                    <th class="text-center"> {{getRates(4,'response_time')}}</th>
                    <th class="text-center"> {{getRates(3,'response_time')}}</th>
                    <th class="text-center"> {{getRates(2,'response_time')}}</th>
                    <th class="text-center"> {{getRates(1,'response_time')}}</th>
                </tr>
                <tr>
                    <th>OUTCOME/Result of Services Requested</th>
                    <th class="text-center"> {{getRates(4,'outcome')}}</th>
                    <th class="text-center"> {{getRates(3,'outcome')}}</th>
                    <th class="text-center"> {{getRates(2,'outcome')}}</th>
                    <th class="text-center"> {{getRates(1,'outcome')}}</th>
                </tr>





            </table>
        </div>
    </div>

</div>
<style>
    th,
    td {
        border: 1px solid black;
        padding: 10px;
    }
</style>

<script>
    new Vue({
        el: "#mainVue",
        data: {
            csf: {

                clarity: [],
                skills: [],
                professionalism: [],
                courtesy: [],
                response_time: [],
                outcome: [],
                details: [],
            }
        },
        methods: {

            apiCsfData() {
                const url = window.location.href
                const data = {
                    from_period: $("#from_period").val(),
                    to_period: $("#to_period").val(),
                    _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
                }
                axios.post(url, data)
                    .then(res => {
                        this.csf = res.data

                        console.log(res.data)
                    })
                    .catch(error => {
                        console.log(error)
                    })

            },
            getRates(rateNumber, rateType) {

                // Use the filter method to get items where clarity is equal to 1
                const filteredRateType = this.csf[rateType].filter(item => item[rateType] == rateNumber)[0]
                if (filteredRateType) {
                    return filteredRateType['num_of_rates']
                }

            }

        },
        computed: {

        }

    })
</script>