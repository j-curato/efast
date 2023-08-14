<?php

use yii\db\Migration;

/**
 * Class m230814_032728_create_vw_supplemental_cse_prs_view
 */
class m230814_032728_create_vw_supplemental_cse_prs_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<< SQL
            DROP VIEW IF EXISTS vw_supplemental_cse_prs;
            CREATE VIEW vw_supplemental_cse_prs as 
                select `pr_purchase_request`.`id` AS `pr_id`,
                `pr_purchase_request`.`pr_number`
                AS `pr_number`,`supplemental_ppmp`.`id` AS `ppmp_id`
                from `supplemental_ppmp`
                join `supplemental_ppmp_cse` on`supplemental_ppmp`.`id` = `supplemental_ppmp_cse`.`fk_supplemental_ppmp_id`
                join `pr_purchase_request_item` on`supplemental_ppmp_cse`.`id` = `pr_purchase_request_item`.`fk_ppmp_cse_item_id`
                join `pr_purchase_request` on `pr_purchase_request_item`.`pr_purchase_request_id` = `pr_purchase_request`.`id`
                where `supplemental_ppmp_cse`.`is_deleted` = 0
                and `pr_purchase_request_item`.`is_deleted` =  0
                and `pr_purchase_request`.`is_cancelled` = 0
                group by `pr_purchase_request`.`id`,`pr_purchase_request`.`pr_number`,`supplemental_ppmp`.`id`

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
        echo "m230814_032728_create_vw_supplemental_cse_prs_view cannot be reverted.\n";

        return false;
    }
    */
}
