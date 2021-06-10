<?php

use yii\db\Migration;

/**
 * Class m210512_031423_add_created_at_in_dv_aucs_table
 */
class m210512_031423_add_created_at_in_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_aucs','created_at',$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_aucs','created_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210512_031423_add_created_at_in_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}
