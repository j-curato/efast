<?php

use yii\db\Migration;

/**
 * Class m211112_012355_add_created_at_in_rpcppe_table
 */
class m211112_012355_add_created_at_in_rpcppe_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('rpcppe','created_at',$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('rpcppe','created_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211112_012355_add_created_at_in_rpcppe_table cannot be reverted.\n";

        return false;
    }
    */
}
