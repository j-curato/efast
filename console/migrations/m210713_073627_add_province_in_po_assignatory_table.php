<?php

use yii\db\Migration;

/**
 * Class m210713_073627_add_province_in_po_assignatory_table
 */
class m210713_073627_add_province_in_po_assignatory_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('po_asignatory','province',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('po_asignatory','province');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210713_073627_add_province_in_po_assignatory_table cannot be reverted.\n";

        return false;
    }
    */
}
