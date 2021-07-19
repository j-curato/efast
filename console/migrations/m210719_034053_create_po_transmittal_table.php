<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%po_transmittal}}`.
 */
class m210719_034053_create_po_transmittal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%po_transmittal}}', [
           
            'transmittal_number'=>$this->string(),
            'date'=>$this->date(),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->addPrimaryKey('transmittal_number','po_transmittal','transmittal_number');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%po_transmittal}}');
    }
}
