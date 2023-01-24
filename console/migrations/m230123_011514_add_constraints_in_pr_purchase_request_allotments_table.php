<?php

use yii\db\Migration;

/**
 * Class m230123_011514_add_constraints_in_pr_purchase_request_allotments_table
 */
class m230123_011514_add_constraints_in_pr_purchase_request_allotments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()

    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->execute();
        $this->createIndex('idx-fk_purchase_request_id', 'pr_purchase_request_allotments', 'fk_purchase_request_id');
        $this->addForeignKey('fk-fk_purchase_request_id', 'pr_purchase_request_allotments', 'fk_purchase_request_id', 'pr_purchase_request', 'id', 'CASCADE');
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=1")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-fk_purchase_request_id', 'pr_purchase_request_allotments');
        $this->dropIndex('idx-fk_purchase_request_id', 'pr_purchase_request_allotments');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230123_011514_add_constraints_in_pr_purchase_request_allotments_table cannot be reverted.\n";

        return false;
    }
    */
}
