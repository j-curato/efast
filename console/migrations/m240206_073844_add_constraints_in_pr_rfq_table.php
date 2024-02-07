<?php

use yii\db\Migration;

/**
 * Class m240206_073844_add_constraints_in_pr_rfq_table
 */
class m240206_073844_add_constraints_in_pr_rfq_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS =0")->query();
        $this->alterColumn('pr_rfq', 'bac_composition_id', $this->integer());
        $this->createIndex("idx-pr_rfq-bac_composition_id", 'pr_rfq', 'bac_composition_id');
        $this->addForeignKey("fk-pr_rfq-bac_composition_id", 'pr_rfq', 'bac_composition_id', 'bac_composition', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey("fk-pr_rfq-bac_composition_id", 'pr_rfq');
        $this->dropIndex("idx-pr_rfq-bac_composition_id", 'pr_rfq');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240206_073844_add_constraints_in_pr_rfq_table cannot be reverted.\n";

        return false;
    }
    */
}
