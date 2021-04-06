<?php

use app\models\Books;
use app\models\FundClusterCode;
use app\models\JevAccountingEntries;
use app\models\JevAccountingEntriesSearch;
use app\models\JevPreparation;
use app\models\ResponsibilityCenter;
use kartik\export\ExportMenu;
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
          <center><a href="jev/jev_format.xlsx">Download Template Here to avoid error during Upload.</a></center>
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
  $q = new JevAccountingEntriesSearch();
  $w = $q->search(Yii::$app->request->queryParams);
  $gridColumn = [
    'jevPreparation.jev_number',
    'chartOfAccount.uacs',
    'chartOfAccount.general_ledger',
    'jevPreparation.reporting_period',
    'jevPreparation.date',
    'jevPreparation.explaination',
    'debit',
    'credit',
    'jevPreparation.ref_number'

  ];

  echo ExportMenu::widget([
    'dataProvider' => $w,
    'columns' => $gridColumn,
    'filename' => 'Jev',
    'exportConfig' => [
      ExportMenu::FORMAT_TEXT => false,
      ExportMenu::FORMAT_PDF => false,
      ExportMenu::FORMAT_EXCEL => false,
      ExportMenu::FORMAT_HTML => false,
    ]

  ]);

  ?>



  <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'panel' => [
      'type' => GridView::TYPE_PRIMARY,
      // 'heading' => 'List of Areas',
    ],
    'export' => false,
    'floatHeaderOptions' => [
      'top' => 50,
      'position' => 'absolute',

    ],
    'columns' => [
      // ['class' => 'yii\grid\SerialColumn'],

      'id',
      'jev_number',
      [
        'label' => 'Particular',
        'attribute' => 'explaination',
        'options' => [
          'width' => '50',
        ]
      ],


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
        'attribute' => 'book_id',
        'value' => 'books.name',
        'filter' => Html::activeDropDownList(
          $searchModel,
          'book_id',
          ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
          ['class' => 'form-control', 'prompt' => 'Books']
        )

      ],


      'reporting_period',
      'date',
      // 'check_ada_number',
      'dv_number',
      'lddap_number',
      'check_ada_number',



      // 'lddap_number',
      // 'entity_name',


      ['class' => 'yii\grid\ActionColumn'],

    ],
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

$script = <<< JS
      



    
     $(document).ready(function() {
      // $("#formupload").submit(function(){
      //   $.ajax({
      //     url:window.location.pathname +'jev-preparation/import',
      //     method:'POST',
      //     success:function(data){
      //       console.log(data)
      //     }
      //   })
      // })
      
        })

    JS;
$this->registerJs($script);
?>

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