<?php

use app\models\FundClusterCode;
use app\models\JevPreparation;
use app\models\ResponsibilityCenter;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\widgets\FileInput;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jev Preparations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">

  <h1><?= Html::encode($this->title) ?></h1>

  <p>
    <?= Html::a('Create Jev ', ['create'], ['class' => 'btn btn-success']) ?>
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
            'action' => ['jev-preparation/import'],
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
  <div>


  </div>

  <?php // echo $this->render('_search', ['model' => $searchModel]); 

  $ref_number  = JevPreparation::find()->select('ref_number')->distinct()->asArray()->all();

  // echo "<pre>";
  // var_dump($ref_number);
  // echo "</pre>";
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
      // ['class' => 'yii\grid\SerialColumn'],

      'id',
      'jev_number',
      // 'responsibility_center_id'
      // 'jev_number',
      // 'dv_number',
      // [
      //   'label' => 'Responsibility Center',
      //   'attribute' => 'responsibility_center_id',
      //   'value' => 'responsibilityCenter.name',
      //   'filter' => Html::activeDropDownList(
      //     $searchModel,
      //     'responsibility_center_id',
      //     ArrayHelper::map(ResponsibilityCenter::find()->asArray()->all(), 'id', 'name'),
      //     ['class' => 'form-control', 'prompt' => 'Responsibility Centers']
      //   )

      // ],
      [
        'label' => 'Reference Number',
        'attribute' => 'ref_number',
        // 'filter' => Html::activeDropDownList(
        //   $searchModel,
        //   'ref_number',
        //   ArrayHelper::getColumn($ref_number, 'ref_number'),
        //   ['class' => 'form-control', 'prompt' => 'Fund Cluster Code']
        // )
      ],

      [
        'label' => 'Fund Cluster Code',
        'attribute' => 'fund_cluster_code_id',
        'value' => 'fundClusterCode.name',
        'filter' => Html::activeDropDownList(
          $searchModel,
          'fund_cluster_code_id',
          ArrayHelper::map(FundClusterCode::find()->asArray()->all(), 'id', 'name'),
          ['class' => 'form-control', 'prompt' => 'Fund Cluster Code']
        )

      ],


      'reporting_period',
      'date',


      // 'lddap_number',
      // 'entity_name',
      // 'explaination',

      ['class' => 'yii\grid\ActionColumn'],
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
        $(function() {
            $(document).on('click', '.btn-add', function(e) {
              e.preventDefault();
          
              var dynaForm = $('.dynamic-wrap form:first'),
                currentEntry = $(this).parents('.entry:first'),
                newEntry = $(currentEntry.clone()).appendTo(dynaForm);
          
              newEntry.find('input').val('');
              dynaForm.find('.entry:not(:last) .btn-add')
                .removeClass('btn-add').addClass('btn-remove')
                .removeClass('btn-success').addClass('btn-danger')
                .html('<span class=`glyphicon glyphicon-minus`></span>');
            }).on('click', '.btn-remove', function(e) {
              $(this).parents('.entry:first').remove();
          
              e.preventDefault();
              return false;
            });
          });

";
$this->registerJs($js, $this::POS_END);
?>