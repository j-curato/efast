<?php

use yii\db\Migration;

/**
 * Class m230331_020054_update_getLstParNum_procedure
 */
class m230331_020054_update_getLstParNum_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
             DROP PROCEDURE IF EXISTS getLstParNum;
            CREATE PROCEDURE getLstParNum(IN office_id INT )
            BEGIN
            WITH parNums as (
            SELECT CAST(SUBSTRING_INDEX(par.par_number,'-',-1)AS UNSIGNED) as l_num
            FROM par
            WHERE par.fk_office_id = office_id
            AND par._year >=2023 

            ),
            seq as (
            SELECT `row` FROM
            (SELECT @row := @row + 1 AS `row`
            FROM (SELECT @row:=0) r, INFORMATION_SCHEMA.TABLES t1,
            INFORMATION_SCHEMA.TABLES t2) sequence
            WHERE `row` >= 1 AND `row` <= 
            (SELECT parNums.l_num FROM parNums ORDER BY parNums.l_num DESC LIMIT 1)
            )
            SELECT (
            SELECT seq.`row`
            FROM seq
            LEFT JOIN parNums ON seq.`row` = parNums.`l_num`
            WHERE parNums.`l_num`  IS NULL
            ORDER BY seq.`row` LIMIT 1 ) as vcnt_num, (SELECT parNums.l_num +1 FROM parNums ORDER BY  parNums.l_num DESC LIMIT 1)  as lst_num;

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
        echo "m230331_020054_update_getLstParNum_procedure cannot be reverted.\n";

        return false;
    }
    */
}
