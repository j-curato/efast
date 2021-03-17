<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubAccounts2Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sub Accounts2s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-accounts2-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- <p>
        <?= Html::a('Create Sub Accounts2', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->


    <p>
        <?php
        // Html::a('Create Sub Accounts1', ['create'], ['class' => 'btn btn-success']) 

        ?>
        <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Upload</button>
    </p>
    <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">UPLOAD WFP</h4>
                </div>
                <div class='modal-body'>
                    <center><a href="jev/604b22cad30a6_sample_sub_account1_import.xlsx">Download Template Here to avoid error during Upload.</a></center>
                    <hr>
                    <label for="sub_account1"> SELECT SUB ACCOUNT 1</label>
                    <?php
                    $sub_account1 = Yii::$app->db->createCommand("SELECT sub_accounts1.id, CONCAT(sub_accounts1.object_code,' - ',sub_accounts1.name) as name FROM sub_accounts1")->queryAll();
                    ?>
                    <?php
                    $form = ActiveForm::begin([
                        'action' => ['sub-accounts2/import'],
                        'method' => 'POST',
                        'id' => 'formupload',
                        'options' => [
                            'enctype' => 'multipart/form-data',
                        ], // important
                    ]);
                    echo Select2::widget([
                        'data' => ArrayHelper::map($sub_account1, 'id', 'name'),
                        'id' => 'sub_account1',
                        'name' => 'sub_account1',
                        'options' => ['placeholder' => 'Select a Sub Account 1'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
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

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Sub Accounts 2',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [

            'sub_accounts1_id',
            'object_code',
            'name',
            // [
            //     'label' => 'Actions',
            //     'format' => 'raw',
            //     'value' => function ($model) {
            //         // $t = yii::$app->request->baseUrl . '/index.php?r=chart-of-accounts/update&id=' .
            //         return ' ' . Html::button('<span class="">Add</span>', [
            //             'class' => '"btn btn-info btn-xs add-sub',
            //             'data-toggle' => "collapse", 'data-target' => "#collapseExample",
            //             'aria-expanded' => "false", 'aria-controls' => "collapseExample",
            //             'value' => $model->id,

            //         ]);
            //     }
            // ],


            ['class' => 'yii\grid\ActionColumn'],
            
        ],
    ]); ?>


</div>