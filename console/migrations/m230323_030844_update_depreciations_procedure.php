<?php

use yii\db\Migration;

/**
 * Class m230323_030844_update_depreciations_procedure
 */
class m230323_030844_update_depreciations_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<< SQL
            DROP PROCEDURE IF EXISTS depreciations;
            CREATE PROCEDURE depreciations(IN reporting_period VARCHAR(20),IN book_id INT )
            BEGIN 
            SET  @is_notAll :=  '';
            IF book_id IS NOT NULL THEN
            SET  @is_notAll :=  CONCAT(' AND books.id != ',book_id);
            END IF;
            SET @finalQuery = CONCAT("WITH q AS (SELECT 
            property.id,
            property.property_number,
            IFNULL(property_articles.article_name,property.article) as article_name,
            property.description,
            property.serial_number,
            property.date as date_acquired,
            property.acquisition_amount,
            books.`name` as book_name,
            depreciation_sub_account.`name` as depreciation_account_title,
            depreciation_sub_account.`object_code` as depreciation_object_code,
            other_property_detail_items.amount,
            property.date,
            other_property_details.useful_life,
            @start_month :=(CASE
            WHEN DAY(property.date) > 15 THEN DATE_FORMAT(  DATE_ADD( property.date,INTERVAL 1 MONTH), '%Y-%m')
            ELSE DATE_FORMAT(property.date, '%Y-%m')
            END ) as strt_mnth,
            @last_month :=  DATE_FORMAT(DATE_ADD(CONCAT(@start_month,'-01'),INTERVAL other_property_details.useful_life MONTH), '%Y-%m') as lst_mth,
            DATE_FORMAT(DATE_SUB(CONCAT(@last_month,'-01'),INTERVAL 1 MONTH), '%Y-%m') as sec_lst_mth,
            @slvg_val := ROUND((other_property_details.salvage_value_prcnt/100)*other_property_detail_items.amount,2) as salage_value,
            ROUND(other_property_detail_items.amount - @slvg_val,2) as depreciable_amount,
            ROUND((other_property_detail_items.amount - @slvg_val)/other_property_details.useful_life)as mnthly_depreciation
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
            END ) <= '",reporting_period,
            "' AND 
            (CASE
            WHEN DAY(property.date) > 15 THEN DATE_FORMAT(  DATE_ADD( property.date,INTERVAL other_property_details.useful_life+1 MONTH), '%Y-%m')
            ELSE DATE_FORMAT(  DATE_ADD( property.date,INTERVAL other_property_details.useful_life MONTH), '%Y-%m')
            END ) >= '",reporting_period,"' "
            ,@is_notAll,")
            SELECT q.* ,
            @newLstMth :=(CASE
            WHEN @last_month>DATE_FORMAT(derecognition.date,'%Y-%m') THEN DATE_FORMAT(derecognition.date,'%Y-%m')
            ELSE @last_month
            END) as derecognition_period

            FROM q
            LEFT JOIN derecognition ON q.id = derecognition.fk_property_id
            WHERE 
            (CASE
            WHEN @last_month>DATE_FORMAT(derecognition.date,'%Y-%m') THEN DATE_FORMAT(derecognition.date,'%Y-%m')
            ELSE @last_month
            END) >='",reporting_period,"'");
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
        echo "m230323_030844_update_depreciations_procedure cannot be reverted.\n";

        return false;
    }
    */
}
