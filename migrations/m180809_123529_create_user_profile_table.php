<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_profile`.
 */
class m180809_123529_create_user_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_profile}}', [
            'userId' => $this->primaryKey(),
            'type' => $this->smallInteger()->notNull(),
            'firstName' => $this->string(),
            'lastName' => $this->string(),
            'patronymic' => $this->string(),
            'itn' => $this->string(),
            'organization' => $this->string(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk_user_profile_user',
            '{{%user_profile}}',
            'userId',
            '{{%user}}',
            'id',
            'cascade',
            'cascade'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_user_profile_user', '{{%user_profile}}');
        $this->dropTable('{{%user_profile}}');
    }
}
