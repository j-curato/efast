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
            'fk_allotment_modification_advice_id' => $this->bigInteger()->notNull(),
            'fk_office_id' => $this->integer()->notNull(),
            'fk_division_id' => $this->bigInteger()->notNull(),
            'fk_chart_of_account_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(20, 2)->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(false),
            'isNegative' => $this->boolean()->notNull(),
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

        $this->createIndex('idx-maf-fk_office_id', 'allotment_modification_advice_items', 'fk_office_id');
        $this->addForeignKey('fk-maf-fk_office_id', 'allotment_modification_advice_items', 'fk_office_id', 'office', 'id', 'RESTRICT');

        $this->createIndex('idx-maf-fk_division_id', 'allotment_modification_advice_items', 'fk_division_id');
        $this->addForeignKey('fk-maf-fk_division_id', 'allotment_modification_advice_items', 'fk_division_id', 'divisions', 'id', 'RESTRICT');

        $this->createIndex('idx-maf-fk_chart_of_account_id', 'allotment_modification_advice_items', 'fk_chart_of_account_id');
        $this->addForeignKey('fk-maf-fk_chart_of_account_id', 'allotment_modification_advice_items', 'fk_chart_of_account_id', 'chart_of_accounts', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%fk-alt_mdftn_advc_itm-fk_allotment_modification_advice_id}}',
            '{{%allotment_modification_advice_items}}'
        );

        // drops index for column `fk_allotment_modification_advice_id`
        $this->dropIndex(
            '{{%idx-alt_mdftn_advc_itm-fk_allotment_modification_advice_id}}',
            '{{%allotment_modification_advice_items}}'
        );


        $this->dropForeignKey('fk-maf-fk_office_id', 'allotment_modification_advice_items');
        $this->dropForeignKey('fk-maf-fk_division_id', 'allotment_modification_advice_items');
        $this->dropForeignKey('fk-maf-fk_chart_of_account_id', 'allotment_modification_advice_items');
        $this->dropIndex('idx-maf-fk_office_id', 'allotment_modification_advice_items');
        $this->dropIndex('idx-maf-fk_division_id', 'allotment_modification_advice_items');
        $this->dropIndex('idx-maf-fk_chart_of_account_id', 'allotment_modification_advice_items');
        $this->dropTable('{{%allotment_modification_advice_items}}');
    }
}
