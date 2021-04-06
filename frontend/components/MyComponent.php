<?php

namespace frontend\components;

use app\models\Books;
use yii\base\Component;

class MyComponent extends Component
{
    // public function getRaoudSerialNumber($q)
    // {
    //     return $q;
    // }
    public function getRaoudSerialNumber($reporting_period, $book_id, $update_id)
    {


        $book_name = Books::findOne($book_id);

        // $q = RecordAllotments::find()
        // ->orderBy(['id' => SORT_DESC])
        // ->one();

        // KUHAAON ANG SERIAL NUMBER SA LAST ID OR SA GE UPDATE NA ID
        $f = (new \yii\db\Query())
            ->select('serial_number')
            ->from('raouds');
            !empty($update_id) ? $f->where("id =:id", ['id' => $update_id]) : $f->orderBy("id DESC");
        $q = $f->one();


        if (!empty($q)) {
            $x = explode('-', $q['serial_number']);
            $y = 1;
            if (!empty($update_id)) {
                $y = 0;
            }
            $last_number = $x[3] + $y;
        } else {
            $last_number = 1;
        }

        $serial_number = $book_name->name . '-' . $reporting_period . '-' . $last_number;
        return  $serial_number;
    }

    public function getOrsBursRaoudSerialNumber($serial_number)
    {
        // $serial_number = "Fund 01-2021-01-3";
        $raoud = (new \yii\db\Query())
            ->select("serial_number")
            ->from('raouds')
            ->orderBy('id DESC')
            ->one();

        $x = explode('-', $serial_number);
        $x[3] = explode('-', $raoud['serial_number'])[3] + 1;
        $y = implode('-', $x);

        return $y;
    }
}