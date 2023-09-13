<?php

use yii\db\Migration;

/**
 * Class m230913_073212_add_created_at_in_payee_table
 */
class m230913_073212_add_created_at_in_payee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payee', 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('payee', 'created_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230913_073212_add_created_at_in_payee_table cannot be reverted.\n";

        return false;
    }
    */
}
