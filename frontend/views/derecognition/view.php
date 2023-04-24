<?php

use app\models\Books;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Derecognition */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Derecognitions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);


?>
<div class="derecognition-view panel">
    <p>
    <div class="row">
        <div class="col-sm-1">
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        </div>
        <div class="col-sm-2">
            <?= Select2::widget([
                'name' => 'book',
                'id' => 'book',
                'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                'pluginOptions' => [
                    'placeholder' => 'Select Book For JEV',
                ]
            ]) ?>
        </div>
        <div class="col-sm-1">
            <?= Html::a('Jev', ['jev-preparation/create', 'derecognitionId' => $model->id], ['class' => 'btn btn-warning', 'id' => 'tojev']) ?>

        </div>
    </div>


    </p>


    <table class="table" id="iirup_items">
        <thead>
            <th>Book</th>
            <th> Date Acquired</th>
            <th> Particulars/Articles</th>
            <th>Property Number</th>
            <th>Quantity</th>
            <th>Unit Cost</th>
            <th>Total Cost</th>
            <th>Accumulated Depreciation</th>
            <th>Carry Amount</th>
            <th>First Month of Depreciation</th>
            <th>Last Month of Depreciation</th>
            <th>NEW Last Month of Depreciation</th>
            <th>Monthly Depreciation</th>
        </thead>
        <tbody>
            <?php



            foreach ($propertyDetails as $itm) {


                echo "<tr>
                        <td>{$itm['book_name']}</td>
                        <td>{$itm['date_acquired']}</td>
                        <td><b>{$itm['article_name']}</b><br><i>{$itm['description']}</i></td>
                        <td>{$itm['property_number']}</td>
                        <td>1</td>
                        <td>" . number_format($itm['acquisition_amount'], 2) . "</td>
                        <td>" . number_format($itm['acquisition_amount'], 2) . "</td>
                        <td>" . number_format($itm['mnthly_depreciation'], 2) . "</td>
                        <td>" . number_format($itm['book_amt'], 2) . "</td>
                        <td>{$itm['strt_mnth']}</td>
                        <td>{$itm['lst_mth']}</td>
                        <td>{$itm['new_last_month']}</td>
                        <td>" . number_format($itm['mnthly_depreciation'], 2) . "</td>
                    </tr>";
            }

            ?>
        </tbody>
    </table>
</div>
<style>
    .panel {
        padding: 2rem;
    }

    th,
    td {
        text-align: center;
    }
</style>
<script>
    $(document).ready(() => {
        $("#book").change(() => {
            $('#tojev').attr('href', $('#tojev').attr('href') + '&bookId=' + $("#book").val())
        })

        $('#export').click(function(e) {
            e.preventDefault();
            $.ajax({
                container: "#employee",
                type: 'POST',
                url: window.location.pathname + '?r=report/export-property-database',
                data: {
                    id: 1,

                },
                success: function(data) {
                    var res = JSON.parse(data)
                    window.open(res)
                    $.ajax({
                        type: 'POST',
                        url: window.location.pathname + '?r=site/clear-exports',
                        success: function(response) {
                            console.log(response)
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log('Error deleting file: ' + errorThrown);
                        }
                    });
                }

            })
        })
    })
</script>