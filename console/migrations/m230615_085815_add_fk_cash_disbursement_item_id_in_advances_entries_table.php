<?php

use yii\db\Migration;

/**
 * Class m230615_085815_add_fk_cash_disbursement_item_id_in_advances_entries_table
 */
class m230615_085815_add_fk_cash_disbursement_item_id_in_advances_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advances_entries', 'fk_cash_disbursement_item_id', $this->integer());
        $this->createIndex('idx-ad-ent-fk_cash_disbursement_item_id', 'advances_entries', 'fk_cash_disbursement_item_id');
        $this->addForeignKey('fk-ad-ent-fk_cash_disbursement_item_id', 'advances_entries', 'fk_cash_disbursement_item_id', 'cash_disbursement_items', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-ad-ent-fk_cash_disbursement_item_id', 'advances_entries');
        $this->dropIndex('idx-ad-ent-fk_cash_disbursement_item_id', 'advances_entries');
        $this->dropColumn('advances_entries', 'fk_cash_disbursement_item_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230615_085815_add_fk_cash_disbursement_item_id_in_advances_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
