<?php

use yii\db\Migration;

/**
 * Class m240206_070730_add_constraints_in_supplemental_ppmp_cse_table
 */
class m240206_070730_add_constraints_in_supplemental_ppmp_cse_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS =0")->query();
        $this->createIndex('idx-supplemental_ppmp_cse-fk_pr_stock_id', 'supplemental_ppmp_cse', 'fk_pr_stock_id');
        $this->addForeignKey('fk-supplemental_ppmp_cse-fk_pr_stock_id', 'supplemental_ppmp_cse', 'fk_pr_stock_id', 'pr_stock', 'id', 'RESTRICT');

        $this->createIndex('idx-supplemental_ppmp_cse-fk_unit_of_measure_id', 'supplemental_ppmp_cse', 'fk_unit_of_measure_id');
        $this->addForeignKey('fk-supplemental_ppmp_cse-fk_unit_of_measure_id', 'supplemental_ppmp_cse', 'fk_unit_of_measure_id', 'unit_of_measure', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-supplemental_ppmp_cse-fk_pr_stock_id', 'supplemental_ppmp_cse');
        $this->dropForeignKey('fk-supplemental_ppmp_cse-fk_pr_stock_id', 'supplemental_ppmp_cse');

        $this->dropIndex('idx-supplemental_ppmp_cse-fk_unit_of_measure_id', 'supplemental_ppmp_cse');
        $this->dropForeignKey('fk-supplemental_ppmp_cse-fk_unit_of_measure_id', 'supplemental_ppmp_cse');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240206_070730_add_constraints_in_supplemental_ppmp_cse_table cannot be reverted.\n";

        return false;
    }
    */
}
