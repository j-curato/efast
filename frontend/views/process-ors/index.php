<?php

use app\components\helpers\MyHelper;
use kartik\date\DatePicker;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProccessOrsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$type_dis = strtoupper($type);
$this->title = 'Process ' . $type_dis;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="process-ors-index">


    <p>

        <?php
        if ($type === 'burs') {
            echo  Html::a('Create Process ' . $type_dis, ['create-burs'], ['class' => 'btn btn-success']);
        } else {
            echo Html::a('Create Process ' . $type_dis, ['create'], ['class' => 'btn btn-success']);
        }
        ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <!-- ANG MODEL ANI KAY SA PROCESS ORS ENTRIES -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'Process ' . $type_dis
        ],
        'toolbar' =>  [
            [
                'content' => "<form id='ors'>" .
                    DatePicker::widget([
                        'name' => 'year',
                        'options' => [
                            'style' => 'width:100px'
                        ],
                        'pluginOptions' => [
                            'format' => 'yyyy',
                            'startView' => 'years',
                            'minViewMode' => 'years',
                            'autoclose' => true
                        ]
                    ])
                    . '' .
                    Html::button('Export', ['type' => 'submit', 'class' => 'btn btn-success']) .
                    "</form>",
                'options' => ['class' => 'btn-group', 'style' => 'margin-right:20px;display:flex']
            ],

        ],
        'columns' => [

            'serial_number',
            'reporting_period',
            'date',
            'tracking_number',
            'particular',
            'r_center',
            'payee',
            'type',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id);
                }
            ],
        ],
    ]); ?>


</div>
<script>
    $(document).ready(() => {
        $('#ors').submit((e) => {
            e.preventDefault()
            $.ajax({
                type: 'POST',
                url: window.location.pathname + "?r=process-ors/export",
                data: $('#ors').serialize(),
                success: (data) => {
                    window.open(JSON.parse(data))
                }
            })

        })
    })
</script>