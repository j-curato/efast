<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%holidays}}`.
 */
class m220523_030329_create_holidays_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%holidays}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
            'date'=>$this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%holidays}}');
    }
}
