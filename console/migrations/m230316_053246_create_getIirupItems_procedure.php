<?php

use yii\db\Migration;

/**
 * Class m230316_053246_create_getIirupItems_procedure
 */
class m230316_053246_create_getIirupItems_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP PROCEDURE IF EXISTS getIirupItems;
            CREATE PROCEDURE getIirupItems(IN iirup_id BIGINT )
            BEGIN
            SELECT 
            iirup_items.id as item_id,
            other_property_detail_items.id as other_property_detail_item_id,
            property.property_number,
            par.par_number,
            IFNULL(property_articles.article_name,property.article) as article_name,
            property.description,
            property.serial_number,
            property.date as date_acquired,
            property.acquisition_amount,
            books.`name` as book_name,
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
            ROUND((other_property_detail_items.amount - @slvg_val)/other_property_details.useful_life)as mnthly_depreciation,
            par.is_unserviceable
            FROM
            iirup_items
            JOIN other_property_detail_items ON iirup_items.fk_other_property_detail_item_id= other_property_detail_items.id
            JOIN other_property_details ON other_property_detail_items.fk_other_property_details_id= other_property_details.id
            JOIN  property ON other_property_details.fk_property_id = property.id
            LEFT JOIN books ON other_property_detail_items.book_id = books.id
            LEFT JOIN chart_of_accounts ON other_property_details.fk_chart_of_account_id  = chart_of_accounts.id
            LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
            JOIN par ON property.id = par.fk_property_id
            WHERE 
            iirup_items.fk_iirup_id = iirup_id
            AND iirup_items.is_deleted = 0;
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
        echo "m230316_053246_create_getIirupItems_procedure cannot be reverted.\n";

        return false;
    }
    */
}
