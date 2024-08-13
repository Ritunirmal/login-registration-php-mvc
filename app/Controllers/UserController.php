<?php

require_once '../app/Models/UserModel.php';
require_once '../config/database.php';

class UserController extends Controller {
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = (new Database())->connect();
            $user = new UserModel($db);

            $user->username = $_POST['username'];
            $user->email = $_POST['email'];
            $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            if ($user->register()) {
                echo json_encode(['success' => true, 'message' => 'Registration successful']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Registration failed']);
            }
        } else {
            $this->view('login');
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = (new Database())->connect();
            $user = new UserModel($db);

            $user->email = $_POST['email'];
            $user->password = $_POST['password'];

            $loggedInUser = $user->login();

            if ($loggedInUser) {
                $_SESSION['user_id'] = $loggedInUser['id'];
                echo json_encode(['success' => true, 'message' => 'Login successful']);
                echo json_encode(['message' => 'Login successful']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Login failed']);
            }
        } else {
            $this->view('login');
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /login');
    }
}
