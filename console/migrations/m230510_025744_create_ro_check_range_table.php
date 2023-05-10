<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ro_check_range}}`.
 */
class m230510_025744_create_ro_check_range_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ro_check_range}}', [
            'id' => $this->primaryKey(),
            'fk_book_id' => $this->integer()->notNull(),
            'from' => $this->bigInteger()->notNull(),
            'to' => $this->bigInteger()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull()
        ]);
        $this->createIndex('idx-ro-chk-rng-fk_book_id', 'ro_check_range', 'fk_book_id');
        $this->addForeignKey('fk-ro-chk-rng-fk_book_id', 'ro_check_range', 'fk_book_id', 'books', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-ro-chk-rng-fk_book_id', 'ro_check_range');
        $this->dropIndex('idx-ro-chk-rng-fk_book_id', 'ro_check_range');
        $this->dropTable('{{%ro_check_range}}');
    }
}
