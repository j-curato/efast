<?php

namespace frontend\components;

use app\models\Books;
use app\models\FundClusterCode;
use yii\base\Component;

class MyComponent extends Component
{
    // public function getRaoudSerialNumber($q)
    // {
    //     return $q;
    // }
    public function getRaoudSerialNumber($reporting_period, $book, $update_id)
    {


        $book = Books::findOne($book);

        // $q = RecordAllotments::find()
        // ->orderBy(['id' => SORT_DESC])
        // ->one();

        // KUHAAON ANG SERIAL NUMBER SA LAST ID OR SA GE UPDATE NA ID
        $f = (new \yii\db\Query())
            ->select('serial_number')
            ->from('record_allotments');
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

        $serial_number = $book->name . '-' . $reporting_period . '-' . $last_number;
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
    public function cancel($id, $table, $type)
    {
        // $q=(new \yii\db\Query())
        // ->update("$table")
        // ->set('is_cancelled =:is_cancelled',['is_cancelled'=>$type])
        // ->where("id =:id",['id'=>$id]);

        \Yii::$app->db->createCommand("UPDATE :t SET is_cancelled = :tpe WHERE id = :id")
            ->bindValue(`:t`, $table)
            ->bindValue(':tpe', $type)
            ->bindValue(':id', $id)

            ->execute();
    }
    public function cibrCdrHeader($province)
    {
        $prov = [
            'adn' => [
                'province' => 'Agusan Del Norte',
                'officer' => 'Rosie R. Vellesco',
                'location' => 'Butuan City'
            ],
            'sdn' => [
                'province' => 'Surigao Del Norte',
                'officer' => 'Ferdinand R. Inres',
                'location' => 'Surigao City'
            ],

            'ads' => [
                'province' => 'Agusan Del SUr',
                'officer' => 'Maria Prescylin C. Lademora',
                'location' => 'San Francisco, ADS'
            ],
            'sds' => [
                'province' => 'Surigao Del Sur',
                'officer' => 'Fritzie N. Usares',
                'location' => 'Tandag City'
            ],
            'pdi' => [
                'province' => 'Dinagat Islands',
                'officer' => 'Venus A. Custodio',
                'location' => 'San Jose, PDI'
            ],


        ];
        return $prov[$province];
    }
}
