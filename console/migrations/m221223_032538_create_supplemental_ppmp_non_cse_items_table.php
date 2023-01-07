<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%supplemental_ppmp_non_cse_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%supplemental_ppmp_non_cse}}`
 */
class m221223_032538_create_supplemental_ppmp_non_cse_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%supplemental_ppmp_non_cse_items}}', [
            'id' => $this->primaryKey(),
            'fk_supplemental_ppmp_non_cse_id' => $this->bigInteger(),
            'fk_unit_of_measure_id' => $this->integer(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'fk_pr_stock_id' => $this->integer(),
            'quantity' => $this->integer(),
            'description' => $this->text(),
            'is_deleted' => $this->boolean()->defaultValue(0),
            'deleted_at' => $this->timestamp(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {


        $this->dropTable('{{%supplemental_ppmp_non_cse_items}}');
    }
}
