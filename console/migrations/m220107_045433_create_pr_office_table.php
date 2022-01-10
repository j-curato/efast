<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_office}}`.
 */
class m220107_045433_create_pr_office_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_office}}', [
            'id' => $this->primaryKey(),
            'office'=>$this->string(),
            'division'=>$this->string(),
            'unit'=>$this->string(),
            'responsibility_code'=>$this->string(),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pr_office}}');
    }
}
