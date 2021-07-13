<?php

use yii\db\Migration;

/**
 * Class m210713_090359_add_province_in_po_responsibility_center_table
 */
class m210713_090359_add_province_in_po_responsibility_center_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('po_responsibility_center', 'province', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('po_responsibility_center', 'province');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210713_090359_add_province_in_po_responsibility_center_table cannot be reverted.\n";

        return false;
    }
    */
}
