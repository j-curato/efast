<?php

use yii\db\Migration;

/**
 * Class m230331_020427_update_getLstPcNum_procedure
 */
class m230331_020427_update_getLstPcNum_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql  = <<<SQL
        
            DROP PROCEDURE IF EXISTS getLstPcNum;
            CREATE PROCEDURE getLstPcNum(IN office_id INT )
            BEGIN
            WITH propertyCardNums as (
            SELECT CAST(SUBSTRING_INDEX(property_card.serial_number,'-',-1)AS UNSIGNED) as l_num
            FROM property_card
            JOIN par ON property_card.fk_par_id = par.id
            WHERE par.fk_office_id = office_id
            AND par._year >= 2023
            ),
            seq as (
            SELECT `row` FROM
            (SELECT @row := @row + 1 AS `row`
            FROM (SELECT @row:=0) r, INFORMATION_SCHEMA.TABLES t1,
            INFORMATION_SCHEMA.TABLES t2) sequence
            WHERE `row` >= 1 AND `row` <= 
            (SELECT propertyCardNums.l_num FROM propertyCardNums ORDER BY propertyCardNums.l_num DESC LIMIT 1)
            )
            SELECT (
            SELECT seq.`row`
            FROM seq
            LEFT JOIN propertyCardNums ON seq.`row` = propertyCardNums.`l_num`
            WHERE propertyCardNums.`l_num`  IS NULL
            ORDER BY seq.`row` LIMIT 1 ) as vcnt_num, (SELECT propertyCardNums.l_num +1 FROM propertyCardNums ORDER BY  propertyCardNums.l_num DESC LIMIT 1)  as lst_num;
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
        echo "m230331_020427_update_getLstPcNum_procedure cannot be reverted.\n";

        return false;
    }
    */
}
