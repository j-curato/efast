<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%supplemental_ppmp}}`.
 */
class m221223_003802_create_supplemental_ppmp_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%supplemental_ppmp}}', [
            'id' => $this->primaryKey(),
            'date' => $this->date(),
            'serial_number' => $this->string()->notNull()->unique(),
            'budget_year' => $this->integer()->notNull(),
            'cse_type' => $this->string()->notNull(),
            'fk_pr_stock_type_id' => $this->integer(),
            'fk_office_id' => $this->integer()->notNull(),
            'fk_division_id' => $this->bigInteger()->notNull(),
            'fk_division_program_unit_id' => $this->integer()->notNull(),
            'fk_prepared_by' => $this->bigInteger(),
            'fk_reviewed_by' => $this->bigInteger(),
            'fk_approved_by' => $this->bigInteger(),
            'fk_certified_funds_available_by' => $this->bigInteger(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('supplemental_ppmp', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%supplemental_ppmp}}');
    }
}
