<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transmittal}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cash_disbursement}}`
 */
class m210423_093828_create_transmittal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transmittal}}', [
            'id' => $this->primaryKey(),
            'cash_disbursement_id' => $this->integer(),
            'transmittal_number' => $this->string(100),
            'location' => $this->string(20),
        ]);

        // creates index for column `cash_disbursement_id`
        $this->createIndex(
            '{{%idx-transmittal-cash_disbursement_id}}',
            '{{%transmittal}}',
            'cash_disbursement_id'
        );

        // add foreign key for table `{{%cash_disbursement}}`
        $this->addForeignKey(
            '{{%fk-transmittal-cash_disbursement_id}}',
            '{{%transmittal}}',
            'cash_disbursement_id',
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
            '{{%fk-transmittal-cash_disbursement_id}}',
            '{{%transmittal}}'
        );

        // drops index for column `cash_disbursement_id`
        $this->dropIndex(
            '{{%idx-transmittal-cash_disbursement_id}}',
            '{{%transmittal}}'
        );

        $this->dropTable('{{%transmittal}}');
    }
}
