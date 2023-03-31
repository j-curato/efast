<?php

use yii\db\Migration;

/**
 * Class m230331_021639_update_getRlsddpNo_procedure
 */
class m230331_021639_update_getRlsddpNo_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP PROCEDURE IF EXISTS getRlsddpNo;
            CREATE PROCEDURE getRlsddpNo(IN office_id INT )
            BEGIN

            WITH rlsddpNums as (
            SELECT CAST(SUBSTRING_INDEX(rlsddp.serial_number,'-',-1)AS UNSIGNED) as l_num
            FROM rlsddp

            WHERE rlsddp.fk_office_id = office_id


            ),
            seq as (
            SELECT `row` FROM
            (SELECT @row := @row + 1 AS `row`
            FROM (SELECT @row:=0) r, INFORMATION_SCHEMA.TABLES t1,
            INFORMATION_SCHEMA.TABLES t2) sequence
            WHERE `row` >= 1 AND `row` <= 
            (SELECT rlsddpNums.l_num FROM rlsddpNums ORDER BY rlsddpNums.l_num DESC LIMIT 1)
            )
            SELECT (
            SELECT seq.`row`
            FROM seq
            LEFT JOIN rlsddpNums ON seq.`row` = rlsddpNums.`l_num`
            WHERE rlsddpNums.`l_num`  IS NULL
            ORDER BY seq.`row` LIMIT 1 ) as vcnt_num, (SELECT rlsddpNums.l_num +1 FROM rlsddpNums ORDER BY  rlsddpNums.l_num DESC LIMIT 1)  as lst_num;
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
        echo "m230331_021639_update_getRlsddpNo_procedure cannot be reverted.\n";

        return false;
    }
    */
}
