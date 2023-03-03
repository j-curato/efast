<?php

namespace app\components\helpers;

use ErrorException;
use Yii;
use yii\base\BaseObject;
use yii\db\Query;
use yii\helpers\Html;

class MyHelper extends BaseObject
{
    public static function post($name)
    {
        return Yii::$app->request->post($name);
    }
    public static function uuid()
    {
        return Yii::$app->db->createCommand('SELECT UUID_SHORT()')->queryScalar();
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
    public static function gridDefaultAction($id, $class = 'modalButtonUpdate')
    {
        return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $id])
            . ' ' . Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $id], ['class' => $class]);
    }
    public  static function getEmployee($id, $qry_type = 'all')
    {
        // $query =   Yii::$app->db->createCommand("SELECT employee_name,position FROM employee_search_view WHERE  employee_id = :id")
        //     ->bindValue(':id', $id);
        $query = new Query();
        $query->select(['employee_id', 'employee_name', 'position'])
            ->from('employee_search_view')
            ->where('employee_id = :id', ['id' => $id]);


        if ($qry_type === 'all') {
            $res =  $query->all();
        } else if ($qry_type === 'one') {
            $res = $query->one();
        }
        return $res;
    }
}
