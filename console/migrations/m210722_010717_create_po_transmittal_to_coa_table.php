<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%po_transmittal_to_coa}}`.
 */
class m210722_010717_create_po_transmittal_to_coa_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%po_transmittal_to_coa}}', [
            'transmittal_number'=>$this->string()->notNull(),
            'date'=>$this->date(),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->addPrimaryKey('transmittal_number','po_transmittal_to_coa','transmittal_number');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%po_transmittal_to_coa}}');
    }
}
