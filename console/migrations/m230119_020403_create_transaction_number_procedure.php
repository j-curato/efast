<?php

use yii\db\Migration;

/**
 * Class m230119_020403_create_transaction_number_procedure
 */
class m230119_020403_create_transaction_number_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("

            DROP PROCEDURE IF EXISTS transaction_number;
            DELIMTER //
            CREATE PROCEDURE transaction_number(IN _year INT, IN responsibility_center_id INT,OUT transaction_number INT)
            BEGIN
            DECLARE r_name CHAR(20) DEFAULT '';

                WITH latestTransactionNum as ( 
                    SELECT 
                    CAST(SUBSTRING_INDEX(`transaction`.tracking_number,'-',-1)AS UNSIGNED) as last_number ,
                    CONCAT(responsibility_center.`name`,'-',_year,'-',LPAD(CAST(SUBSTRING_INDEX(`transaction`.tracking_number,'-',-1)AS UNSIGNED)+1, 4, 0)) as transaction_number 
                    FROM `transaction`
                    LEFT JOIN responsibility_center ON `transaction`.responsibility_center_id = responsibility_center.id
                    WHERE responsibility_center.`id` = responsibility_center_id
                    AND `transaction`.tracking_number LIKE CONCAT('%',_year,'%')
                    ORDER BY last_number DESC
                    LIMIT 1
                ),
            responsibilityCenterName as (SELECT UPPER(responsibility_center.`name`) as r_name FROM responsibility_center WHERE responsibility_center.id = responsibility_center_id)
            -- 	SELECT @r_name := (SELECT responsibilityCenterName.r_name FROM responsibilityCenterName);
            SELECT @transaction_number := IFNULL((SELECT   latestTransactionNum.transaction_number FROM latestTransactionNum),CONCAT((SELECT responsibilityCenterName.r_name FROM responsibilityCenterName),'-',_year,'-0001'));

            END //
            DELIMTER;
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
        echo "m230119_020403_create_transaction_number_procedure cannot be reverted.\n";

        return false;
    }
    */
}
