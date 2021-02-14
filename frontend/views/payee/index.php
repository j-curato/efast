<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PayeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payee-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Payee', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal"> upload</button>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">UPLOAD WFP</h4>
                </div>
                <div class='modal-body'>
                    <center><a href="WFP Template.xlsx">Download Template Here to avoid error during Upload.</a></center>
                    <hr>
                    <?php


                    $form = ActiveForm::begin([
                        'action' => ['payee/import'],
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


</div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'panel' => [
        'type' => GridView::TYPE_PRIMARY,
        'heading' => 'List of Areas',
    ],
    'floatHeaderOptions' => [
        'top' => 50,
        'position' => 'absolute',
    ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        'account_name',
        'registered_name',
        'contact_person',
        'registered_address',
        //'contact',
        //'remark',
        //'tin_number',

        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>


</div>