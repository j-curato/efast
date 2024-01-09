<?php

use yii\db\Migration;

/**
 * Class m240108_062222_create_CountUnderscores_function
 */
class m240108_062222_create_CountUnderscores_function extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
        DROP FUNCTION  IF EXISTS CountUnderscores;

            CREATE FUNCTION CountUnderscores(inputString VARCHAR(255))
            RETURNS INT
            BEGIN
                DECLARE underscoreCount INT;
                
                SET underscoreCount = CHAR_LENGTH(inputString) - CHAR_LENGTH(REPLACE(inputString, '_', ''));
                
                RETURN underscoreCount;
            END
        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240108_062222_create_CountUnderscores_function cannot be reverted.\n";

        return false;
    }
    */
}
