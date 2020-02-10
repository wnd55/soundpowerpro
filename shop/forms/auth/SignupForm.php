<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 11.05.18
 * Time: 13:32
 */

namespace shop\forms\auth;


use yii\base\Model;
use shop\entities\user\User;


class SignupForm extends Model
{

    public $username;
    public $email;
    public $password;
    public $privacy;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'Это имя пользователя уже занято.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Этот адрес электронной почты уже занят.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['privacy', 'required', 'message' => 'Подтвердите'],
            ['privacy', 'boolean', 'trueValue' => true, 'falseValue' => false, 'strict' => false, 'skipOnEmpty' =>true,],
            ['privacy', 'in', 'range' => [1,], 'message' => 'Подтвердите'],
        ];
    }


    public function attributeLabels()
    {

        return [

            'username' => 'Логин',
            'email' => 'Email',
            'password' => 'Пароль',
            'privacy' => 'Соглашение обработки персональных данных'

        ];


    }


}

