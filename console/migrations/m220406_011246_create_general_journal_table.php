<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%general_journal}}`.
 */
class m220406_011246_create_general_journal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%general_journal}}', [
            'id' => $this->primaryKey(),
            'book_id'=>$this->integer()->notNull(),
            'reporting_period'=>$this->string(20)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%general_journal}}');
    }
}
