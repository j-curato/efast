<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%jev_preparation}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%responsibility_center}}`
 * - `{{%fund_cluster_code}}`
 */
class m210202_031914_create_jev_preparation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%jev_preparation}}', [
            'id' => $this->primaryKey(),
            'responsibility_center_id' => $this->integer(),
            'fund_cluster_code_id' => $this->integer(),
            'reporting_period' => $this->string(50)->notNull(),
            'date' => $this->date(),
            'jev_number' => $this->string(100),
            'ref_number'=>$this->string(100),
            'dv_number' => $this->string(100),
            'lddap_number' => $this->string(100),
            'entity_name' => $this->string(100),
            'explaination' => $this->string(255)->notNull(),
        ]);

        // creates index for column `responsibility_center_id`
        $this->createIndex(
            '{{%idx-jev_preparation-responsibility_center_id}}',
            '{{%jev_preparation}}',
            'responsibility_center_id'
        );

        // add foreign key for table `{{%responsibility_center}}`
        $this->addForeignKey(
            '{{%fk-jev_preparation-responsibility_center_id}}',
            '{{%jev_preparation}}',
            'responsibility_center_id',
            '{{%responsibility_center}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fund_cluster_code_id`
        $this->createIndex(
            '{{%idx-jev_preparation-fund_cluster_code_id}}',
            '{{%jev_preparation}}',
            'fund_cluster_code_id'
        );

        // add foreign key for table `{{%fund_cluster_code}}`
        $this->addForeignKey(
            '{{%fk-jev_preparation-fund_cluster_code_id}}',
            '{{%jev_preparation}}',
            'fund_cluster_code_id',
            '{{%fund_cluster_code}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%responsibility_center}}`
        $this->dropForeignKey(
            '{{%fk-jev_preparation-responsibility_center_id}}',
            '{{%jev_preparation}}'
        );

        // drops index for column `responsibility_center_id`
        $this->dropIndex(
            '{{%idx-jev_preparation-responsibility_center_id}}',
            '{{%jev_preparation}}'
        );

        // drops foreign key for table `{{%fund_cluster_code}}`
        $this->dropForeignKey(
            '{{%fk-jev_preparation-fund_cluster_code_id}}',
            '{{%jev_preparation}}'
        );

        // drops index for column `fund_cluster_code_id`
        $this->dropIndex(
            '{{%idx-jev_preparation-fund_cluster_code_id}}',
            '{{%jev_preparation}}'
        );

        $this->dropTable('{{%jev_preparation}}');
    }
}
