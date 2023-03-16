<?php

use yii\db\Migration;

/**
 * Class m230316_075841_add_fk_office_id_in_iirup_table
 */
class m230316_075841_add_fk_office_id_in_iirup_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->query();
        $this->addColumn('iirup', 'fk_office_id', $this->integer());
        $this->createIndex('idx-iirup-fk_office_id', 'iirup', 'fk_office_id');
        $this->addForeignKey('fk-iirup-fk_office_id', 'iirup', 'fk_office_id', 'office', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-iirup-fk_office_id', 'iirup');
        $this->dropIndex('idx-iirup-fk_office_id', 'iirup');
        $this->dropColumn('iirup', 'fk_office_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230316_075841_add_fk_office_id_in_iirup_table cannot be reverted.\n";

        return false;
    }
    */
}
