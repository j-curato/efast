<?php

use app\components\helpers\MyHelper;
use app\models\DvAucsEntriesSearch;
use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DvAucsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'No File Links DVs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dv-aucs-index">


    <?php

    $cols =  [
        'dv_number',
        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {

                return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id], ['class' => 'btn'])
                    . Html::a('<i class="fa fa-pencil "></i>', ['add-link', 'id' => $model->id], ['class' => 'btn mdModal']);
            }
        ]


    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of DV',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'toolbar' => [


            [
                'content' => ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $cols,
                    'filename' => "DV",
                    'batchSize' => 10,
                    'stream' => false,
                    'target' => '_popup',

                    'exportConfig' => [
                        ExportMenu::FORMAT_CSV => false,
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_HTML => false,
                        ExportMenu::FORMAT_EXCEL => false,

                    ]

                ]),
                'options' => [
                    'class' => 'btn-group mr-2', 'style' => 'margin-right:20px'
                ]
            ]
        ],
        'pjax' => true,
        'export' => false,
        'columns' => $cols
    ]); ?>


    <style>
        .grid-view td {
            white-space: normal;
            width: 5rem;
            padding: 0;
        }
    </style>
</div>
<?php


?>
<script>
    $(document).ready(function() {
        $('#add_link').submit((e) => {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=dv-aucs/add-link',
                data: $("#add_link").serialize(),
                success: function(data) {
                    $('#uploadmodal').modal('toggle');
                    var res = JSON.parse(data)

                    if (res.isSuccess) {
                        swal({
                            title: 'Success',
                            type: 'success',
                            button: false,
                            timer: 3000,
                        }, function() {
                            location.reload(true)
                        })
                    } else {
                        swal({
                            title: "Error Adding Fail",
                            type: 'error',
                            button: false,
                            timer: 3000,
                        })
                    }
                }
            })
        })
    })
</script>