<?php

use yii\db\Migration;

/**
 * Class m211112_012211_add_created_at_in_ptr_table
 */
class m211112_012211_add_created_at_in_ptr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ptr','created_at',$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ptr','created_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211112_012211_add_created_at_in_ptr_table cannot be reverted.\n";

        return false;
    }
    */
}
