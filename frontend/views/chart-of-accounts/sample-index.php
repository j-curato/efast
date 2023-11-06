<?php

use app\models\MajorAccounts;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\FileInput;
use kartik\widgets\ActiveForm;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ChartOfAccountsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


?>
<div class="chart-of-accounts-index">
    <?php
    ob_start();
    echo "<pre>";
    var_dump($dataProvider);
    echo "</pre>";
    return ob_get_clean();
    ?>

</div>

<?php
SweetAlertAsset::register($this);
$script = <<<JS


        $(document).ready(function(){
            var at =''
            var id=''
          
            $('.add-sub').click(function(){
              id =  document.getElementById('chart_id').value=$(this).val()
            })
            $('#save').click(function(){
             at = document.getElementById('account_title').value
            //  id = document.getElementById('chart_id').value
            console.log (at)
            $.ajax({
                type:'POST',
                url:window.location.pathname + '?r=chart-of-accounts/create-sub-account' ,
                data:{
                    account_title:at,
                    id:id,
                },
                success:function(data){
                    // var res = JSON.parse(data)
                    console.log(data)

    
                    if (data=='success'){
                    $('#myModal').modal('hide');
   
                        swal( {
                        icon: 'success',
                        title: "Successfuly Added",
                        type: "success",
                        timer:3000,
                        closeOnConfirm: false,
                        closeOnCancel: false
                    })
                    }
                    else{
                        swal( {
                        icon: 'error',
                        title:  res.name,
                        type: "error",
                        timer:3000,
                        closeOnConfirm: false,
                        closeOnCancel: false
                    })
                    }
                },
                beforeSend: function(){
                   setTimeout(() => {
                   console.log('loading');
                       
                   }, 5000);
                },
                complete: function(){
                    $('#loading').hide();
                }
                

            })
        })
        })


JS;
$this->registerJs($script);
?>

<?php

$js = "
        $('#mdModal').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
        $('.modalButtonedit').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });


";
$this->registerJs($js, $this::POS_END);
?>