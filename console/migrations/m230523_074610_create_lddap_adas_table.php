<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%lddap_adas}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cash_disbursement}}`
 */
class m230523_074610_create_lddap_adas_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%lddap_adas}}', [
            'id' => $this->primaryKey(),
            'fk_cash_disbursement_id' => $this->integer(),
            'serial_number' => $this->string()->notNull()->unique(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_cash_disbursement_id`
        $this->createIndex(
            '{{%idx-lddap_adas-fk_cash_disbursement_id}}',
            '{{%lddap_adas}}',
            'fk_cash_disbursement_id'
        );

        // add foreign key for table `{{%cash_disbursement}}`
        $this->addForeignKey(
            '{{%fk-lddap_adas-fk_cash_disbursement_id}}',
            '{{%lddap_adas}}',
            'fk_cash_disbursement_id',
            '{{%cash_disbursement}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%cash_disbursement}}`
        $this->dropForeignKey(
            '{{%fk-lddap_adas-fk_cash_disbursement_id}}',
            '{{%lddap_adas}}'
        );

        // drops index for column `fk_cash_disbursement_id`
        $this->dropIndex(
            '{{%idx-lddap_adas-fk_cash_disbursement_id}}',
            '{{%lddap_adas}}'
        );

        $this->dropTable('{{%lddap_adas}}');
    }
}
