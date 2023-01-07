<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%supplemental_ppmp_cse}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%supplemental_ppmp}}`
 */
class m221223_010133_create_supplemental_ppmp_cse_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%supplemental_ppmp_cse}}', [
            'id' => $this->primaryKey(),
            'fk_supplemental_ppmp_id' => $this->bigInteger(),
            'fk_pr_stock_id' => $this->integer(),
            'fk_unit_of_measure_id' => $this->integer(),
            'amount' => $this->decimal(10, 2)->defaultValue(0),
            'jan_qty' => $this->integer()->defaultValue(0),
            'feb_qty' => $this->integer()->defaultValue(0),
            'mar_qty' => $this->integer()->defaultValue(0),
            'apr_qty' => $this->integer()->defaultValue(0),
            'may_qty' => $this->integer()->defaultValue(0),
            'jun_qty' => $this->integer()->defaultValue(0),
            'jul_qty' => $this->integer()->defaultValue(0),
            'aug_qty' => $this->integer()->defaultValue(0),
            'sep_qty' => $this->integer()->defaultValue(0),
            'oct_qty' => $this->integer()->defaultValue(0),
            'nov_qty' => $this->integer()->defaultValue(0),
            'dec_qty' => $this->integer()->defaultValue(0),
            'is_deleted' => $this->boolean()->defaultValue(0),
            'deleted_at' => $this->timestamp(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_supplemental_ppmp_id`
        $this->createIndex(
            '{{%idx-supplemental_ppmp_cse-fk_supplemental_ppmp_id}}',
            '{{%supplemental_ppmp_cse}}',
            'fk_supplemental_ppmp_id'
        );

        // add foreign key for table `{{%supplemental_ppmp}}`
        $this->addForeignKey(
            '{{%fk-supplemental_ppmp_cse-fk_supplemental_ppmp_id}}',
            '{{%supplemental_ppmp_cse}}',
            'fk_supplemental_ppmp_id',
            '{{%supplemental_ppmp}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%supplemental_ppmp}}`
        $this->dropForeignKey(
            '{{%fk-supplemental_ppmp_cse-fk_supplemental_ppmp_id}}',
            '{{%supplemental_ppmp_cse}}'
        );

        // drops index for column `fk_supplemental_ppmp_id`
        $this->dropIndex(
            '{{%idx-supplemental_ppmp_cse-fk_supplemental_ppmp_id}}',
            '{{%supplemental_ppmp_cse}}'
        );

        $this->dropTable('{{%supplemental_ppmp_cse}}');
    }
}
