<?php

use yii\db\Migration;

/**
 * Class m231003_053134_add_constraints_in_inspection_report_items_table
 */
class m231003_053134_add_constraints_in_inspection_report_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        YIi::$app->db->createCommand('SET FOREIGN_KEY_CHECKS= 0')->query();
        $this->createIndex('idx-ir-fk_request_for_inspection_item_id', 'inspection_report_items', 'fk_request_for_inspection_item_id');
        $this->addForeignKey('fk-ir-fk_request_for_inspection_item_id', 'inspection_report_items', 'fk_request_for_inspection_item_id', 'request_for_inspection_items', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-ir-fk_request_for_inspection_item_id', 'inspection_report_items');
        $this->dropIndex('idx-ir-fk_request_for_inspection_item_id', 'inspection_report_items');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231003_053134_add_constraints_in_inspection_report_items_table cannot be reverted.\n";

        return false;
    }
    */
}
