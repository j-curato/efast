<?php

use yii\db\Migration;

/**
 * Class m210616_061217_remove_advances_id_from_liquidation_entries_table
 */
class m210616_061217_remove_advances_id_from_liquidation_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drops foreign key for table `{{%advances}}`
        $this->dropForeignKey(
            '{{%fk-liquidation_entries-advances_id}}',
            '{{%liquidation_entries}}'
        );

        // drops index for column `advances_id`
        $this->dropIndex(
            '{{%idx-liquidation_entries-advances_id}}',
            '{{%liquidation_entries}}'
        );
        $this->dropColumn('liquidation_entries', 'advances_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('liquidation_entries', 'advances_id', $this->integer());
        $this->createIndex(
            '{{%idx-liquidation_entries-advances_id}}',
            '{{%liquidation_entries}}',
            'advances_id'
        );

        // add foreign key for table `{{%transaction}}`
        $this->addForeignKey(
            '{{%fk-liquidation_entries-advances_id}}',
            '{{%liquidation_entries}}',
            'advances_id',
            '{{%liquidation}}',
            'id',
            'CASCADE'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210616_061217_remove_advances_id_from_liquidation_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
