<?php

use yii\db\Migration;

/**
 * Class m210319_062328_add_entry_id_to_raouds_table
 */
class m210319_062328_add_entry_id_to_raouds_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('raouds','record_allotment_entries_id',$this->integer());
        // creates index for column `chart_of_account_id`
        $this->createIndex(
            '{{%idx-raouds-record_allotment_entries_id}}',
            '{{%raouds}}',
            'record_allotment_entries_id'
        );

        // add foreign key for table `{{%chart_of_accounts}}`
        $this->addForeignKey(
            '{{%fk-raouds-record_allotment_entries_id}}',
            '{{%raouds}}',
            'record_allotment_entries_id',
            '{{%record_allotment_entries}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%chart_of_accounts}}`
        $this->dropForeignKey(
            '{{%fk-raouds-record_allotment_entries_id}}',
            '{{%raouds}}'
        );

        // drops index for column `record_allotment_entries_id`
        $this->dropIndex(
            '{{%idx-raouds-record_allotment_entries_id}}',
            '{{%raouds}}'
        );
        $this->dropColumn('raouds','record_allotment_entries_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210319_062328_add_entry_id_to_raouds_table cannot be reverted.\n";

        return false;
    }
    */
}
