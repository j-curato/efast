<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ro_rao}}`.
 */
class m220210_080542_create_ro_rao_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ro_rao}}', [
            'id' => $this->primaryKey(),
            'reporting_period'=>$this->string(20),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ro_rao}}');
    }
}
