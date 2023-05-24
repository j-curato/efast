<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sliies}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cash_disbursements}}`
 */
class m230522_090008_create_sliies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sliies}}', [
            'id' => $this->primaryKey(),
            'fk_cash_disbursement_id' => $this->integer(),
            'serial_number' => $this->string()->notNull()->unique(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_cash_disbursement_id`
        $this->createIndex(
            '{{%idx-sliies-fk_cash_disbursement_id}}',
            '{{%sliies}}',
            'fk_cash_disbursement_id'
        );

        // add foreign key for table `{{%cash_disbursement}}`
        $this->addForeignKey(
            '{{%fk-sliies-fk_cash_disbursement_id}}',
            '{{%sliies}}',
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
        // drops foreign key for table `{{%cash_disbursements}}`
        $this->dropForeignKey(
            '{{%fk-sliies-fk_cash_disbursement_id}}',
            '{{%sliies}}'
        );

        // drops index for column `fk_cash_disbursement_id`
        $this->dropIndex(
            '{{%idx-sliies-fk_cash_disbursement_id}}',
            '{{%sliies}}'
        );

        $this->dropTable('{{%sliies}}');
    }
}
