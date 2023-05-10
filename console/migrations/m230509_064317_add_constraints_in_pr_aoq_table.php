<?php

use yii\db\Migration;

/**
 * Class m230509_064317_add_constraints_in_pr_aoq_table
 */
class m230509_064317_add_constraints_in_pr_aoq_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->execute();
        $this->createIndex('idx-aoq-pr_rfq_id', 'pr_aoq', 'pr_rfq_id');
        $this->addForeignKey('idx-aoq-pr_rfq_id', 'pr_aoq', 'pr_rfq_id', 'pr_rfq', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('idx-aoq-pr_rfq_id', 'pr_aoq');
        $this->dropIndex('idx-aoq-pr_rfq_id', 'pr_aoq');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230509_064317_add_constraints_in_pr_aoq_table cannot be reverted.\n";

        return false;
    }
    */
}
