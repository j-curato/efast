<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%fur}}`.
 */
class m210902_032717_create_fur_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%fur}}', [
            'id' => $this->primaryKey(),
            'reporting_period'=>$this->string(50),
            'province'=>$this->string(20),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%fur}}');
    }
}
