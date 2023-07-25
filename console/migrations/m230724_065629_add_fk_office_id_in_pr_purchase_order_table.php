<?php

use yii\db\Migration;

/**
 * Class m230724_065629_add_fk_office_id_in_pr_purchase_order_table
 */
class m230724_065629_add_fk_office_id_in_pr_purchase_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_purchase_order', 'fk_office_id', $this->integer());
        $this->createIndex('idx-PO-fk_office_id', 'pr_purchase_order', 'fk_office_id');
        $this->addForeignKey('fk-PO-fk_office_id', 'pr_purchase_order', 'fk_office_id', 'office', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-PO-fk_office_id', 'pr_purchase_order');
        $this->dropIndex('idx-PO-fk_office_id', 'pr_purchase_order');
        $this->dropColumn('pr_purchase_order', 'fk_office_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230724_065629_add_fk_office_id_in_pr_purchase_order_table cannot be reverted.\n";

        return false;
    }
    */
}
