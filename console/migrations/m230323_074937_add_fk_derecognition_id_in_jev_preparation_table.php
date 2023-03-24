<?php

use yii\db\Migration;

/**
 * Class m230323_074937_add_fk_derecognition_id_in_jev_preparation_table
 */
class m230323_074937_add_fk_derecognition_id_in_jev_preparation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('jev_preparation', 'fk_derecognition_id', $this->bigInteger());
        $this->createIndex('idx-jev-fk_derecognition_id', 'jev_preparation', 'fk_derecognition_id');
        $this->addForeignKey('fk-jev-fk_derecognition_id', 'jev_preparation', 'fk_derecognition_id', 'derecognition', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-jev-fk_derecognition_id', 'jev_preparation');
        $this->dropIndex('idx-jev-fk_derecognition_id', 'jev_preparation');
        $this->dropColumn('jev_preparation', 'fk_derecognition_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230323_074937_add_fk_derecognition_id_in_jev_preparation_table cannot be reverted.\n";

        return false;
    }
    */
}
