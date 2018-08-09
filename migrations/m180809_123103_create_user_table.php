<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m180809_123103_create_user_table extends Migration
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

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->unique()->notNull(),
            'passwordHash' => $this->char(60)->notNull(),
            'authKey' => $this->string()->notNull(),
            'accessToken' => $this->char(60)->unique()->notNull(),
            'createdAt' => $this->timestamp()->defaultValue(null),
            'updatedAt' => $this->timestamp()->defaultValue(null)
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
