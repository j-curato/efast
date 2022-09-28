<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ir_transmittal}}`.
 */
class m220928_063328_create_ir_transmittal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ir_transmittal}}', [
            'id' => $this->primaryKey(),
            'serial_number'=>$this->string()->notNull()->unique(),
            'date' => $this->date()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('ir_transmittal', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ir_transmittal}}');
    }
}
