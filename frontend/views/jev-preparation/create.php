<?php

use app\models\ResponsibilityCenter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\JevPreparation */

$this->title = 'Create Jev Preparation';
$this->params['breadcrumbs'][] = ['label' => 'Jev Preparations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-create">



    <?= $this->render('_form_new', [
        'model' => $model,
        'modelJevItems' => $modelJevItems
    ]) ?>




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