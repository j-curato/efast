<?php


use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "RAO";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index " style="background-color: white;padding:20px" id="main">



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
        <div class="col-1 pt-4">
            <button class="btn export-button" id="generate" @click="exportRao">
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

    </div>

    <!-- <div class=" center-container" v-show='loading'>
        <pulse-loader :loading="loading" :color="color" :size="size"></pulse-loader>
    </div> -->

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

                raoData: [],
                loading: false


            },
            computed: {},
            methods: {

                exportRao() {
                    this.loading = true
                    // this.showTable = false
                    const url = window.location.href
                    const data = {
                        year: $("#year").val(),
                        _csrf: '<?= $csrf ?>'
                    }
                    const response = axios.post(url, data)

                        .then((response) => {
                            this.raoData = response.data
                            this.exportToExcel()
                        }).catch((error) => {
                            console.log(error)
                        })
                },
                exportToExcel() {

                    // Create a new workbook
                    var wb = XLSX.utils.book_new();

                    // Add a worksheet to the workbook
                    var ws = XLSX.utils.json_to_sheet(this.raoData);

                    // Add the worksheet to the workbook
                    XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

                    // Save the workbook as an Excel file and trigger download
                    XLSX.writeFile(wb, $("#year").val() + " RAO.xlsx");
                    this.loading = false
                }

            },

        })
    })
</script>