<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%radai}}`.
 */
class m230621_052503_create_radai_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%radai}}', [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull(),
            'reporting_period' => $this->string()->notNull(),
            'fk_book_id' => $this->integer()->notNull(),
            'serial_number' => $this->string()->notNull()->unique(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('radai', 'id', $this->bigInteger());
        $this->createIndex('idx-radai-fk_book_id', 'radai', 'fk_book_id');
        $this->addForeignKey('fk-radai-fk_book_id', 'radai', 'fk_book_id', 'books', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-radai-fk_book_id', 'radai');
        $this->dropIndex('idx-radai-fk_book_id', 'radai');

        $this->dropTable('{{%radai}}');
    }
}
