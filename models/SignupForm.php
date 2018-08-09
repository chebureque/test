<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    const SCENARIO_PERSON = 'person';
    const SCENARIO_ENTREPRENEUR = 'entrepreneur';
    const SCENARIO_LEGAL_ENTITY = 'legalEntity';

    public $type = UserProfile::TYPE_PERSON;
    public $email;
    public $password;
    public $passwordRepeat;
    public $firstName;
    public $lastName;
    public $patronymic;
    public $itn;
    public $organization;

    protected $user;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_LEGAL_ENTITY] = $scenarios[self::SCENARIO_DEFAULT];
        $scenarios[self::SCENARIO_ENTREPRENEUR] = array_diff(
            $scenarios[self::SCENARIO_LEGAL_ENTITY], ['organization']
        );
        $scenarios[self::SCENARIO_PERSON] = array_diff(
            $scenarios[self::SCENARIO_ENTREPRENEUR], ['itn']
        );

        return $scenarios;
    }

    public function rules()
    {
        return [
            [
                [
                    'type',
                    'email',
                    'password',
                    'passwordRepeat',
                    'firstName',
                    'lastName',
                    'patronymic',
                    'itn',
                    'organization'
                ],
                'filter',
                'filter' => 'trim'
            ],
            [
                [
                    'type',
                    'email',
                    'password',
                    'passwordRepeat',
                    'firstName',
                    'lastName',
                    'patronymic',
                ],
                'required'
            ],

            ['type', 'integer'],
            ['type', 'in', 'range' => array_keys(UserProfile::types())],

            ['email', 'string', 'max' => 255],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'app\models\User'],

            ['password', 'string', 'min' => 8],

            ['passwordRepeat', 'compare', 'compareAttribute' => 'password'],

            ['firstName', 'string', 'max' => 255],

            ['lastName', 'string', 'max' => 255],

            ['patronymic', 'string', 'max' => 255],

            [
                'itn',
                'required',
                'when' => function ($model) {
                    return in_array($model->type, [
                        UserProfile::TYPE_ENTREPRENEUR,
                        UserProfile::TYPE_LEGAL_ENTITY
                    ]);
                },
                'whenClient' => "function (attribute, value) {
                    return $(':input[name*=type]').val() > 0;
                }",
            ],
            ['itn', 'string', 'min' => 10, 'max' => 12],
            ['itn', 'match', 'pattern' => '/^\d+$/'],

            [
                'organization',
                'required',
                'when' => function ($model) {
                    return in_array($model->type, [
                        UserProfile::TYPE_LEGAL_ENTITY
                    ]);
                },
                'whenClient' => "function (attribute, value) {
                    return $(':input[name*=type]').val() == 2;
                }",
            ],
            ['organization', 'string', 'max' => 255],
        ];
    }

    /**
     * @return User|null
     * @throws \yii\base\Exception
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->email = $this->email;
            $user->passwordHash = Yii::$app->security->generatePasswordHash($this->password);
            $user->save(false);

            $profile = new UserProfile();
            $profile->load($this->attributes, '');
            $user->link('profile', $profile);

            return $user;
        }

        return null;
    }
}
