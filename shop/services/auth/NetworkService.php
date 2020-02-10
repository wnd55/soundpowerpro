<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 04.01.19
 * Time: 19:31
 */

namespace shop\services\auth;


use shop\entities\user\Network;
use shop\entities\user\User;
use shop\repositories\UserRepository;

class NetworkService
{

    /**
     * @var UserRepository
     */
    private $users;

    /**
     * NetworkService constructor.
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * @param $network
     * @param $identity
     * @return array|null|User|\yii\db\ActiveRecord
     */

    public function auth($network, $identity)
    {
        if ($user = $this->users->findByNetworkIdentity($network, $identity)) {
            return $user;
        }
        $user = User::signupByNetwork();
        $this->users->save($user);
        Network::create($network, $identity, $user->id)->save();

        return $user;
    }


    /**
     * @param $id
     * @param $network
     * @param $identity
     */

    public function attach($id, $network, $identity)
    {
        if ($this->users->findByNetworkIdentity($network, $identity)) {
            throw new \DomainException('Network is already signed up.');
        }
        $user = $this->users->get($id);
        $user->attachNetwork($network, $identity);
//        $this->users->save($user);
    }





}