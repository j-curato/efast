<?php


use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "RAO";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index " style="background-color: white;padding:20px">



    <form id="rao">
        <div class="row">

            <div class="col-sm-2">
                <label for="year">Allotment Year</label>
                <?php
                echo DatePicker::widget([
                    'name' => 'year',
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




</div>



<script>
    $(document).ready(() => {
        $('#rao').submit((e) => {
            e.preventDefault()

            $.ajax({
                type: 'POST',
                url: window.location.href,
                data: $('#rao').serialize(),
                success: (data) => {
                    window.open(JSON.parse(data))
                }
            })

        })
    })
</script>