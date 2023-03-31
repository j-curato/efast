<?php

use yii\db\Migration;

/**
 * Class m230331_014249_update_search_property_number_procedure
 */
class m230331_014249_update_search_property_number_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP PROCEDURE IF EXISTS search_property_number;
            CREATE PROCEDURE search_property_number (IN office_id INT ,IN _year VARCHAR(255))
            BEGIN

            WITH propertyNums as (
            SELECT CAST(SUBSTRING_INDEX(property.property_number,'-',-1)AS UNSIGNED) as l_num
            FROM property
            WHERE property.fk_office_id = office_id
            AND property.ppe_year = _year

            ),
            seq as (
            SELECT `row` FROM
            (SELECT @row := @row + 1 AS `row`
            FROM (SELECT @row:=0) r, INFORMATION_SCHEMA.TABLES t1,
            INFORMATION_SCHEMA.TABLES t2) sequence
            WHERE `row` >= 1 AND `row` <= 
            (SELECT propertyNums.l_num FROM propertyNums ORDER BY propertyNums.l_num DESC LIMIT 1)
            )
            SELECT (
            SELECT seq.`row`
            FROM seq
            LEFT JOIN propertyNums ON seq.`row` = propertyNums.`l_num`
            WHERE propertyNums.`l_num`  IS NULL
            ORDER BY seq.`row` LIMIT 1 ) as vcnt_num, (SELECT propertyNums.l_num +1 FROM propertyNums ORDER BY  propertyNums.l_num DESC LIMIT 1)  as lst_num;
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
        echo "m230331_014249_update_search_property_number_procedure cannot be reverted.\n";

        return false;
    }
    */
}
