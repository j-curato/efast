<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%divisions}}`.
 */
class m220729_052121_create_divisions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%divisions}}', [
            'id' => $this->primaryKey(),
            'division' => $this->string()->notNull()->unique(),
            'fk_division_chief' => $this->bigInteger()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('divisions', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%divisions}}');
    }
}
