<?php

use yii\db\Migration;

/**
 * Class m230123_012901_create_GetPrItems_procedure
 */
class m230123_012901_create_GetPrItems_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<< SQL
            DROP PROCEDURE IF EXISTS GetPrItems;
            CREATE PROCEDURE GetPrItems(IN pr_id BIGINT)
            BEGIN 
            SELECT 
                    (CASE 
                    WHEN pr_purchase_request_item.fk_ppmp_cse_item_id IS NOT NULL THEN 'cse_item_id'
                    WHEN     pr_purchase_request_item.fk_ppmp_non_cse_item_id IS NOT NULL THEN 'non_cse_item_id'
                    END
                    ) as cse_type,
                    (CASE 
                    WHEN pr_purchase_request_item.fk_ppmp_cse_item_id IS NOT NULL THEN pr_purchase_request_item.fk_ppmp_cse_item_id 
                    WHEN     pr_purchase_request_item.fk_ppmp_non_cse_item_id IS NOT NULL THEN  pr_purchase_request_item.fk_ppmp_non_cse_item_id 
                    END
                    ) as  ppmp_item_id,

                    (CASE 
                    WHEN pr_purchase_request_item.fk_ppmp_cse_item_id IS NOT NULL THEN pr_stock.stock_title
                    WHEN     pr_purchase_request_item.fk_ppmp_non_cse_item_id IS NOT NULL THEN  supplemental_ppmp_non_cse.activity_name 
                    END
                    ) as project_name,
                    pr_purchase_request_item.id as item_id,
                    pr_stock.id as stock_id,
                    pr_stock.stock_title,
                    unit_of_measure.id as unit_of_measure_id,
                    unit_of_measure.unit_of_measure,
                    pr_purchase_request_item.unit_cost,
                    pr_purchase_request_item.quantity,
                    pr_stock.bac_code,
                    pr_purchase_request_item.unit_cost * pr_purchase_request_item.quantity as total_cost,
                    IFNULL(REPLACE( pr_purchase_request_item.specification, '[n]', '<br>'),'') as specification,
                    supplemental_ppmp.is_supplemental
                FROM `pr_purchase_request_item`

                LEFT JOIN unit_of_measure ON pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
                LEFT JOIN supplemental_ppmp_non_cse_items ON pr_purchase_request_item.fk_ppmp_non_cse_item_id = supplemental_ppmp_non_cse_items.id
                LEFT JOIN supplemental_ppmp_cse ON pr_purchase_request_item.fk_ppmp_cse_item_id = supplemental_ppmp_cse.id
                LEFT JOIN supplemental_ppmp_non_cse ON supplemental_ppmp_non_cse_items.fk_supplemental_ppmp_non_cse_id = supplemental_ppmp_non_cse.id
                LEFT JOIN supplemental_ppmp ON supplemental_ppmp_cse.fk_supplemental_ppmp_id = supplemental_ppmp_cse.id OR supplemental_ppmp_non_cse.fk_supplemental_ppmp_id = supplemental_ppmp.id
                -- LEFT JOIN pr_stock ON supplemental_ppmp_cse.fk_pr_stock_id = pr_stock.id OR supplemental_ppmp_non_cse_items.fk_pr_stock_id = pr_stock.id
                LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id 

                WHERE 
                pr_purchase_request_item.pr_purchase_request_id = pr_id
                AND pr_purchase_request_item.is_deleted =0;
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
        echo "m230123_012901_create_GetPrItems_procedure cannot be reverted.\n";

        return false;
    }
    */
}
