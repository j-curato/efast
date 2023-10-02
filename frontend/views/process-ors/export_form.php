<?php


use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div style="background-color: white;padding:20px" id="main">


    <form id="rao" @submit.prevent="exportOrs">
        <div class="row">
            <div class="col-sm-2">
                <label for="year">Export Detailed <?= strtoupper($orsType) ?> Year</label>
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
            <div class="col-sm-1" style="margin-top: 2.05rem;">
                <button class="btn export-button" id="generate"> <i class="fas fa-file-excel text-success"></i> Export</button>
            </div>
            <div class="col-sm-1">
                <div class=" center-container" v-show='loading'>
                    <pulse-loader :loading="loading" :color="color" :size="size"></pulse-loader>
                </div>
            </div>
        </div>
    </form>
</div>


<style>
    .center-container {
        margin-top: 2.05rem;
    }

    .export-button:hover {
        background-color: #c3c7c9;
        /* Change this to the desired hover color */
        color: white;
        /* Change the text color if needed */
    }
</style>
<?php
$csrf = Yii::$app->request->csrfToken;
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

                loading: false,
                color: '#03befc',
                size: '20px',
                orsType: '<?= $orsType ?>',

            },

            methods: {
                exportOrs() {
                    this.loading = true
                    this.showTable = false
                    const url = window.location.pathname + '?r=process-ors/export'
                    const data = {
                        year: $("#year").val(),
                        type: this.orsType,
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