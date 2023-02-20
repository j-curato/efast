<?php

use yii\db\Migration;

/**
 * Class m230217_011800_add_fk_office_id_in_advances_table
 */
class m230217_011800_add_fk_office_id_in_advances_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advances', 'fk_office_id', $this->integer());
        $this->createIndex('idx-adv-fk_office_id', 'advances', 'fk_office_id');
        $this->addForeignKey('fk-adv-fk_office_id', 'advances', 'fk_office_id', 'office', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-adv-fk_office_id', 'advances');
        $this->dropIndex('idx-adv-fk_office_id', 'advances');
        $this->dropColumn('advances', 'fk_office_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230217_011800_add_fk_office_id_in_advances_table cannot be reverted.\n";

        return false;
    }
    */
}
