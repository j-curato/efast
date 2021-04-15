<?php

use yii\db\Migration;

/**
 * Class m210414_033230_add_transction_type_to_dv_aucs_table
 */
class m210414_033230_add_transction_type_to_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_aucs', 'transaction_type', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_aucs', 'transaction_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210414_033230_add_transction_type_to_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}
