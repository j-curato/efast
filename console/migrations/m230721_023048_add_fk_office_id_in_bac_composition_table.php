<?php

use yii\db\Migration;

/**
 * Class m230721_023048_add_fk_office_id_in_bac_composition_table
 */
class m230721_023048_add_fk_office_id_in_bac_composition_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bac_composition', 'fk_office_id', $this->integer());
        $this->createIndex('idx-bac-fk_office_id', 'bac_composition', 'fk_office_id');
        $this->addForeignKey('fk-bac-fk_office_id', 'bac_composition', 'fk_office_id', 'office', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-bac-fk_office_id', 'bac_composition');
        $this->dropIndex('idx-bac-fk_office_id', 'bac_composition');
        $this->dropColumn('bac_composition', 'fk_office_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230721_023048_add_fk_office_id_in_bac_composition_table cannot be reverted.\n";

        return false;
    }
    */
}
