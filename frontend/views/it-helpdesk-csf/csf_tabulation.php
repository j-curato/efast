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
                <tr class=" table-info">
                    <th colspan="7" class="text-center">ARTA SERVICE QUALITY DIMENSIONS SUMMARY</th>
                </tr>
                <tr>
                    <th>Period </th>
                    <th colspan="6" class="text-center">{{fromPeriod}}
                        <span v-if="fromPeriod!=toPeriod"> to {{toPeriod}}</span>
                    </th>
                </tr>
                <tr>
                    <th>No. of Raters</th>
                    <th class="text-center " colspan="6">{{csf.details.number_of_raters}}</th>
                </tr>
                <tr>
                    <th></th>
                    <th>VERY SATISFIED </th>
                    <th>SATISFIED</th>
                    <th>DISSATISFIED </th>
                    <th>VERY DISSATISFIED</th>
                    <th>CSF Score</th>
                    <th>CSF Rating</th>
                </tr>
                <tr>
                    <th>Clarity</th>
                    <th class="text-center"> {{getRates(4,'clarity')}}

                    </th>
                    <th class="text-center"> {{getRates(3,'clarity')}}</th>
                    <th class="text-center"> {{getRates(2,'clarity')}}</th>
                    <th class="text-center"> {{getRates(1,'clarity')}}</th>
                    <th class="text-center">
                        {{computeCsfScore('clarity')}}
                    </th>
                    <th class="text-center">
                        {{computeCsfRating('clarity')}}%
                    </th>
                </tr>
                <tr>
                    <th>Skills</th>
                    <th class="text-center"> {{getRates(4,'skills')}}</th>
                    <th class="text-center"> {{getRates(3,'skills')}}</th>
                    <th class="text-center"> {{getRates(2,'skills')}}</th>
                    <th class="text-center"> {{getRates(1,'skills')}}</th>
                    <th class="text-center">
                        {{computeCsfScore('skills')}}
                    </th>
                    <th class="text-center">
                        {{computeCsfRating('skills')}}%
                    </th>
                </tr>
                <tr>
                    <th>Professionalism</th>
                    <th class="text-center"> {{getRates(4,'professionalism')}}</th>
                    <th class="text-center"> {{getRates(3,'professionalism')}}</th>
                    <th class="text-center"> {{getRates(2,'professionalism')}}</th>
                    <th class="text-center"> {{getRates(1,'professionalism')}}</th>
                    <th class="text-center">
                        {{computeCsfScore('professionalism')}}
                    </th>
                    <th class="text-center">
                        {{computeCsfRating('professionalism')}}%
                    </th>
                </tr>
                <tr>
                    <th>Courtesy</th>
                    <th class="text-center"> {{getRates(4,'courtesy')}}</th>
                    <th class="text-center"> {{getRates(3,'courtesy')}}</th>
                    <th class="text-center"> {{getRates(2,'courtesy')}}</th>
                    <th class="text-center"> {{getRates(1,'courtesy')}}</th>
                    <th class="text-center">
                        {{computeCsfScore('courtesy')}}
                    </th>
                    <th class="text-center">
                        {{computeCsfRating('courtesy')}}%
                    </th>
                </tr>
                <tr>
                    <th>Response Time</th>
                    <th class="text-center"> {{getRates(4,'response_time')}}</th>
                    <th class="text-center"> {{getRates(3,'response_time')}}</th>
                    <th class="text-center"> {{getRates(2,'response_time')}}</th>
                    <th class="text-center"> {{getRates(1,'response_time')}}</th>
                    <th class="text-center">
                        {{computeCsfScore('response_time')}}
                    </th>
                    <th class="text-center">
                        {{computeCsfRating('response_time')}}%
                    </th>
                </tr>
                <tr>
                    <th>OUTCOME/Result of Services Requested</th>
                    <th class="text-center"> {{getRates(4,'outcome')}}</th>
                    <th class="text-center"> {{getRates(3,'outcome')}}</th>
                    <th class="text-center"> {{getRates(2,'outcome')}}</th>
                    <th class="text-center"> {{getRates(1,'outcome')}}</th>
                    <th class="text-center">
                        {{computeCsfScore('outcome')}}
                    </th>
                    <th class="text-center">
                        {{computeCsfRating('outcome')}}%
                    </th>
                </tr>
                <tr>
                <tr>
                    <th colspan="5" class="text-center">OVERALL AVE</th>
                    <th class="text-center">{{computeCsfScoreAve}}</th>
                    <th class="text-center">{{computeCsfRatingAve}}</th>
                </tr>
                </tr>
            </table>
            <!-- <table class="mt-3 ">

                <tr class="table-info">
                    <th colspan="2" class="text-center">SQD (OVERALL SCORE)</th>

                </tr>
                <tr>
                    <th class="text-center">Dimensions </th>
                    <th class="text-center">CSF Score</th>
                </tr>
                <tr>
                    <th>Clarity</th>
                    <th class="text-center"> {{computeCsfScore('clarity')}}</th>

                </tr>
                <tr>
                    <th>Skills</th>

                    <th class="text-center"> {{computeCsfScore('skills')}}</th>

                </tr>
                <tr>
                    <th>Professionalism</th>
                    <th class="text-center"> {{computeCsfScore('professionalism')}}</th>

                </tr>
                <tr>
                    <th>Courtesy</th>
                    <th class="text-center"> {{computeCsfScore('courtesy')}}</th>

                </tr>
                <tr>
                    <th>Response Time</th>
                    <th class="text-center"> {{computeCsfScore('response_time')}}</th>

                </tr>
                <tr>
                    <th>OUTCOME/Result of Services Requested</th>
                    <th class="text-center"> {{computeCsfScore('outcome')}}</th>

                </tr>
                <tr>
                    <th>OVERALL AVE</th>
                    <th class="text-center">{{computeCsfScoreAve}}</th>
                </tr>
                <tr>
                    <th>OVERALL RATING</th>
                    <th class="text-center">{{computeCsfRatingAve}}</th>
                </tr>
            </table> -->
            <p class="mt-4">
                For period ________,
                a total of <b> {{csf.details.number_of_raters}}</b> clients received a CSF in relation to the
                IT Technical Assistance service process.
                The process owner successfully collected all the forms,
                demonstrating 100% retrieval efficiency across both online and offline platforms.
                An evaluation of the overall process revealed a weighted average rating of <b>
                    {{computeCsfScoreAve}}</b>,
                translating into a CSF score of <b> {{computeCsfRatingAve}}%</b>.
                This score is calculated based on the SQD of the process,
                reflecting a Satisfied level of client satisfaction.




            </p>
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
            fromPeriod: null,
            toPeriod: null,
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
                this.fromPeriod = this.formattedPeriod($("#from_period").val())
                this.toPeriod = this.formattedPeriod($("#to_period").val())
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
                return 0;

            },
            computeCsfScore(type) {

                let res = ((this.getRates(4, type) * 4) +
                        (this.getRates(3, type) * 3) +
                        (this.getRates(2, type) * 2) +
                        (this.getRates(1, type) * 1)) /
                    parseInt(this.csf.details.number_of_raters)
                return isNaN(res) ? 0 : res.toFixed(2)
            },
            computeCsfRating(type) {
                return (this.computeCsfScore(type) / 4) * 100
            },
            formattedPeriod(period) {
                // Split the date into year and month
                const [year, month] = period.split('-');

                // Create a Date object using the year and month
                const dateObject = new Date(`${year}-${month}-01`);

                // Format the date using the toLocaleString method
                const formattedDateString = dateObject.toLocaleString('en-US', {
                    month: 'long',
                    year: 'numeric',
                });

                return formattedDateString;
            },

        },
        computed: {

            computeCsfScoreAve() {

                let ave =
                    (parseFloat(this.computeCsfScore('clarity')) +
                        parseFloat(this.computeCsfScore('skills')) +
                        parseFloat(this.computeCsfScore('professionalism')) +
                        parseFloat(this.computeCsfScore('courtesy')) +
                        parseFloat(this.computeCsfScore('response_time')) +
                        parseFloat(this.computeCsfScore('outcome'))
                    ) / 6

                return ave.toFixed(2)
            },
            computeCsfRatingAve() {

                const ave = (this.computeCsfRating('clarity') +
                    this.computeCsfRating('skills') +
                    this.computeCsfRating('professionalism') +
                    this.computeCsfRating('courtesy') +
                    this.computeCsfRating('response_time') +
                    this.computeCsfRating('outcome')) / 6
                return ave.toFixed(2)
            },

        }

    })
</script>