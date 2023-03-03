<?php

use yii\db\Migration;

/**
 * Class m230227_064255_create_search_property_number_procedure
 */
class m230227_064255_create_search_property_number_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        YIi::$app->db->createCommand("DROP PROCEDURE IF EXISTS search_property_number;
    DELIMITER //
    CREATE PROCEDURE search_property_number (IN office_id INT ,IN _year VARCHAR(255))
    BEGIN
    SET @row := 0;
    WITH seq AS 
    (
    SELECT @row := @row + 1 as row
    FROM property t, (SELECT @row := 0) r
    WHERE
     t.fk_office_id =office_id
    AND t.property_number LIKE _year
    ),
    used_num as (
        SELECT CAST(SUBSTRING_INDEX(property_number,'-',-1) AS UNSIGNED) as p_number
        from property 
        WHERE  
     property.fk_office_id =office_id
    AND property.property_number LIKE _year
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
    (SELECT * FROM l_num) as lst_num;
    END //
    DELIMITER ;
    
    
    ")
            ->execute();
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
        echo "m230227_064255_create_search_property_number_procedure cannot be reverted.\n";

        return false;
    }
    */
}
