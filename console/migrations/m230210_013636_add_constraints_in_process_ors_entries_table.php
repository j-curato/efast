<?php

use yii\db\Migration;

/**
 * Class m230210_013636_add_constraints_in_process_ors_entries_table
 */
class m230210_013636_add_constraints_in_process_ors_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->query();

        $this->createIndex('idx-record_allotment_entries_id', 'process_ors_entries', 'record_allotment_entries_id');
        $this->addForeignKey('fk-ors-entry-record_allotment_entries_id', 'process_ors_entries', 'record_allotment_entries_id', 'record_allotment_entries', 'id', 'RESTRICT');
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=1")->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-ors-entry-record_allotment_entries_id', 'process_ors_entries');
        $this->dropIndex('idx-record_allotment_entries_id', 'process_ors_entries');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230210_013636_add_constraints_in_process_ors_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
