<?php

use yii\db\Migration;

/**
 * Class m230815_015445_add_fk_created_by_in_request_for_inspection_table
 */
class m230815_015445_add_fk_created_by_in_request_for_inspection_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('request_for_inspection', 'fk_created_by', $this->bigInteger());
        $this->createIndex('idx-rfi-fk_created_by', 'request_for_inspection', 'fk_created_by');
        $this->addForeignKey('fk-rfi-fk_created_by', 'request_for_inspection', 'fk_created_by', 'user', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-rfi-fk_created_by', 'request_for_inspection');
        $this->dropIndex('idx-rfi-fk_created_by', 'request_for_inspection');
        $this->dropColumn('request_for_inspection', 'fk_created_by');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230815_015445_add_fk_created_by_in_request_for_inspection_table cannot be reverted.\n";

        return false;
    }
    */
}
