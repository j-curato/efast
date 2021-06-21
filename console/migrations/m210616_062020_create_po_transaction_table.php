<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%po_transaction}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%responsibility_center}}`
 */
class m210616_062020_create_po_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%po_transaction}}', [
            'id' => $this->primaryKey(),
            'responsibility_center_id' => $this->integer(),
            'payee' =>$this->text(),
            'particular'=>$this->text(),
            'amount'=>$this->decimal(10,2),
            'payroll_number'=>$this->string(100),
            'tracking_numner'=>$this->string(100)
        ]);

        // creates index for column `responsibility_center_id`
        $this->createIndex(
            '{{%idx-po_transaction-responsibility_center_id}}',
            '{{%po_transaction}}',
            'responsibility_center_id'
        );

        // add foreign key for table `{{%responsibility_center}}`
        $this->addForeignKey(
            '{{%fk-po_transaction-responsibility_center_id}}',
            '{{%po_transaction}}',
            'responsibility_center_id',
            '{{%responsibility_center}}',
            'id',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%responsibility_center}}`
        $this->dropForeignKey(
            '{{%fk-po_transaction-responsibility_center_id}}',
            '{{%po_transaction}}'
        );

        // drops index for column `responsibility_center_id`
        $this->dropIndex(
            '{{%idx-po_transaction-responsibility_center_id}}',
            '{{%po_transaction}}'
        );

        $this->dropTable('{{%po_transaction}}');
    }
}
