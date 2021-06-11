<?php

use yii\db\Migration;

/**
 * Class m210611_013126_add_advances_entries_id_in_liquidation_entries
 */
class m210611_013126_add_advances_entries_id_in_liquidation_entries extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation_entries','advances_entries_id',$this->integer());

                // creates index for column `advances_entries_id`
                $this->createIndex(
                    '{{%idx-liquidation_entries-advances_entries_id}}',
                    '{{%liquidation_entries}}',
                    'advances_entries_id'
                );
        
                // add foreign key for table `{{%advances_entries}}`
                $this->addForeignKey(
                    '{{%fk-liquidation_entries-advances_entries_id}}',
                    '{{%liquidation_entries}}',
                    'advances_entries_id',
                    '{{%advances_entries}}',
                    'id',
                    'CASCADE'
                );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%liquidation}}`
        $this->dropForeignKey(
            '{{%fk-liquidation_entries-advances_entries_id}}',
            '{{%liquidation_entries}}'
        );

        // drops index for column `advances_entries_id`
        $this->dropIndex(
            '{{%idx-liquidation_entries-advances_entries_id}}',
            '{{%liquidation_entries}}'
        );
        $this->dropColumn('liquidation_entries','advances_entries_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210611_013126_add_advances_entries_id_in_liquidation_entries cannot be reverted.\n";

        return false;
    }
    */
}
