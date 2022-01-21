<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_rfq}}`.
 */
class m220113_024416_create_pr_rfq_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_rfq}}', [
            'id' => $this->primaryKey(),
            'rfq_number' => $this->string()->unique()->notNull(),
            'pr_purchase_request_id' => $this->bigInteger(),
            '_date' => $this->date(),
            'deadline' => $this->date(),
            'rbac_composition_id' => $this->bigInteger(),
            'employee_id' => $this->bigInteger(),
            'province' => $this->string(),
            'project_location' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->alterColumn('{{%pr_rfq}}', 'id', $this->bigInteger() . ' NOT NULL  default(uuid_short())');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pr_rfq}}');
    }
}
