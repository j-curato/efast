<?php

use app\models\MajorAccounts;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\FileInput;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ChartOfAccountsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Chart Of Accounts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chart-of-accounts-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Add New', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=chart-of-accounts/create'), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>
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
                    <center><a href="WFP Template.xlsx">Download Template Here to avoid error during Upload.</a></center>
                    <hr>
                    <?php


                    $form = ActiveForm::begin([
                        'action' => ['chart-of-accounts/import'],
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

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
        'options' => ['id' => 'table-grid'],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Recommendation List',
            //'after'=>$after,0
        ],

        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [


            'id',

            [
                'label' => 'UACS',
                'attribute' => 'uacs',
            ],
            [
                'label' => 'General Ledger',
                'attribute' => 'general_ledger',
            ],
            [
                'label' => 'Account Group',
                'attribute' => 'account_group',
            ],

            [
                'label' => 'Object Code',
                'attribute' => 'majorAccount.object_code',
                'value' => 'majorAccount.object_code',

            ],

            [
                'label' => 'Major Account',
                'attribute' => 'major_account_id',
                'value' => 'majorAccount.name',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'major_account_id',
                    ArrayHelper::map(MajorAccounts::find()->asArray()->all(), 'id', 'name'),
                    ['class' => 'form-control', 'prompt' => 'Major Accounts']
                )

            ],
            [
                'label' => 'Object Code',
                'attribute' => 'subMajorAccount.object_code',
                'value' => 'subMajorAccount.name'

            ],
            [
                'label' => 'SUb Major Account',
                'attribute' => 'sub_major_account',
                'value' => 'subMajorAccount.name'

            ],
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $t = yii::$app->request->baseUrl . '/index.php?r=chart-of-accounts/update&id=' . $model->id;
                    return ' ' . Html::button('<span class="fa fa-pencil-square-o"></span>', ['value' => Url::to($t), 'class' => 'btn btn-primary btn-xs modalButtonedit']);
                }
            ]




        ],
    ]); ?>


</div>

<?php

$js = "
        $('#modalButtoncreate').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('.modalButtonedit').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });


";
$this->registerJs($js, $this::POS_END);
?>