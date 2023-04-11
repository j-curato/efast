<?php

use yii\db\Migration;

/**
 * Class m230411_060850_create_detailed_other_property_details_view
 */
class m230411_060850_create_detailed_other_property_details_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $sql = <<<SQL
            DROP VIEW IF EXISTS detailed_other_property_details;
            CREATE VIEW detailed_other_property_details AS 
                SELECT 
                property.id as property_id,
                property.fk_office_id,
                property.property_number,
                LOWER(books.`name`) as book_name,
                other_property_detail_items.amount as book_val,
                ROUND((other_property_details.salvage_value_prcnt/100)*other_property_detail_items.amount) as salvage_value,
                ROUND(other_property_detail_items.amount - ROUND((other_property_details.salvage_value_prcnt/100)*other_property_detail_items.amount)) as depreciable_amount,
                ROUND((other_property_detail_items.amount - ROUND((other_property_details.salvage_value_prcnt/100)*other_property_detail_items.amount,2))/other_property_details.useful_life) as mnthly_depreciation,
                ROUND(ROUND(other_property_detail_items.amount -
                ROUND((other_property_details.salvage_value_prcnt/100)*other_property_detail_items.amount,2),2)-
                ( ROUND((other_property_detail_items.amount - ROUND((other_property_details.salvage_value_prcnt/100)*other_property_detail_items.amount,2))
                /other_property_details.useful_life)* (other_property_details.useful_life-1))) as lstmnthdep,
                
                (CASE 
                WHEN TIMESTAMPDIFF(MONTH, STR_TO_DATE(DATE_FORMAT(property.date,'%Y-%m-30'), '%Y-%m-%d'), DATE_FORMAT(CURRENT_DATE(),'%Y-%m-30'))>=other_property_details.useful_life THEN other_property_details.useful_life
                ELSE TIMESTAMPDIFF(MONTH, STR_TO_DATE(DATE_FORMAT(property.date,'%Y-%m-30'), '%Y-%m-%d'), DATE_FORMAT(CURRENT_DATE(),'%Y-%m-30')) -1
                END) as mnt_dep,
                
                (CASE 
                WHEN TIMESTAMPDIFF(MONTH, STR_TO_DATE(DATE_FORMAT(property.date,'%Y-%m-30'), '%Y-%m-%d'), DATE_FORMAT(CURRENT_DATE(),'%Y-%m-30'))>=other_property_details.useful_life-1 THEN other_property_details.useful_life
                ELSE TIMESTAMPDIFF(MONTH, STR_TO_DATE(DATE_FORMAT(property.date,'%Y-%m-30'), '%Y-%m-%d'), DATE_FORMAT(CURRENT_DATE(),'%Y-%m-30')) -1
                END) 
                * ROUND((other_property_detail_items.amount - ROUND((other_property_details.salvage_value_prcnt/100)*other_property_detail_items.amount,2))
                /other_property_details.useful_life) as accu_depreciation,
                other_property_detail_items.amount -      (CASE 
                WHEN TIMESTAMPDIFF(MONTH, STR_TO_DATE(DATE_FORMAT(property.date,'%Y-%m-30'), '%Y-%m-%d'), DATE_FORMAT(CURRENT_DATE(),'%Y-%m-30'))>=other_property_details.useful_life-1 THEN other_property_details.useful_life
                ELSE TIMESTAMPDIFF(MONTH, STR_TO_DATE(DATE_FORMAT(property.date,'%Y-%m-30'), '%Y-%m-%d'), DATE_FORMAT(CURRENT_DATE(),'%Y-%m-30')) -1
                END) 
                * ROUND((other_property_detail_items.amount - ROUND((other_property_details.salvage_value_prcnt/100)*other_property_detail_items.amount,2))
                /other_property_details.useful_life) as book_val_bal
                FROM property
                LEFT JOIN other_property_details ON property.id = other_property_details.fk_property_id
                LEFT JOIN other_property_detail_items ON other_property_details.id = other_property_detail_items.fk_other_property_details_id
                LEFT JOIN derecognition ON property.id = derecognition.fk_property_id
                LEFT JOIN books ON other_property_detail_items.book_id = books.id
                WHERE 
                other_property_detail_items.is_deleted = 0
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
        echo "m230411_060850_create_detailed_other_property_details_view cannot be reverted.\n";

        return false;
    }
    */
}
