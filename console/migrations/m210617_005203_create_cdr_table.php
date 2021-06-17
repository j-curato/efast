<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cdr}}`.
 */
class m210617_005203_create_cdr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cdr}}', [
            'id' => $this->primaryKey(),
            'serial_number'=>$this->string(100),
            'reporting_period'=>$this->string(50),
            'province'=>$this->string(50),
            'book_name'=>$this->string(50),
            'report_type'=>$this->string(),
            'is_final'=>$this->boolean()->defaultValue(0)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cdr}}');
    }
}
