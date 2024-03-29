<?php

use app\components\helpers\MyHelper;
use app\models\Books;
use app\models\JevAccountingEntriesSearch;
use kartik\date\DatePicker;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\widgets\FileInput;
use kartik\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'JEV';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">


  <p>
    <?= Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success']) ?>
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
          <center><a href="/afms/frontend/web/jev/jev_format.xlsx">Download Template Here to avoid error during Upload.</a></center>
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

  // $reference  = Yii::$app->db->createCommand("SELECT jev_preparation.ref_number  as `reference`

  // FROM jev_preparation
  // WHERE jev_preparation.ref_number  !='' AND  jev_preparation.ref_number IS NOT NULL
  // GROUP BY
  // jev_preparation.ref_number")->queryAll();


  $reference = [
    'ADADJ' => 'ADADJ',
    'CDJ' => 'CDJ',
    'CKDJ' => 'CKDJ',
    'CRJ' => 'CRJ',
    'GJ' => 'GJ',
  ];

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
    'pjax' => true,
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
      [
        'attribute' => 'reporting_period',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
          'attribute' => 'reporting_period',
          'readonly' => true,
          'pluginOptions' =>
          [
            'allowClear' => true,
            'format' => 'yyyy-mm',
            'minViewMode' => 'months',
            'autoclose' => true

          ],
        ],

      ],
      'jev_number',

      [
        'label' => 'Payee',
        'attribute' => 'payee',
      ],
      [
        'label' => 'Particular',
        'attribute' => 'explaination',
        'options' => [
          'width' => '50',
        ]
      ],
      'dv_number',
      [
        'label' => 'Reference',
        'attribute' => 'reference_type',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $reference,
        'filterWidgetOptions' => [
          'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Select Reference', 'multiple' => false],
        'format' => 'raw'
      ],

      [
        'label' => 'Book',
        'attribute' => 'book_name',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Books::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
        'filterWidgetOptions' => [
          'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Select Book', 'multiple' => false],
        'format' => 'raw'
      ],

      [
        'attribute' => 'date',
        'value' => 'date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
          'attribute' => 'date',

          'pluginOptions' =>
          [
            'allowClear' => true,
            'format' => 'yyyy-mm-dd',
            'autoclose' => true,


          ],
        ],

      ],

      'check_ada',
      'entry_type',


      [
        'label' => 'Actions',
        'format' => 'raw',
        'value' => function ($model) {
          return MyHelper::gridDefaultAction($model->id,'');
        }
      ],

    ],
  ]); ?>
  <style>
    .grid-view td {
      white-space: normal;
      width: 5rem;
      padding: 5px;
      font-size: 12px;
    }
  </style>


</div>
<?php

$script = <<< JS
      
      $('#mdModal').click(function(){
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
                .html('<span class=`fa fa-times`></span>');
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