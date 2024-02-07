<?php

use yii\db\Migration;

/**
 * Class m240206_071055_add_constraints_in_supplemental_ppmp_non_cse_table
 */
class m240206_071055_add_constraints_in_supplemental_ppmp_non_cse_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {



        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS =0")->query();
        $this->createIndex('idx-supplemental_ppmp_non_cse-fk_mode_of_procurement_id', 'supplemental_ppmp_non_cse', 'fk_mode_of_procurement_id');
        $this->addForeignKey('fk-supplemental_ppmp_non_cse-fk_mode_of_procurement_id', 'supplemental_ppmp_non_cse', 'fk_mode_of_procurement_id', 'pr_mode_of_procurement', 'id', 'RESTRICT');

        $this->createIndex('idx-supplemental_ppmp_non_cse-fk_fund_source_id', 'supplemental_ppmp_non_cse', 'fk_fund_source_id');
        $this->addForeignKey('fk-supplemental_ppmp_non_cse-fk_fund_source_id', 'supplemental_ppmp_non_cse', 'fk_fund_source_id', 'fund_source', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-supplemental_ppmp_non_cse-fk_mode_of_procurement_id', 'supplemental_ppmp_non_cse');
        $this->dropIndex('idx-supplemental_ppmp_non_cse-fk_mode_of_procurement_id', 'supplemental_ppmp_non_cse');

        $this->dropForeignKey('fk-supplemental_ppmp_non_cse-fk_fund_source_id', 'supplemental_ppmp_non_cse');
        $this->dropIndex('idx-supplemental_ppmp_non_cse-fk_fund_source_id', 'supplemental_ppmp_non_cse');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240206_071055_add_constraints_in_supplemental_ppmp_non_cse_table cannot be reverted.\n";

        return false;
    }
    */
}
