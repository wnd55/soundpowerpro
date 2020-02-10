<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 11.05.18
 * Time: 14:30
 */

namespace shop\services\auth;

use Yii;
use shop\access\Rbac;
use shop\entities\user\User;
use shop\forms\auth\SignupForm as AuthSignupForm;
use shop\repositories\UserRepository;
use shop\services\RoleManager;
use yii\mail\MailerInterface;

class SignupService
{

    private $users;
    private $roles;
    private $mailer;


    public function __construct(UserRepository $users, RoleManager $roles, MailerInterface $mailer)
    {


        $this->users = $users;
        $this->roles = $roles;
        $this->mailer = $mailer;

    }



    public function signup(AuthSignupForm $form)
    {

        $user = User::requestSignup(
            $form->username,
            $form->email,
            $form->password
        );

        $this->users->save($user);
        $this->roles->assign($user->id, Rbac::ROLE_USER);

        $sent = $this->mailer
            ->compose(
                ['text' => 'auth/signup/emailNotification-text'],
                ['user' => $user]

            )
            ->setTo($user->email)
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject('Registration on the ' . Yii::$app->name)
            ->send();

        if (!$sent) {
            throw new \RuntimeException('Sending error.');
        }


    }


}

