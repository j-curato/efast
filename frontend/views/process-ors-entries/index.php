<?php

use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProcessOrsEntriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Process Ors Entries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="process-ors-entries-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i>Create Process Ors', ['create'], ['class' => 'btn btn-success']) ?>
    <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Import</button>
    </p>
    <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">UPLOAD WFP</h4>
                </div>
                <div class='modal-body'>
                    <center><a href="sub_account1/sub_account1_format.xlsx">Download Template Here to avoid error during Upload.</a></center>
                    <hr>
                    <label for="ledger"> SELECT GENERAL LEDGER</label>
                    <?php
                    $ledger = Yii::$app->db->createCommand("SELECT chart_of_accounts.id, CONCAT(chart_of_accounts.uacs,' - ',chart_of_accounts.general_ledger) as name FROM chart_of_accounts")->queryAll();
                    ?>
                    <?php

                    $form = ActiveForm::begin([
                        'action' => ['process-ors-entries/import'],
                        'method' => 'POST',
                        'id' => 'import',
                        'options' => [
                            'enctype' => 'multipart/form-data',
                        ], // important
                    ]);

                    // echo '<input type="file">';
                    echo "<br>";
                    echo FileInput::widget([
                        'name' => 'file',
                        // 'options' => ['multiple' => true],
                        'id' => 'fileupload',
                        'pluginOptions' => [
                            'showPreview' => true,
                            'showCaption' => true,
                            'showRemove' => true,
                            'showUpload' => true,
                        ]
                    ]);


                    ActiveForm::end();

                    ?>

                </div>
            </div>
        </div>
    </div>

    <!-- RAOUDS ANG MODEL ANI. TRIP KO LANG -->
    <!-- NAA SA PROCESS ORS ENTRIES CONTROLLER SA INDEX NAKO GE CHANGE -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'heading' => '<h3 class="panel-title"> Process Ors</h3>',
            'type' => 'primary',
            // 'before' => Html::a('<i class="glyphicon glyphicon-plus"></i>Create Process Ors', ['create'], ['class' => 'btn btn-success']),
            'exportConfig' => [
                ExportMenu::FORMAT_TEXT => false,
                ExportMenu::FORMAT_PDF => false
            ],
        ],


        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'reporting_period',
            // 'raoudEntries.chartOfAccount.general_ledger',
            [
                'label' => 'General Ledger',
                'value' => 'raoudEntries.chartOfAccount.general_ledger',
                // 'value' => 'processOrs.reporting_period'
            ],
            [
                'label' => 'Serial Number',
                'attribute' => 'processOrs.serial_number',
                // 'value' => 'processOrs.reporting_period'
            ],
            [
                'label' => 'Amount',
                'attribute' => 'raoudEntries.amount',
                'format' => ['decimal', 2]
            ],
            [
                'label' => 'Adjust Amount',
                'value' => function ($model) {
                    $query = Yii::$app->db->createCommand("SELECT SUM(raoud_entries.amount) as total
                    FROM `raouds`,raoud_entries
                    WHERE raouds.id=raoud_entries.raoud_id
                    AND raoud_entries.amount >0
                    AND raoud_entries.parent_id_from_raoud = $model->id
                     ")->queryOne();
                    if (!empty($query['total'])) {
                        return $query['total'];
                    } else {
                        return '';
                    }
                },
                'format' => ['decimal', 2]
            ],

            [
                'label' => 'Adjust',
                'format' => 'raw',
                'value' => function ($model) {

                    $query = Yii::$app->db->createCommand("SELECT SUM(raoud_entries.amount) as total
                    FROM `raouds`,raoud_entries
                    WHERE raouds.id=raoud_entries.raoud_id
                    AND raoud_entries.amount >0
                    AND raoud_entries.parent_id_from_raoud = $model->id
                     ")->queryOne();
                    $amount = $model->raoudEntries->amount;
                    if ($query['total'] < $amount  && $amount > 0) {

                        $t = yii::$app->request->baseUrl . "/index.php?r=process-ors-entries/adjust&id=$model->id";
                        return ' ' . Html::a('', $t, ['class' => 'btn-xs btn-secondary fa fa-pencil-square-o']);
                    } else {
                        return "";
                    }
                    // return $query['total'];
                }
            ],
            // [
            //     'label' => 'Adjust',
            //     'format' => 'raw',
            //     'value' => function ($model) {



            //             $t = yii::$app->request->baseUrl . "/index.php?r=process-ors/view&id=$model->process_ors_id";
            //             return ' ' . Html::a('', $t, ['class' => 'btn-xs btn-success fa fa-pencil-square-o']);

            //             // return $query['total'];
            //     }
            // ],


            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>