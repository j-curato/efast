<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ro_fur}}`.
 */
class m220210_032305_create_ro_fur_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ro_fur}}', [
            'id' => $this->primaryKey(),
            'from_reporting_period'=>$this->string(20),
            'to_reporting_period'=>$this->string(20),
            'division'=>$this->string(20),
            'document_recieve_id'=>$this->integer(),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
            
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ro_fur}}');
    }
}
