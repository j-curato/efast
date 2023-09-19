<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
// use kartik\select2\Select2;
use yii\bootstrap4\Modal;
use yii\widgets\Pjax;


// use yii\helpers\Html;
// use yii\helpers\ArrayHelper;
// use yii\widgets\ActiveForm;
// use kartik\widgets\FileInput;
// use kartik\date\DatePicker;
// use kartik\select2\Select2;
// use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model frontend\models\hre\HreMemoAth */

// $this->title = $model->id;
$this->title = 'Staff';
$this->params['breadcrumbs'][] = ['label' => 'Hre Memo Aths', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="hre-memo-ath-index">



    
    <?= $this->render('viewstaffform', [
        'model' => $model,
        // 'id'=>78
    ]) ?>

         <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options'=>['id'=>'stafflist'],
           'formatter'=> ['class'=>'yii\i18n\Formatter','nullDisplay'=>'-'],
         'panel' => [
              'type' => GridView::TYPE_PRIMARY,
              // 'heading' => 'Memo list',
              //'after'=>$after,0
              // 'before'=> $add ,
        ],
        // 'toolbar'=> false,
        // 'pjax'=>true,
         'floatHeaderOptions'=>[
             'top'=>50,
             'position'=>'absolute',
           ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
               [
              'label'=>'Status',
              'attribute'=>'statuslist.name',
              'format'=>'raw',
              'value'=>function($model){
                  if($model->status == 1){
                      return '<span class="badge bg-green">'.$model->statuslist->name.'</span>';
                  }else if($model->status == 2){
                      return  '<span class="badge bg-red">'.$model->statuslist->name.' effective this '.$model->date_termination.'</span>';
                  }else if($model->status == 3){
                      return  '<span class="badge bg-orange">'.$model->statuslist->name.' effective this '.$model->date_resignation.'</span>';
                  }else{
                      return  '<span class="badge bg-primary">'.$model->statuslist->name.'</span>';
                  }
              }
            ],
            [
              'label'=>'First Name',
              'attribute'=>'fname',
              'value'=>function($model){
                  return $model->staff->fname;
              }
            ],
             [
              'label'=>'Middle Name',
              'attribute'=>'mname',
              'value'=>function($model){
                  return $model->staff->mname;
              }
            ],
             [
              'label'=>'Last Name',
              'attribute'=>'lname',
              'value'=>function($model){
                  return $model->staff->lname;
              }
            ],
              [
              'label'=>'Fund Source',
              // 'attribute'=>'position',
              'value'=>function($model){
                  
                  if($model->memoAthPosition){
                    return $model->memoAthPosition->memo->fundsource->name .': '.$model->memoAthPosition->memo->fundsource->fundSourceType->name;
                  }else{
                    return $model->memo->fundsource->name.': '.$model->memo->fundsource->fundSourceType->name;;
                  }
              }
            ],
             [
              'label'=>'Memo Subject',
              // 'attribute'=>'position',
              'value'=>function($model){
                if($model->memoAthPosition){
                  return $model->memoAthPosition->memo->memo_subject;
                }else{
                  return $model->memo->memo_subject;
                }
              }
            ],
           [
              'label'=>'Position',
              'attribute'=>'position',
              'value'=>function($model){
                if($model->memoAthPosition){
                 return $model->memoAthPosition->position->description;
                }else{
                  return $model->positionSavings->description;
                }

                  
              }
            ],
            
             [
              'label'=>'Sector',
              'attribute'=>'sector',
              'value'=>function($model){
                  return $model->sector->name;
              }
            ],
            'date_start_contract',
            'date_end_contract',
        
            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

