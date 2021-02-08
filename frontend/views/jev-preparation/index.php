<?php

use app\models\FundClusterCode;
use app\models\ResponsibilityCenter;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jev Preparations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">

  <h1><?= Html::encode($this->title) ?></h1>

  <p>
    <?= Html::a('Create Jev Preparation', ['create'], ['class' => 'btn btn-success']) ?>
  </p>

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
      ['class' => 'yii\grid\SerialColumn'],

      'id',
      // 'responsibility_center_id'
      'jev_number',
      'dv_number',
      [
        'label' => 'Responsibility Center',
        'attribute' => 'responsibility_center_id',
        'value' => 'responsibilityCenter.name',
        'filter' => Html::activeDropDownList(
          $searchModel,
          'responsibility_center_id',
          ArrayHelper::map(ResponsibilityCenter::find()->asArray()->all(), 'id', 'name'),
          ['class' => 'form-control', 'prompt' => 'Responsibility Centers']
        )

      ],
      [
        'label' => 'Fund Cluster Code',
        'attribute' => 'fund_cluster_code_id',
        'value' => 'fundClusterCode.name',
        'filter' => Html::activeDropDownList(
          $searchModel,
          'responsibility_center_id',
          ArrayHelper::map(FundClusterCode::find()->asArray()->all(), 'id', 'name'),
          ['class' => 'form-control', 'prompt' => 'Responsibility Centers']
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