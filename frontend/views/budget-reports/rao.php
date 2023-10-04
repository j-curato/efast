<?php


use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "RAO";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index " style="background-color: white;padding:20px" id="main">



    <form id="rao" @submit.prevent="filterForm">
        <div class="row">
            <div class="col-sm-2">
                <label for="year">Allotment Year</label>
                <?php
                echo DatePicker::widget([
                    'name' => 'year',
                    'id' => 'year',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'placeholder' => 'Select Year',
                        'format' => 'yyyy',
                        'minViewMode' => 'years'
                    ]
                ]);

                ?>
            </div>
            <div class="col-sm-2" style="margin-top: 2.5rem;">
                <button class="btn btn-primary" id="generate">Export</button>
            </div>

        </div>
    </form>

    <div class=" center-container" v-show='loading'>
        <pulse-loader :loading="loading" :color="color" :size="size"></pulse-loader>
    </div>


</div>


<style>
    .center-container {
        display: flex;
        justify-content: center;
        /* Horizontally center */
        align-items: center;
        /* Vertically center */
        height: 70vh;
        /* 100% of the viewport height */
    }
</style>
<script>
    // $(document).ready(() => {
    //     $('#rao').submit((e) => {
    //         e.preventDefault()
    //         $.ajax({
    //             type: 'POST',
    //             url: window.location.href,
    //             data: $('#rao').serialize(),
    //             success: (data) => {
    //                 window.open(JSON.parse(data))
    //             }
    //         })

    //     })
    // })
</script>
<?php
$csrf = YIi::$app->request->csrfToken;
$this->registerJsFile('@web/js/vue-spinner.min.js', ['position' => $this::POS_HEAD]);
?>
<script>
    var PulseLoader = VueSpinner.PulseLoader
    var RingLoader = VueSpinner.RingLoader
    $(document).ready(function() {
        new Vue({
            el: '#main',
            components: {
                'PulseLoader': PulseLoader,
                'RingLoader': RingLoader,
            },
            data: {
                finalData: [],
                year: '',
                personelServicesLists: [],
                mooeList: [],
                capitalOutlayList: [],
                loading: false,
                color: '#03befc',
                size: '20px',
                showTable: false,
                grandTotals: [],
                mooeCoTotal: [],


            },
            computed: {},
            methods: {

                filterForm() {
                    this.loading = true
                    this.showTable = false
                    const url = window.location.href
                    const data = {
                        year: $("#year").val(),
                        _csrf: '<?= $csrf ?>'
                    }
                    const response = axios.post(url, data)
                        .then((response) => {
                            setTimeout(() => {
                                this.loading = false
                                window.open(response.data)
                                setTimeout(() => {
                                    this.showTable = true
                                }, 100)
                            }, 800)
                        }).catch((error) => {
                            console.log(error)
                        })
                },

            },

        })
    })
</script>