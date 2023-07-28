<?php

use yii\db\Migration;

/**
 * Class m230728_022334_add_fk_office_id_in_payee_table
 */
class m230728_022334_add_fk_office_id_in_payee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payee', 'fk_office_id', $this->integer());
        $this->createIndex('idx-payee-fk_office_id', 'payee', 'fk_office_id');
        $this->addForeignKey('fk-payee-fk_office_id', 'payee', 'fk_office_id', 'office', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-payee-fk_office_id', 'payee', 'fk_office_id');
        $this->dropIndex('idx-payee-fk_office_id', 'payee');
        $this->dropColumn('payee', 'fk_office_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230728_022334_add_fk_office_id_in_payee_table cannot be reverted.\n";

        return false;
    }
    */
}
