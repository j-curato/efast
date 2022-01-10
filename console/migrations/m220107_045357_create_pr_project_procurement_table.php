<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_project_procurement}}`.
 */
class m220107_045357_create_pr_project_procurement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_project_procurement}}', [
            'id' => $this->primaryKey(),
            'title' => $this->text(),
            'pr_office_id' => $this->integer(),
            'amount' => $this->decimal(10, 2),
            'employee_id' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pr_project_procurement}}');
    }
}
