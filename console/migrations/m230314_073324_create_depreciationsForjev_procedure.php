<?php

use yii\db\Migration;

/**
 * Class m230314_073324_create_depreciationsForjev_procedure
 */
class m230314_073324_create_depreciationsForjev_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
                DROP PROCEDURE IF EXISTS depreciationsForjev;
                CREATE PROCEDURE depreciationsForjev(IN reporting_period VARCHAR(20),IN book_id INT )
                BEGIN 
                DECLARE is_notAll VARCHAR(1024) DEFAULT NULL;

                SET  @is_notAll =  '';

                IF book_id IS NOT NULL THEN
                    SET  @is_notAll =  CONCAT(' AND books.id != ',book_id);
                END IF;
                SET @finalQuery = CONCAT("SELECT 
                books.`name` as book_name,
                depreciation_sub_account.`name` as account_title,
                depreciation_sub_account.`object_code` as object_code,
                @slvg_val := ROUND((other_property_details.salvage_value_prcnt/100)*other_property_detail_items.amount,2) as salage_value,
                ROUND((other_property_detail_items.amount - @slvg_val)/other_property_details.useful_life)as credit,
                0 as debit
                FROM other_property_details
                LEFT JOIN other_property_detail_items ON other_property_details.id = other_property_detail_items.fk_other_property_details_id
                LEFT JOIN sub_accounts1 ON other_property_details.fk_sub_account1_id = sub_accounts1.id
                LEFT JOIN sub_accounts1 as depreciation_sub_account ON other_property_details.fk_depreciation_sub_account1_id = depreciation_sub_account.id
                LEFT JOIN books ON other_property_detail_items.book_id = books.id
                LEFT JOIN chart_of_accounts ON other_property_details.fk_chart_of_account_id  = chart_of_accounts.id
                LEFT JOIN property ON other_property_details.fk_property_id = property.id
                LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
                WHERE 
                (CASE
                WHEN DAY(property.date) > 15 THEN DATE_FORMAT(  DATE_ADD( property.date,INTERVAL 1 MONTH), '%Y-%m')
                ELSE DATE_FORMAT(property.date, '%Y-%m')
                END ) <= ",reporting_period,
                
                " AND 
                (CASE
                WHEN DAY(property.date) > 15 THEN DATE_FORMAT(  DATE_ADD( property.date,INTERVAL other_property_details.useful_life MONTH), '%Y-%m')
                ELSE DATE_FORMAT(property.date, '%Y-%m')
                END ) >= ",reporting_period
                ,@is_notAll);

                PREPARE stmt FROM @finalQuery;
                EXECUTE stmt;
                DEALLOCATE PREPARE stmt;
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
        echo "m230314_073324_create_depreciationsForjev_procedure cannot be reverted.\n";

        return false;
    }
    */
}
