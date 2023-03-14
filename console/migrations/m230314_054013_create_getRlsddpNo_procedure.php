<?php

use yii\db\Migration;

/**
 * Class m230314_054013_create_getRlsddpNo_procedure
 */
class m230314_054013_create_getRlsddpNo_procedure extends Migration
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

            SET @row := 0;
            WITH seq AS 
            (
            SELECT @row := @row + 1 as row
            FROM rlsddp t, (SELECT @row := 0) r
            WHERE
            t.fk_office_id =office_id
            ),
            used_num as (
                SELECT CAST(SUBSTRING_INDEX(serial_number,'-',-1) AS UNSIGNED) as p_number
                from rlsddp 
                WHERE  
            rlsddp.fk_office_id =office_id
            ),
            vacant_numbers as (
            SELECT 
            seq.`row` 
            FROM seq 
            LEFT JOIN used_num ON seq.`row` = used_num.p_number 
            WHERE used_num.p_number IS NULL),
            l_num as (
            SELECT used_num.p_number +1 as l_num
            FROM used_num
            ORDER BY used_num.p_number 
            DESC LIMIT 1
            ),
            vacant_num as (SELECT vacant_numbers.`row` FROM vacant_numbers ORDER BY vacant_numbers.`row` ASC LIMIT 1)


            SELECT 
            (SELECT * FROM vacant_num ) as vcnt_num,
            (SELECT * FROM l_num) as lst_num
            ;
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
        echo "m230314_054013_create_getRlsddpNo_procedure cannot be reverted.\n";

        return false;
    }
    */
}
