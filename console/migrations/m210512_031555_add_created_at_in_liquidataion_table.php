<?php

use yii\db\Migration;

/**
 * Class m210512_031555_add_created_at_in_liquidataion_table
 */
class m210512_031555_add_created_at_in_liquidataion_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$this->addColumn('liquidation','created_at',$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation','created_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210512_031555_add_created_at_in_liquidataion_table cannot be reverted.\n";

        return false;
    }
    */
}
