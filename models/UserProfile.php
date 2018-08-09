<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_profile}}".
 *
 * @property int $userId
 * @property int $type
 * @property string $firstName
 * @property string $lastName
 * @property string $patronymic
 * @property string $itn
 * @property string $organization
 *
 * @property User $user
 */
class UserProfile extends ActiveRecord
{
    const TYPE_PERSON = 0;
    const TYPE_ENTREPRENEUR = 1;
    const TYPE_LEGAL_ENTITY = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'firstName', 'lastName', 'patronymic'], 'required'],
            ['type', 'integer'],
            [
                [
                    'firstName',
                    'lastName',
                    'patronymic',
                    'itn',
                    'organization',
                ],
                'string',
                'max' => 255,
            ],
            [
                'userId',
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['userId' => 'id'],
            ],
            [['itn', 'organization'], 'default']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'userId' => 'User ID',
            'type' => 'Type',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'patronymic' => 'Patronymic',
            'itn' => 'Itn',
            'organization' => 'Organization',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
    }

    /**
     * @return array
     */
    public static function types()
    {
        return [
            self::TYPE_PERSON => 'Person',
            self::TYPE_ENTREPRENEUR => 'Entrepreneur',
            self::TYPE_LEGAL_ENTITY => 'Legal Entity'
        ];
    }
}
