<?php


use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="jev-preparation-index d-none " style="background-color: white;padding:20px" id="main">
    <form id="rao" @submit.prevent="exportDvs">
        <div class="row">
            <div class="col-sm-2">
                <label for="year">Export Detailed DV Year</label>
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
                <button class="btn export-button" id="generate">
                    <div class="row">
                        <div class="col-2 text-left  ">
                            <i class="fas fa-file-excel text-success" v-show='!loading'></i>
                            <div class="loader " v-show='loading' style="margin-top: 4px; margin-right:100px"></div>
                        </div>
                        <div class="col-2 text-right">
                            Export
                        </div>
                    </div>
                </button>
            </div>
            <div class="col-sm-1">
                <div class=" center-container" v-show='loading'>

                    <!-- <pulse-loader :loading="loading" :color="color" :size="size"></pulse-loader> -->
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

    .loader {
        width: 18px;
        padding: 2px;
        aspect-ratio: 1;
        border-radius: 50%;
        background: #A3A3AD;
        --_m:
            conic-gradient(#0000 10%, #000),
            linear-gradient(#000 0 0) content-box;
        -webkit-mask: var(--_m);
        mask: var(--_m);
        -webkit-mask-composite: source-out;
        mask-composite: subtract;
        animation: l3 1s infinite linear;
    }

    @keyframes l3 {
        to {
            transform: rotate(1turn)
        }
    }
</style>
<?php
$csrf = YIi::$app->request->csrfToken;
$this->registerJsFile('@web/js/vue-spinner.min.js', ['position' => $this::POS_HEAD]);
?>
<script>
    var PulseLoader = VueSpinner.PulseLoader
    var RingLoader = VueSpinner.RingLoader
    $(document).ready(function() {
        $("#main").removeClass("d-none")
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
                yr: ''

            },

            methods: {
                exportDvs() {

                    this.showTable = false
                    const url = window.location.pathname + '?r=dv-aucs/export-detailed-dv'

                    this.yr = $("#year").val()
                    const data = {
                        year: this.yr,
                        _csrf: '<?= $csrf ?>'
                    }
                    if (this.yr) {
                        this.loading = true
                        const response = axios.post(url, data)
                            .then((response) => {
                                setTimeout(() => {
                                    this.exportToExcel(response.data)
                                }, 800)
                            }).catch((error) => {
                                console.log(error)
                                this.loading = false
                            })
                    }

                },
                exportToExcel(jsonData) {
                    // Your JSON data
                    // Create a new workbook
                    let wb = XLSX.utils.book_new();
                    // Add a worksheet to the workbook
                    let ws = XLSX.utils.json_to_sheet(jsonData);
                    // Add the worksheet to the workbook
                    XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
                    // Save the workbook as an Excel file and trigger download
                    XLSX.writeFile(wb, this.yr + " Detailed DV .xlsx");
                    this.loading = false
                }

            },

        })
    })
</script>