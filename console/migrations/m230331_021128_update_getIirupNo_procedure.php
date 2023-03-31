<?php

use yii\db\Migration;

/**
 * Class m230331_021128_update_getIirupNo_procedure
 */
class m230331_021128_update_getIirupNo_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP PROCEDURE IF EXISTS getIirupNo;
            CREATE PROCEDURE getIirupNo(IN office_id INT )
            BEGIN
            WITH iirupNums as (
            SELECT CAST(SUBSTRING_INDEX(iirup.serial_number,'-',-1)AS UNSIGNED) as l_num
            FROM iirup

            WHERE iirup.fk_office_id = 1


            ),
            seq as (
            SELECT `row` FROM
            (SELECT @row := @row + 1 AS `row`
            FROM (SELECT @row:=0) r, INFORMATION_SCHEMA.TABLES t1,
            INFORMATION_SCHEMA.TABLES t2) sequence
            WHERE `row` >= 1 AND `row` <= 
            (SELECT iirupNums.l_num FROM iirupNums ORDER BY iirupNums.l_num DESC LIMIT 1)
            )
            SELECT (
            SELECT seq.`row`
            FROM seq
            LEFT JOIN iirupNums ON seq.`row` = iirupNums.`l_num`
            WHERE iirupNums.`l_num`  IS NULL
            ORDER BY seq.`row` LIMIT 1 ) as vcnt_num, (SELECT iirupNums.l_num +1 FROM iirupNums ORDER BY  iirupNums.l_num DESC LIMIT 1)  as lst_num;

            END 
        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230331_021128_update_getIirupNo_procedure cannot be reverted.\n";

        return false;
    }
    */
}
