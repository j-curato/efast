<?php

use yii\db\Migration;

/**
 * Class m230112_084943_add_fk_constraints_in_supplemental_ppmp_non_cse_items_table
 */
class m230112_084943_add_fk_constraints_in_supplemental_ppmp_non_cse_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('supplemental_ppmp_non_cse_items', 'fk_supplemental_ppmp_non_cse_id', $this->integer());
        $this->alterColumn('supplemental_ppmp_non_cse_items', 'fk_pr_stock_id', $this->bigInteger());
        $this->createIndex('idx-fk_supplemental_ppmp_non_cse_id', 'supplemental_ppmp_non_cse_items', 'fk_supplemental_ppmp_non_cse_id');
        $this->createIndex('idx-fk_unit_of_measure_id', 'supplemental_ppmp_non_cse_items', 'fk_unit_of_measure_id');
        $this->createIndex('idx-fk_pr_stock_id', 'supplemental_ppmp_non_cse_items', 'fk_pr_stock_id');
        // // CREATE FK
        $this->addForeignKey(
            'fk-fk_supplemental_ppmp_non_cse_id',
            'supplemental_ppmp_non_cse_items',
            'fk_supplemental_ppmp_non_cse_id',
            'supplemental_ppmp_non_cse',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-fk_unit_of_measure_id',
            'supplemental_ppmp_non_cse_items',
            'fk_unit_of_measure_id',
            'unit_of_measure',
            'id',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-fk_pr_stock_id',
            'supplemental_ppmp_non_cse_items',
            'fk_pr_stock_id',
            'pr_stock',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-fk_unit_of_measure_id', 'supplemental_ppmp_non_cse_items');
        $this->dropForeignKey('fk-fk_pr_stock_id', 'supplemental_ppmp_non_cse_items');
        $this->dropForeignKey('fk-fk_supplemental_ppmp_non_cse_id', 'supplemental_ppmp_non_cse_items');
        $this->dropIndex('idx-fk_supplemental_ppmp_non_cse_id', 'supplemental_ppmp_non_cse_items');
        $this->dropIndex('idx-fk_unit_of_measure_id', 'supplemental_ppmp_non_cse_items');
        $this->dropIndex('idx-fk_pr_stock_id', 'supplemental_ppmp_non_cse_items');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230112_084943_add_fk_constraints_in_supplemental_ppmp_non_cse_items_table cannot be reverted.\n";

        return false;
    }
    */
}
