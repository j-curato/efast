<?php

use yii\db\Migration;

/**
 * Class m240112_053327_add_columns_in_pr_rfq_table
 */
class m240112_053327_add_columns_in_pr_rfq_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_rfq', 'is_early_procurement', $this->boolean()->defaultValue(false));
        $this->addColumn('pr_rfq', 'source_of_fund', $this->string());
        $this->addColumn('pr_rfq', 'mooe_amount', $this->decimal(10, 2));
        $this->addColumn('pr_rfq', 'co_amount', $this->decimal(10, 2));
        $this->addColumn('pr_rfq', 'fk_mode_of_procurement_id', $this->integer());
        $this->createIndex("idx-pr_rfq-fk_mode_of_procurement_id", 'pr_rfq', 'fk_mode_of_procurement_id');
        $this->addForeignKey(
            "fk-pr_rfq-fk_mode_of_procurement_id",
            'pr_rfq',
            'fk_mode_of_procurement_id',
            'pr_mode_of_procurement',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {


        $this->dropForeignKey(
            "fk-pr_rfq-fk_mode_of_procurement_id",
            'pr_rfq'
        );
        $this->dropIndex("idx-pr_rfq-fk_mode_of_procurement_id", 'pr_rfq');
        $this->dropColumn('pr_rfq', 'is_early_procurement');
        $this->dropColumn('pr_rfq', 'source_of_fund');
        $this->dropColumn('pr_rfq', 'mooe_amount');
        $this->dropColumn('pr_rfq', 'co_amount');
        $this->dropColumn('pr_rfq', 'fk_mode_of_procurement_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240112_053327_add_columns_in_pr_rfq_table cannot be reverted.\n";

        return false;
    }
    */
}
