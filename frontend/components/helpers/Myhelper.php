<?php

namespace app\components\helpers;

use ErrorException;
use Yii;
use yii\base\BaseObject;
use yii\helpers\Html;

class MyHelper extends BaseObject
{
    public static function post($name)
    {
        return Yii::$app->request->post($name);
    }
    public static function checkAllotmentBalance(
        $allotment_id,
        $amount = 0,
        $prAllotmentId = null,
        $txnAllotmentId = null,
        $txnPrItmId = null,
        $orsItmId = null,
        $orsTxnItmId = null
    ) {
        $cur_balance = 0;
        try {
            $balance = Yii::$app->db->createCommand("CALL
            GetAllotmentBalance(
            :allotment_id,
            :prAllotmentId,
            :txnAllotmentId,
            :txnPrItmId,
            :orsItmId,
            :orsTxnItmId)")
                ->bindValue(':allotment_id', $allotment_id)
                ->bindValue(':prAllotmentId', $prAllotmentId)
                ->bindValue(':txnAllotmentId', $txnAllotmentId)
                ->bindValue(':txnPrItmId', $txnPrItmId)
                ->bindValue(':orsItmId', $orsItmId)
                ->bindValue(':orsTxnItmId', $orsTxnItmId)
                ->queryScalar();
            $cur_balance = floatval($balance) - floatval($amount);

            if ($cur_balance < 0) {
                throw new ErrorException("Allotment Amount Cannot be more than " . number_format($balance, 2));
            }
        } catch (ErrorException $e) {
            return $e->getMessage();
        }

        return true;
    }
    public static function gridDefaultAction($id)
    {
        return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $id])
            . ' ' . Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $id], ['class' => 'modalButtonUpdate']);
    }
}
