<?php

use yii\db\Migration;

/**
 * Class m231110_052008_add_fk_bac_composition_member_id_in_notice_of_postponement_table
 */
class m231110_052008_add_fk_bac_composition_member_id_in_notice_of_postponement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('notice_of_postponement', 'fk_bac_composition_member_id', $this->integer());
        $this->createIndex('idx-nop-fk_bac_composition_member_id', 'notice_of_postponement', 'fk_bac_composition_member_id');
        $this->addForeignKey('fk-nop-fk_bac_composition_member_id', 'notice_of_postponement', 'fk_bac_composition_member_id', 'bac_composition_member', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-nop-fk_bac_composition_member_id', 'notice_of_postponement',);
        $this->dropIndex('idx-nop-fk_bac_composition_member_id', 'notice_of_postponement');
        $this->dropColumn('notice_of_postponement', 'fk_bac_composition_member_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231110_052008_add_fk_bac_composition_member_id_in_notice_of_postponement_table cannot be reverted.\n";

        return false;
    }
    */
}
