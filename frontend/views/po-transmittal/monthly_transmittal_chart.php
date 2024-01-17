<?php


/* @var $this yii\web\View */

use yii\helpers\Url;
use app\models\Office;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = 'Dashboard';
?>
<?= \yii\helpers\Html::csrfMetaTags() ?>
<div class="site-index p-0" id="mainVue">
    <div class="card-header border-0">
        <div class="d-flex justify-content-between">
            <h3 class="card-title">PO DV Transmittals</h3>
            <div>
                <?= Html::a(
                    '<i class="fa fa-question-circle"></i>Help',
                    '@web/module_guides/po_dv_transmittal_summary.pdf',
                    ['target' => '_blank', 'class' => '']
                ) ?>
            </div>

        </div>
    </div>
    <div class="card-body">
        <form @submit.prevent="filter">
            <div class="row">
                <?php if (YIi::$app->user->can('ro_accounting_admin')) : ?>
                    <div class="col-5">
                        <label for="bar_filter">Office</label>
                        <?= Select2::widget([
                            'id' => 'office_id',
                            'name' => 'office_id',
                            'data' => ArrayHelper::map(Office::getOfficesA(), 'id', 'office_name'),
                            'pluginOptions' => [
                                'placeholder' => 'Select Office'
                            ]
                        ]) ?>
                    </div>
                <?php endif; ?>
                <div col-5>
                    <label for="bar_filter">Year</label>
                    <?= DatePicker::widget([
                        'id' => 'year',
                        'name' => 'year',
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy',
                            'minViewMode' => 'years',
                            'placeholder' => 'Select Year'

                        ]
                    ]);
                    ?>
                </div>
                <div class="col-1 pt-4 mt-2">
                    <button class="btn btn-success" type="submit">Filter</button>
                </div>
            </div>
        </form>
        <div id="poTransmittalChartContainer">
            <canvas id="poTransmittalChart"></canvas>
        </div>
    </div>

</div>


<?php
$csrfToken = Yii::$app->request->getCsrfToken();
$monthlyTransmittalListUrl = Url::toRoute(['po-transmittal/monthly-transmittal-list']);
?>

<script>
    const csrfToken = '<?= $csrfToken ?>';

    let extract = (data, col) => {
        return data.map(item => item[col])
    }

    $(document).ready(function() {
        new Vue({
            el: "#mainVue",
            data: {
                transmittalData: [],
                year: ''
            },
            mounted() {
                this.filter()
            },
            methods: {
                async filter() {
                    this.year = $("#year").val()
                    const url = window.location.pathname + "?r=po-transmittal/monthly-transmittal-count"
                    const data = {
                        year: this.year,

                        office_id: $("#office_id").val(),
                        _csrf: "<?= $csrfToken ?>"
                    }
                    await axios.post(url, data)
                        .then(res => {
                            this.transmittalData = res.data
                        })
                        .catch(err => {
                            console.log(err)
                        })
                    this.barChart()
                },
                barChart(year = '') {
                    // document.getElementById("poTransmittalChartContainer").innerHTML = '&nbsp;';
                    document.getElementById("poTransmittalChartContainer").innerHTML = '<canvas id="poTransmittalChart"></canvas>';
                    const ctx = document.getElementById('poTransmittalChart').getContext('2d');
                    const myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: extract(this.transmittalData, 'reporting_period'),
                            datasets: [{
                                    label: '# of DV`s',
                                    data: extract(this.transmittalData, 'total_dvs'),
                                    backgroundColor: '#668cff',
                                    borderColor: 'rgb(0, 82, 204)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'DVs Not Transmitted',
                                    data: extract(this.transmittalData, 'total_untransmitted_dvs'),
                                    backgroundColor: '#ff8080',
                                    borderColor: 'rgb(179, 0, 0)',
                                    borderWidth: 1
                                },
                                {
                                    label: '# of Dv Pending at RO',
                                    data: extract(this.transmittalData, 'total_dvs_pending_at_ro'),
                                    backgroundColor: '#f6fa1b',
                                    borderColor: '#e0e336',
                                    borderWidth: 1
                                },
                                {
                                    label: '# of Dv  at RO',
                                    data: extract(this.transmittalData, 'total_dvs_at_ro'),
                                    backgroundColor: '#17a2b8',
                                    borderColor: '#17a2b8',
                                    borderWidth: 1
                                },
                                {
                                    label: '# of Dv  at COA',
                                    data: extract(this.transmittalData, 'total_dvs_at_coa'),
                                    backgroundColor: '#4aed75',
                                    borderColor: '#32a852',
                                    borderWidth: 1
                                },
                            ]
                        },
                        options: {
                            // This chart will not respond to mousemove, etc
                            onClick(e) {
                                const activePoints = myChart.getElementsAtEvent(e)[0];

                                if (activePoints != undefined) {

                                    // console.log(myChart.data)
                                    // console.log(myChart.data.datasets[activePoints._index])
                                    // console.log(myChart.data.labels[activePoints._index])
                                    const reporting_period = myChart.data.labels[activePoints._index]
                                    let url = "<?= $monthlyTransmittalListUrl ?>" + `&reportingPeriod=${reporting_period}`
                                    if ($("#office_id").length) {
                                        console.log('qwe')
                                        url = url + "&officeId=" + $("#office_id").val()
                                    }
                                    console.log(url)
                                    window.location.href = url
                                }
                            }
                        }

                    });


                }
            }
        })
    })
</script>