<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%iar}}`.
 */
class m220809_003109_create_iar_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%iar}}', [
            'id' => $this->primaryKey(),
            'iar_number' => $this->string()->notNull()->unique(),
            'fk_ir_id' => $this->bigInteger(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->alterColumn('iar', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%iar}}');
    }
}
