<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%allotment_modification_advice_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%allotment_modification_advice}}`
 * - `{{%record_allotment_entries}}`
 */
class m230823_020018_create_allotment_modification_advice_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%allotment_modification_advice_items}}', [
            'id' => $this->primaryKey(),
            'fk_allotment_modification_advice_id' => $this->bigInteger(),
            'fk_record_allotment_entries_id' => $this->integer(),
            'amount' => $this->decimal(20, 2)->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(false),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_allotment_modification_advice_id`
        $this->createIndex(
            '{{%idx-alt_mdftn_advc_itm-fk_allotment_modification_advice_id}}',
            '{{%allotment_modification_advice_items}}',
            'fk_allotment_modification_advice_id'
        );

        // add foreign key for table `{{%allotment_modification_advice}}`
        $this->addForeignKey(
            '{{%fk-alt_mdftn_advc_itm-fk_allotment_modification_advice_id}}',
            '{{%allotment_modification_advice_items}}',
            'fk_allotment_modification_advice_id',
            '{{%allotment_modification_advice}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fk_record_allotment_entries_id`
        $this->createIndex(
            '{{%idx-alt_mdftn_advc_itm-fk_record_allotment_entries_id}}',
            '{{%allotment_modification_advice_items}}',
            'fk_record_allotment_entries_id'
        );

        // add foreign key for table `{{%record_allotment_entries}}`
        $this->addForeignKey(
            '{{%fk-alt_mdftn_advc_itm-fk_record_allotment_entries_id}}',
            '{{%allotment_modification_advice_items}}',
            'fk_record_allotment_entries_id',
            '{{%record_allotment_entries}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%allotment_modification_advice}}`
        $this->dropForeignKey(
            '{{%fk-alt_mdftn_advc_itm-fk_allotment_modification_advice_id}}',
            '{{%allotment_modification_advice_items}}'
        );

        // drops index for column `fk_allotment_modification_advice_id`
        $this->dropIndex(
            '{{%idx-alt_mdftn_advc_itm-fk_allotment_modification_advice_id}}',
            '{{%allotment_modification_advice_items}}'
        );

        // drops foreign key for table `{{%record_allotment_entries}}`
        $this->dropForeignKey(
            '{{%fk-alt_mdftn_advc_itm-fk_record_allotment_entries_id}}',
            '{{%allotment_modification_advice_items}}'
        );

        // drops index for column `fk_record_allotment_entries_id`
        $this->dropIndex(
            '{{%idx-alt_mdftn_advc_itm-fk_record_allotment_entries_id}}',
            '{{%allotment_modification_advice_items}}'
        );

        $this->dropTable('{{%allotment_modification_advice_items}}');
    }
}
