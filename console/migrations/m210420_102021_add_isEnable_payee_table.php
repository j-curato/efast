<?php

use yii\db\Migration;

/**
 * Class m210420_102021_add_isEnable_payee_table
 */
class m210420_102021_add_isEnable_payee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payee','isEnable',$this->boolean()->defaultValue(1));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('payee','isEnable');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210420_102021_add_isEnable_payee_table cannot be reverted.\n";

        return false;
    }
    */
}
