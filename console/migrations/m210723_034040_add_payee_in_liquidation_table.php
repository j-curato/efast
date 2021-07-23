<?php

use yii\db\Migration;

/**
 * Class m210723_034040_add_payee_in_liquidation_table
 */
class m210723_034040_add_payee_in_liquidation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation','payee',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation','payee');
    }   

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210723_034040_add_payee_in_liquidation_table cannot be reverted.\n";

        return false;
    }
    */
}
