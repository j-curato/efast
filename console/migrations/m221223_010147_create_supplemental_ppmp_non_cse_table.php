<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%supplemental_ppmp_non_cse}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%supplemental_ppmp}}`
 */
class m221223_010147_create_supplemental_ppmp_non_cse_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%supplemental_ppmp_non_cse}}', [
            'id' => $this->primaryKey(),
            'fk_supplemental_ppmp_id' => $this->bigInteger(),
        
            'type' => $this->string()->notNull(),
            'early_procurement' => $this->boolean()->defaultValue(false),
            'fk_mode_of_procurement_id' => $this->integer()->notNull(),
            'activity_name' => $this->text()->notNull(),
            'fk_fund_source_id' => $this->integer()->notNull(),
            'proc_act_sched' => $this->string()->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(0),
            'deleted_at' => $this->timestamp(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')

        ]);

        // creates index for column `fk_supplemental_ppmp_id`
        $this->createIndex(
            '{{%idx-supplemental_ppmp_non_cse-fk_supplemental_ppmp_id}}',
            '{{%supplemental_ppmp_non_cse}}',
            'fk_supplemental_ppmp_id'
        );

        // add foreign key for table `{{%supplemental_ppmp}}`
        $this->addForeignKey(
            '{{%fk-supplemental_ppmp_non_cse-fk_supplemental_ppmp_id}}',
            '{{%supplemental_ppmp_non_cse}}',
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
            '{{%fk-supplemental_ppmp_non_cse-fk_supplemental_ppmp_id}}',
            '{{%supplemental_ppmp_non_cse}}'
        );

        // drops index for column `fk_supplemental_ppmp_id`
        $this->dropIndex(
            '{{%idx-supplemental_ppmp_non_cse-fk_supplemental_ppmp_id}}',
            '{{%supplemental_ppmp_non_cse}}'
        );

        $this->dropTable('{{%supplemental_ppmp_non_cse}}');
    }
}
