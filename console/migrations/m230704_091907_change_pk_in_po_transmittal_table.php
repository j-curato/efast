<?php

use yii\db\Migration;

/**
 * Class m230704_091907_change_pk_in_po_transmittal_table
 */
class m230704_091907_change_pk_in_po_transmittal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->query();
        $this->alterColumn('po_transmittal', 'id', $this->bigInteger()->after('transmittal_number'));
        $this->alterColumn('po_transmittal', 'transmittal_number', $this->string()->after('id'));
        $this->dropPrimaryKey('PRIMARY', 'po_transmittal');
        $this->addPrimaryKey('pk-id', 'po_transmittal', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->query();
        $this->dropPrimaryKey('PRIMARY', 'po_transmittal');
        $this->addPrimaryKey('pd-id', 'po_transmittal', 'transmittal_number');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230704_091907_change_pk_in_po_transmittal_table cannot be reverted.\n";

        return false;
    }
    */
}
