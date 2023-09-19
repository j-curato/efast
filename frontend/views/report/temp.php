<?php

use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CashDisbursementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cash Disbursements';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-disbursement-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Import</button>
    </p>

    <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">UPLOAD Cash Disbursement</h4>
                </div>
                <div class='modal-body'>
                    <center><a href="import_formats/Cash_Disbursement and DV Format.xlsx">Download Template Here to avoid error during Upload.</a></center>
                    <hr>
                    <?php


                    $form = ActiveForm::begin([
                        'action' => ['report/temp-import'],
                        'method' => 'post',
                        'id' => 'formupload',
                        'options' => [
                            'enctype' => 'multipart/form-data',
                        ], // important
                    ]);
                    // echo '<input type="file">';
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



    <style>
        .grid-view td {
            white-space: normal;
            width: 10rem;
            padding: 0;
        }
    </style>
</div>