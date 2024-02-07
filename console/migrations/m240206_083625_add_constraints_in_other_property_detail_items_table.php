<?php

use yii\db\Migration;

/**
 * Class m240206_083625_add_constraints_in_other_property_detail_items_table
 */
class m240206_083625_add_constraints_in_other_property_detail_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS =0")->query();
        $this->createIndex("idx-other_property_detail_items-book_id", 'other_property_detail_items', 'book_id');
        $this->addForeignKey("fk-other_property_detail_items-book_id", 'other_property_detail_items', 'book_id', 'books', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey("fk-other_property_detail_items-book_id", 'other_property_detail_items');
        $this->dropIndex("idx-other_property_detail_items-book_id", 'other_property_detail_items');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240206_083625_add_constraints_in_other_property_detail_items_table cannot be reverted.\n";

        return false;
    }
    */
}
