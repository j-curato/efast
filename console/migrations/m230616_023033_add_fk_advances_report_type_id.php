<?php

use yii\db\Migration;

/**
 * Class m230616_023033_add_fk_advances_report_type_id
 */
class m230616_023033_add_fk_advances_report_type_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advances_entries', 'fk_advances_report_type_id', $this->integer());
        $this->createIndex('idx-adv-ent-fk_advances_report_type_id', 'advances_entries', 'fk_advances_report_type_id');
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->query();
        $this->addForeignKey('fk-adv-ent-fk_advances_report_type_id', 'advances_entries', 'fk_advances_report_type_id', 'advances_report_type', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-adv-ent-fk_advances_report_type_id', 'advances_entries');
        $this->dropIndex('idx-adv-ent-fk_advances_report_type_id', 'advances_entries');
        $this->dropColumn('advances_entries', 'fk_advances_report_type_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230616_023033_add_fk_advances_report_type_id cannot be reverted.\n";

        return false;
    }
    */
}
