<?php

use yii\db\Migration;

/**
 * Class m230117_021806_create_jev_last_number_procedure
 */
class m230117_021806_create_jev_last_number_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("
            DROP PROCEDURE IF EXISTS jev_last_number;
            DELIMITER //
            CREATE PROCEDURE jev_last_number (IN  reporting_period VARCHAR(10),IN book_id INT,IN ref_number VARCHAR(10) )
            BEGIN
            SET @r := 0;
            WITH seq AS 
            (
            SELECT @r := @r + 1 as r
            FROM jev_preparation t, (SELECT @r := 0) r
            WHERE
            t.reporting_period LIKE reporting_period AND t.book_id = book_id AND t.ref_number = ref_number
            ),
            used_num as (
                SELECT CAST(SUBSTRING_INDEX(jev_number,'-',-1) AS UNSIGNED) as p_number
                from jev_preparation 
                WHERE  jev_preparation.reporting_period LIKE reporting_period 
            AND jev_preparation.book_id = book_id 
            AND jev_preparation.ref_number = ref_number
            ),
            vacant_num as (SELECT  seq.`row` FROM seq LEFT JOIN used_num ON seq.`row` = used_num.p_number WHERE used_num.p_number IS NULL),
            l_num as (SELECT used_num.p_number +1 as l_num FROM used_num ORDER BY used_num.p_number DESC LIMIT 1)
            SELECT 
            IFNULL(
            (SELECT vacant_num.`row` FROM vacant_num ORDER BY vacant_num.`row` ASC LIMIT 1)
            ,l_num.l_num
            ) as  q
            FROM  l_num;
            END //
            DELIMTER ;
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
        echo "m230117_021806_create_jev_last_number_procedure cannot be reverted.\n";

        return false;
    }
    */
}
