<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%travel_order}}`.
 */
class m221122_051155_create_travel_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%travel_order}}', [
            'id' => $this->primaryKey(),
            'to_number' => $this->string()->notNull()->unique(),
            'date' => $this->date()->notNull(),
            'destination' => $this->text()->notNull(),
            'purpose' => $this->text()->notNull(),
            'expected_outputs' => $this->text(),
            'fk_recommending_approval' => $this->bigInteger(),
            'fk_approved_by' => $this->bigInteger()->notNull(),
            'fk_budget_officer' => $this->bigInteger()->notNull(),
            'type' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('travel_order', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%travel_order}}');
    }
}
