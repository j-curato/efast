<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%trip_ticket}}`.
 */
class m221115_055644_create_trip_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%trip_ticket}}', [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull(),
            'driver' => $this->bigInteger()->notNull(),
            'serial_no' => $this->string()->notNull()->unique(),
            'purpose' => $this->text()->notNull(),
            'authorized_by' => $this->bigInteger()->notNull(),
            'car_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull()
        ]);
        $this->alterColumn('trip_ticket', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%trip_ticket}}');
    }
}
