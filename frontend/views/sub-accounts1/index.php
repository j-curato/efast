<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubAccounts1Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sub Accounts1s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-accounts1-index">


    <p>
        <?php
        // Html::a('Create Sub Accounts1', ['create'], ['class' => 'btn btn-success']) 

        ?>
        <!-- <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Upload</button> -->
    </p>
    <!-- <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                        'action' => ['sub-accounts1/import'],
                        'method' => 'POST',
                        'id' => 'formupload',
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
    </div> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

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
            'object_code',
            [

                'label' => 'Account Title',
                'attribute' => 'name',
                'options' => [
                    'style' => 'word-wrap: break-word; width: 100px'
                ]
            ],


            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn  = Yii::$app->user->can('update_sub_account_1') ?  Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate']) : '';
                    $createSubAccount2Btn  = Yii::$app->user->can('create_sub_account_2') ?  Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate']) : '';
                    return $createSubAccount2Btn . ' ' .
                        Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id])
                        . ' ' . $updateBtn;
                }
            ]
        ],
    ]); ?>

</div>

<?php
$this->registerJsFile(
    '@web/frontend/web/js/globalFunctions.js',
    [
        'depends' => [\yii\web\JqueryAsset::class]
    ]
)
?>