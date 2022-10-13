<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ssf_category}}`.
 */
class m221012_013952_create_ssf_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ssf_category}}', [
            'id' => $this->primaryKey(),
            'ssf_number' => $this->string()->unique(),
            'fund_source' => $this->string(),
            'province' => $this->string(),
            'district' => $this->string(),
            'city' => $this->string(),
            'project_title' => $this->text(),
            'cooperator' => $this->text(),
            'cooperator_type' => $this->string(),
            'industry_cluster'=>$this->string(),
            'count_of_ssf_establish' => $this->integer(),
            'equipment_provided' => $this->text(),
            'amount_disbursed' => $this->decimal(10, 2),
            'date' => $this->date(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ssf_category}}');
    }
}
