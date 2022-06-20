<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%maintenance_job_request}}`.
 */
class m220620_052449_create_maintenance_job_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%maintenance_job_request}}', [
            'id' => $this->primaryKey(),
            'mjr_number'=>$this->string()->notNull()->unique(),
            'fk_responsibility_center_id' => $this->integer()->notNull(),
            'fk_employee_id' => $this->bigInteger()->notNull(),
            'date_requested' => $this->date()->notNull(),
            'problem_description' => $this->text()->notNull(),
            'recommendation' => $this->text(),
            'action_taken' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('maintenance_job_request', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%maintenance_job_request}}');
    }
}
