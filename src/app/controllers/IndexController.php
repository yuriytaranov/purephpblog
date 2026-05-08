<?php

namespace app\controllers;

use app\http\Response;
use app\models\UserModel;

class IndexController extends HttpController
{
    /**
     * @throws \Exception
     */
    public function index(): Response
    {
        return $this->view("index", ['hello' => 'world']);
    }

    /**
     * @throws \Exception
     */
    public function dashboard(): Response {
        $r = $this->_request;
        if($r->session("is_logged", false) === true) {
            return $this->view("my/dashboard", []);
        }
        return $this->redirect("login");
    }

    public function login(): Response {
        $r = $this->_request;
        if($r->isPost) {
            $email = $r->post("email", "");
            $password = $r->post("password", "");
            $user = new UserModel();
            $user->one('email', $email);
            if(!is_null($user->email) && password_verify($password, $user->password) && $user->active === 1) {
                $r->sessionSet("is_logged", true);
                $r->sessionSet("user_id", $user->id);
                return $this->redirect('dashboard');
            }
            return $this->view('auth/login', ['req' => $r, 'error' => '']);
        }
        return $this->view("auth/login", ['req' => $r]);
    }

    public function authenticate(): Response {

        return $this->redirect("dashboard");
    }

    public function register(): Response {
        $r = $this->_request;
        if($r->isPost) {
            $email = $r->post("email", "");
            $pass = $r->post("password", "");
            $passRep =$r->post("password_repeat", "");
            if($pass !== $passRep) {
                return $this->view("auth/register", ['req' => $r, 'error' => ['Пароли не совпадают']]);
            }
            if(mb_strlen($pass) > 72) {
                return $this->view("auth/register", ['req' => $r, 'error' => ['Сложность пароля превышает максимальную длинну']]);
            }
            $user = new UserModel();
            $user->one('email', $email);
            if(!is_null($user->email)) {
                return $this->view("auth/register", ['req' => $r, 'error' => ['Пользователь уже существует']]);
            }
            $sql = "insert into {$user->table}(email, password, active) values(:email, :password, :active)";
            $user->query($sql, [
                'email' => $email,
                'password' => password_hash($pass, PASSWORD_DEFAULT),
                'active' => true,
            ]);
            $this->redirect('login');
        }
        return $this->view("auth/register", ['req' => $r, 'error' => '']);
    }

    public function getPrize() {
        $r = $this->_request;
        if($r->session("is_logged", false) === true) {

            return $this->view("my/dashboard", []);
        }
        return $this->redirect("login");
    }
}