<?php

use yii\db\Migration;

/**
 * Class m230313_034747_create_getLstPcNum_procedure
 */
class m230313_034747_create_getLstPcNum_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
        
            DROP PROCEDURE IF EXISTS getLstPcNum;
            CREATE PROCEDURE getLstPcNum(IN office_id INT )
            BEGIN

            SET @row := 0;
            WITH seq AS 
            (
            SELECT @row := @row + 1 as row
            FROM property_card , (SELECT @row := 0) r,par 
            WHERE
            property_card.fk_par_id = par.id
            AND
            par.fk_office_id =office_id
            AND
            par._year >=2023


            ),
            used_num as (
                SELECT CAST(SUBSTRING_INDEX(property_card.serial_number,'-',-1) AS UNSIGNED) as p_number
                from property_card 
            JOIN par ON property_card.fk_par_id = par.id
                WHERE  
            par.fk_office_id =office_id
            AND par._year >=2023

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
        echo "m230313_034747_create_getLstPcNum_procedure cannot be reverted.\n";

        return false;
    }
    */
}
