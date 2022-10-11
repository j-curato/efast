<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%other_property_details}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%property}}`
 */
class m221006_074555_create_other_property_details_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%other_property_details}}', [
            'id' => $this->primaryKey(),
            'fk_property_id' => $this->bigInteger()->notNull(),
            'depreciation_schedule' => $this->integer()->notNull()->defaultValue(1),
            'fk_chart_of_account_id' => $this->integer()->notNull(),
            'salvage_value_prcnt' => $this->integer()->notNull(),
            'first_month_depreciation' => $this->string()->notNull(),
            'start_month_depreciation' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('other_property_details', 'id', $this->bigInteger());

        // creates index for column `fk_property_id`

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropTable('{{%other_property_details}}');
    }
}
