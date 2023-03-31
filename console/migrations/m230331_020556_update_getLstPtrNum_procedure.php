<?php

use yii\db\Migration;

/**
 * Class m230331_020556_update_getLstPtrNum_procedure
 */
class m230331_020556_update_getLstPtrNum_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP PROCEDURE IF EXISTS getLstPtrNum;
            CREATE PROCEDURE getLstPtrNum(IN office_id INT )
            BEGIN
            WITH ptrNums as (
            SELECT CAST(SUBSTRING_INDEX(ptr.ptr_number,'-',-1)AS UNSIGNED) as l_num
            FROM ptr

            WHERE ptr.fk_office_id = office_id


            ),
            seq as (
            SELECT `row` FROM
            (SELECT @row := @row + 1 AS `row`
            FROM (SELECT @row:=0) r, INFORMATION_SCHEMA.TABLES t1,
            INFORMATION_SCHEMA.TABLES t2) sequence
            WHERE `row` >= 1 AND `row` <= 
            (SELECT ptrNums.l_num FROM ptrNums ORDER BY ptrNums.l_num DESC LIMIT 1)
            )
            SELECT (
            SELECT seq.`row`
            FROM seq
            LEFT JOIN ptrNums ON seq.`row` = ptrNums.`l_num`
            WHERE ptrNums.`l_num`  IS NULL
            ORDER BY seq.`row` LIMIT 1 ) as vcnt_num, (SELECT ptrNums.l_num +1 FROM ptrNums ORDER BY  ptrNums.l_num DESC LIMIT 1)  as lst_num;
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
        echo "m230331_020556_update_getLstPtrNum_procedure cannot be reverted.\n";

        return false;
    }
    */
}
