<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_mg_liquidation_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tbl_mg_liquidations}}`
 * - `{{%tbl_notification_to_pay}}`
 */
class m231120_072935_create_tbl_mg_liquidation_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_mg_liquidation_items}}', [
            'id' => $this->primaryKey(),
            'fk_mg_liquidation_id' => $this->bigInteger(),
            'fk_notification_to_pay_id' => $this->bigInteger(),
            'date' => $this->date(),
            'is_deleted' => $this->boolean()->defaultValue(false),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // creates index for column `fk_mg_liquidation_id`
        $this->createIndex(
            '{{%idx-tbl_mg_liquidation_items-fk_mg_liquidation_id}}',
            '{{%tbl_mg_liquidation_items}}',
            'fk_mg_liquidation_id'
        );

        // add foreign key for table `{{%tbl_mg_liquidations}}`
        $this->addForeignKey(
            '{{%fk-tbl_mg_liquidation_items-fk_mg_liquidation_id}}',
            '{{%tbl_mg_liquidation_items}}',
            'fk_mg_liquidation_id',
            '{{%tbl_mg_liquidations}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fk_notification_to_pay_id`
        $this->createIndex(
            '{{%idx-tbl_mg_liquidation_items-fk_notification_to_pay_id}}',
            '{{%tbl_mg_liquidation_items}}',
            'fk_notification_to_pay_id'
        );

        // add foreign key for table `{{%tbl_notification_to_pay}}`
        $this->addForeignKey(
            '{{%fk-tbl_mg_liquidation_items-fk_notification_to_pay_id}}',
            '{{%tbl_mg_liquidation_items}}',
            'fk_notification_to_pay_id',
            '{{%tbl_notification_to_pay}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%tbl_mg_liquidations}}`
        $this->dropForeignKey(
            '{{%fk-tbl_mg_liquidation_items-fk_mg_liquidation_id}}',
            '{{%tbl_mg_liquidation_items}}'
        );

        // drops index for column `fk_mg_liquidation_id`
        $this->dropIndex(
            '{{%idx-tbl_mg_liquidation_items-fk_mg_liquidation_id}}',
            '{{%tbl_mg_liquidation_items}}'
        );

        // drops foreign key for table `{{%tbl_notification_to_pay}}`
        $this->dropForeignKey(
            '{{%fk-tbl_mg_liquidation_items-fk_notification_to_pay_id}}',
            '{{%tbl_mg_liquidation_items}}'
        );

        // drops index for column `fk_notification_to_pay_id`
        $this->dropIndex(
            '{{%idx-tbl_mg_liquidation_items-fk_notification_to_pay_id}}',
            '{{%tbl_mg_liquidation_items}}'
        );

        $this->dropTable('{{%tbl_mg_liquidation_items}}');
    }
}
