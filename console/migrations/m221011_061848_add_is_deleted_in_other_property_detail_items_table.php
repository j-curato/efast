<?php

use yii\db\Migration;

/**
 * Class m221011_061848_add_is_deleted_in_other_property_detail_items_table
 */
class m221011_061848_add_is_deleted_in_other_property_detail_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('other_property_detail_items', 'is_deleted', $this->boolean()->defaultValue(0));
        $this->addColumn('other_property_detail_items', 'deleted_at', $this->timestamp()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('other_property_detail_items', 'deleted_at');
        $this->dropColumn('other_property_detail_items', 'is_deleted');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221011_061848_add_is_deleted_in_other_property_detail_items_table cannot be reverted.\n";

        return false;
    }
    */
}
