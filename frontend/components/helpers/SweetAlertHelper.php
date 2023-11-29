<?php

namespace app\components\helpers;

use Yii;
use yii\base\BaseObject;
use yii\web\JsExpression;


class SweetAlertHelper extends BaseObject
{
    public static function getCancelConfirmation($actionUrl)
    {
        return new JsExpression('
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this item!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, cancel it!",
            closeOnConfirm: false
        },
        function(){
            $.ajax({
                url: "' . $actionUrl . '",
                type: "post",
                data: {
                    _csrf: "' . Yii::$app->request->getCsrfToken() . '"
                },
                success: function(data) {
                    if(data ==true){
                        swal({
                            title: "Cancelled",
                            type: "success",
                            button: false,
                            timer: 3000,
                        }, function() {
                            location.reload(true)
                        })
                    } 
                    else{
                        swal("Cancel Error!", data, "error");
                       
                    }
                }
            })
        });
        return false;
    ');
    }
}
