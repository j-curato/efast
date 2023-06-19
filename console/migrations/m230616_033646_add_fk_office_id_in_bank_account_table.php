<?php

use yii\db\Migration;

/**
 * Class m230616_033646_add_fk_office_id_in_bank_account_table
 */
class m230616_033646_add_fk_office_id_in_bank_account_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bank_account', 'fk_office_id', $this->integer());
        $this->createIndex('idx-bank_account-fk_office_id', 'bank_account', 'fk_office_id');
        $this->addForeignKey('fk-bank_account-fk_office_id', 'bank_account', 'fk_office_id', 'office', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-bank_account-fk_office_id', 'bank_account');
        $this->dropIndex('idx-bank_account-fk_office_id', 'bank_account');
        $this->dropColumn('bank_account', 'fk_office_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230616_033646_add_fk_office_id_in_bank_account_table cannot be reverted.\n";

        return false;
    }
    */
}
