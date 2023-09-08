<?php

use yii\db\Migration;

/**
 * Class m230907_081628_add_fk_major_account_id_in_table
 */
class m230907_081628_add_fk_major_account_id_in_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('record_allotments', 'fk_major_account_id', $this->integer());
        $this->createIndex('idx-recAlot-fk_major_account_id', 'record_allotments', 'fk_major_account_id');
        $this->addForeignKey('fk-recAlot-fk_major_account_id', 'record_allotments', 'fk_major_account_id', 'major_accounts', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-recAlot-fk_major_account_id', 'record_allotments');
        $this->dropIndex('idx-recAlot-fk_major_account_id', 'record_allotments');
        $this->dropColumn('record_allotments', 'fk_major_account_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230907_081628_add_fk_major_account_id_in_table cannot be reverted.\n";

        return false;
    }
    */
}
