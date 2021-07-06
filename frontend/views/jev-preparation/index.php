<?php

use app\models\Books;
use app\models\FundClusterCode;
use app\models\JevAccountingEntries;
use app\models\JevAccountingEntriesSearch;
use app\models\JevPreparation;
use app\models\ResponsibilityCenter;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\widgets\FileInput;
use kartik\widgets\ActiveForm;
use yii\widgets\Pjax;

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
    'jevPreparation.dv_number',
    'jevPreparation.check_ada_number',
    [
      'label' => 'Book',
      'value' => 'jevPreparation.books.name'
    ],
    [
      'label' => 'Payee',
      'value' => 'jevPreparation.payee.account_name'
    ],
    'chartOfAccount.uacs',
    'chartOfAccount.general_ledger',
    [
      'label' => 'Entry Object Code',
      'value' => function ($model) {
        return $model->object_code;
      }
    ],
    [
      'label' => 'Entry General Ledger',
      'value' => function ($model) {
        if ($model->lvl === 1) {
          return $model->chartOfAccount->general_ledger;
        } else if ($model->lvl === 2) {
          $query = (new \yii\db\Query(0))->select('sub_accounts1.name')->from('sub_accounts1')->where('sub_accounts1.object_code =:object_code', ['object_code' => $model->object_code])->one();
          return $query['name'];
        } else if ($model->lvl === 3) {
          $query = (new \yii\db\Query(0))->select('sub_accounts2.name')->from('sub_accounts2')->where('sub_accounts2.object_code =:object_code', ['object_code' => $model->object_code])->one();
          return $query['name'];
        }
      }

    ],
    'jevPreparation.reporting_period',
    'jevPreparation.date',
    'jevPreparation.explaination',
    'debit',
    'credit',
    'jevPreparation.ref_number',


  ];
  $dataProvider->pagination = ['pageSize' => 10];
  // echo ExportMenu::widget([
  //   'dataProvider' => $w,
  //   'columns' => $gridColumn,
  //   'filename' => 'Jev',
  //   'exportConfig' => [
  //     ExportMenu::FORMAT_TEXT => false,
  //     ExportMenu::FORMAT_PDF => false,
  //     ExportMenu::FORMAT_EXCEL => false,
  //     ExportMenu::FORMAT_HTML => false,
  //   ]

  // ]);

  ?>

  <?php Pjax::begin(['id' => 'employee', 'clientOptions' => ['method' => 'POST']]) ?>
  <?php Pjax::end() ?>

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
    'toolbar' =>  [
      [
        'content' =>
        // ExportMenu::widget([
        //   'dataProvider' => $w,
        //   'columns' => $gridColumn,
        //   'filename' => 'Jev',
        //   'exportConfig' => [
        //     ExportMenu::FORMAT_TEXT => false,
        //     ExportMenu::FORMAT_PDF => false,
        //     ExportMenu::FORMAT_EXCEL => false,
        //     ExportMenu::FORMAT_HTML => false,
        //   ]

        // ])

        DatePicker::widget([
          'name' => 'year',
          'id' => 'year',
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
          "<button class='btn btn-primary' id='export'>Export</button>",
        'options' => ['class' => 'btn-group', 'style' => 'margin-right:20px;display:flex']
      ],

    ],
    'toggleDataContainer' => ['class' => 'btn-group mr-2'],
    'columns' => [
      // ['class' => 'yii\grid\SerialColumn'],

      'id',
      'jev_number',
      [
        'label' => 'Payee',
        'attribute' => 'payee_id',
        'value' => 'payee.account_name'
      ],
      // 'transaction_id',
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
      font-size: 12px;
    }
  </style>


</div>
<?php

$script = <<< JS
      
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

      $('#export').click(function(){

        $.pjax({container: "#employee", 
        url: window.location.pathname + '?r=jev-preparation/export-jev',
        type:'POST',
        data:{
            year:$('#year').val(),
            
        },
        })
        ;
      })

JS;
$this->registerJs($script);
?>