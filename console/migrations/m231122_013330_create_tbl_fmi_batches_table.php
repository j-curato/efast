<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_fmi_batches}}`.
 */
class m231122_013330_create_tbl_fmi_batches_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_fmi_batches}}', [
            'id' => $this->primaryKey(),
            'batch_name' => $this->text()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->alterColumn('tbl_fmi_batches','id',$this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tbl_fmi_batches}}');
    }
}
