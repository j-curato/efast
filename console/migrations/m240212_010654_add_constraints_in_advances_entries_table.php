<?php

use yii\db\Migration;

/**
 * Class m240212_010654_add_constraints_in_advances_entries_table
 */
class m240212_010654_add_constraints_in_advances_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->query();
        $this->createIndex('idx-fk_fund_source_type_id-advances_entries', 'advances_entries', 'fk_fund_source_type_id');
        $this->addForeignKey('fk-fk_fund_source_type_id-advances_entries', 'advances_entries', 'fk_fund_source_type_id', 'fund_source_type', 'id', 'RESTRICT');

        $this->createIndex('idx-fk_advances_report_type_id-advances_entries', 'advances_entries', 'fk_advances_report_type_id');
        $this->addForeignKey('fk-fk_advances_report_type_id-advances_entries', 'advances_entries', 'fk_advances_report_type_id', 'advances_report_type', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-fk_fund_source_type_id-advances_entries', 'advances_entries');
        $this->dropIndex('idx-fk_fund_source_type_id-advances_entries', 'advances_entries');

        $this->dropForeignKey('fk-fk_advances_report_type_id-advances_entries', 'advances_entries');
        $this->dropIndex('idx-fk_advances_report_type_id-advances_entries', 'advances_entries');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240212_010654_add_constraints_in_advances_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
