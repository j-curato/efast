<?php

use yii\db\Migration;

/**
 * Class m230619_082724_remove_constraint_in_advances_table
 */
class m230619_082724_remove_constraint_in_advances_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-adv-ent-fk_advances_report_type_id', 'advances_entries');
        $this->dropIndex('idx-adv-ent-fk_advances_report_type_id', 'advances_entries');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createIndex('idx-adv-ent-fk_advances_report_type_id', 'advances_entries', 'fk_advances_report_type_id');
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->query();
        $this->addForeignKey('fk-adv-ent-fk_advances_report_type_id', 'advances_entries', 'fk_advances_report_type_id', 'advances_report_type', 'id', 'RESTRICT', 'CASCADE');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230619_082724_remove_constraint_in_advances_table cannot be reverted.\n";

        return false;
    }
    */
}
