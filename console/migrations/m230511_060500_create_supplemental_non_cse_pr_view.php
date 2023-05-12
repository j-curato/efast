<?php

use yii\db\Migration;

/**
 * Class m230511_060500_create_supplemental_non_cse_pr_view
 */
class m230511_060500_create_supplemental_non_cse_pr_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<< SQL
            DROP VIEW IF EXISTS supplemental_non_cse_prs;
            CREATE VIEW supplemental_non_cse_prs AS 
                SELECT 
                pr_purchase_request.id as pr_id,
                pr_purchase_request.pr_number,
                supplemental_ppmp.id as ppmp_id
                FROM supplemental_ppmp
                JOIN supplemental_ppmp_non_cse ON  supplemental_ppmp.id= supplemental_ppmp_non_cse.fk_supplemental_ppmp_id
                JOIN supplemental_ppmp_non_cse_items ON supplemental_ppmp_non_cse.id = supplemental_ppmp_non_cse_items.fk_supplemental_ppmp_non_cse_id
                JOIN pr_purchase_request_item ON supplemental_ppmp_non_cse_items.id  = pr_purchase_request_item.fk_ppmp_non_cse_item_id
                JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id  = pr_purchase_request.id
                WHERE 
                supplemental_ppmp_non_cse.is_deleted  = 0 
                AND supplemental_ppmp_non_cse_items.is_deleted = 0
                AND pr_purchase_request_item.is_deleted = 0
                AND pr_purchase_request.is_cancelled = 0
                GROUP BY
                pr_purchase_request.id,
                pr_purchase_request.pr_number,
                supplemental_ppmp.id
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
        echo "m230511_060500_create_supplemental_non_cse_pr_view cannot be reverted.\n";

        return false;
    }
    */
}
