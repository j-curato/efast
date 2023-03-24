<?php

use yii\db\Migration;

/**
 * Class m230322_030512_create_derecognitionProperty_procedure
 */
class m230322_030512_create_derecognitionProperty_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<< SQL
           DROP PROCEDURE IF EXISTS derecognitionProperty;
            CREATE PROCEDURE derecognitionProperty(IN derecognition_date VARCHAR(20),IN property_id BIGINT,IN book_id INT )
            BEGIN 

            SET  @with_book :=  '';
            IF book_id IS NOT NULL THEN
            SET  @with_book :=  CONCAT(' AND books.id = ',book_id);
            END IF;
            SET @finalQuery =CONCAT("SELECT
            property.property_number,
            property.date as date_acquired,
            property.serial_number,
            IFNULL(property_articles.article_name,property.article) as article_name,
            property.description,
            unit_of_measure.unit_of_measure,
            property.acquisition_amount,
            books.`name` as book_name,
            depreciation_sub_account.`name` as depreciation_account_title,
            depreciation_sub_account.`object_code` as depreciation_object_code,
            other_property_detail_items.amount as book_amt,
            property.date,
            @start_month :=(CASE
            WHEN DAY(property.date) > 15 THEN DATE_FORMAT(  DATE_ADD( property.date,INTERVAL 1 MONTH), '%Y-%m')
            ELSE DATE_FORMAT(property.date, '%Y-%m')
            END ) as strt_mnth,
            @last_month :=  DATE_FORMAT(DATE_ADD(CONCAT(@start_month,'-01'),INTERVAL other_property_details.useful_life MONTH), '%Y-%m') as lst_mth,
            @newLstMth :=(CASE
            WHEN @last_month>DATE_FORMAT('",derecognition_date,"','%Y-%m') THEN   DATE_FORMAT('",derecognition_date,"','%Y-%m')
            ELSE @last_month
            END) as new_last_month,
            @usefulLife := TIMESTAMPDIFF(month,CONCAT(@start_month,'-01'),CONCAT(@newLstMth,'-01')) as useful_life,
            @slvg_val := ROUND((other_property_details.salvage_value_prcnt/100)*other_property_detail_items.amount,2) as salage_value,
            ROUND(other_property_detail_items.amount - @slvg_val,2) as depreciable_amount,
            ROUND((other_property_detail_items.amount - @slvg_val)/other_property_details.useful_life)as mnthly_depreciation

            FROM
            property 
            JOIN other_property_details ON property.id =  other_property_details.fk_property_id
            JOIN other_property_detail_items ON other_property_details.id = other_property_detail_items.fk_other_property_details_id
            LEFT JOIN sub_accounts1 ON other_property_details.fk_sub_account1_id = sub_accounts1.id
            LEFT JOIN sub_accounts1 as depreciation_sub_account ON other_property_details.fk_depreciation_sub_account1_id = depreciation_sub_account.id
            LEFT JOIN books ON other_property_detail_items.book_id = books.id
            LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
            LEFT JOIN unit_of_measure ON property.unit_of_measure_id = unit_of_measure.id
            WHERE property.id =", property_id,
            @with_book);

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
        echo "m230322_030512_create_derecognitionProperty_procedure cannot be reverted.\n";

        return false;
    }
    */
}
