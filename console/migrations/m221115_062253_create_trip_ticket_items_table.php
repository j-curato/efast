<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%trip_ticket_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%trip_ticket}}`
 */
class m221115_062253_create_trip_ticket_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%trip_ticket_items}}', [
            'id' => $this->primaryKey(),
            'fk_trip_ticket_id' => $this->bigInteger()->notNull(),
            'departure_time' => $this->string(),
            'departure_place' => $this->text(),
            'arrival_time' => $this->string(),
            'arrival_place' => $this->text(),
            'passenger_id' => $this->bigInteger(),
            'is_deleted' => $this->boolean()->defaultValue(0),
            'deleted_at' => $this->timestamp(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // creates index for column `fk_trip_ticket_id`
        $this->createIndex(
            '{{%idx-trip_ticket_items-fk_trip_ticket_id}}',
            '{{%trip_ticket_items}}',
            'fk_trip_ticket_id'
        );

        // add foreign key for table `{{%trip_ticket}}`
        $this->addForeignKey(
            '{{%fk-trip_ticket_items-fk_trip_ticket_id}}',
            '{{%trip_ticket_items}}',
            'fk_trip_ticket_id',
            '{{%trip_ticket}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%trip_ticket}}`
        $this->dropForeignKey(
            '{{%fk-trip_ticket_items-fk_trip_ticket_id}}',
            '{{%trip_ticket_items}}'
        );

        // drops index for column `fk_trip_ticket_id`
        $this->dropIndex(
            '{{%idx-trip_ticket_items-fk_trip_ticket_id}}',
            '{{%trip_ticket_items}}'
        );

        $this->dropTable('{{%trip_ticket_items}}');
    }
}
