<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rci}}`.
 */
class m230608_030821_create_rci_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rci}}', [
            'id' => $this->primaryKey(),
            'serial_number' => $this->string()->unique()->notNull(),
            'fk_book_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'reporting_period' => $this->string(10)->notNull(),

            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('rci', 'id', $this->bigInteger());
        $this->createIndex('idx-rci-fk_book_id', 'rci', 'fk_book_id');
        $this->addForeignKey('fk-rci-fk_book_id', 'rci', 'fk_book_id', 'books', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-rci-fk_book_id', 'rci', 'fk_book_id');
        $this->dropIndex('idx-rci-fk_book_id', 'rci');
        $this->dropTable('{{%rci}}');
    }
}
