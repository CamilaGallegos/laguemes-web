<?php
class UserController
{
    private $userModel;

    public function __construct($userModel)
    {
        $this->userModel = $userModel;
    }

    public function login($username, $password)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = $this->userModel->getUserByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            return ['success' => true, 'message' => 'Inicio de sesión exitoso'];
        }
        return ['success' => false, 'message' => 'Nombre de usuario o contraseña incorrectos'];
    }

    public function register($username, $password)
    {
        if (empty($username) || empty($password)) {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios'];
        }
        if ($this->userModel->getUserByUsername($username)) {
            return ['success' => false, 'message' => 'El nombre de usuario ya está en uso'];
        }
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $result = $this->userModel->createUser($username, $hashedPassword);

        if ($result) {
            return ['success' => true, 'message' => 'Usuario registrado exitosamente'];
        } else {
            return ['success' => false, 'message' => 'Error al registrar el usuario'];
        }
    }


    public function logout()
    {
        session_start();
        session_destroy();
        return ['success' => true, 'message' => 'Sesión cerrada exitosamente'];
    }

    public function getAllUsers()
    {
        return $this->userModel->getAllUsers();
    }

    public function deleteUser($id)
    {
        return $this->userModel->deleteUser($id);
    }

    public function updateUser($id, $data)
    {
        return $this->userModel->updateUser($id, $data);
    }
}
