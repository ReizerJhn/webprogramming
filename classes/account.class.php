<?php

require_once 'database.php';

class Account {
    private $db;
    
    // Properties matching the database schema
    public $id = '';
    public $first_name = '';
    public $last_name = '';
    public $username = '';
    public $password = '';
    public $role = 'staff';
    public $is_staff = true;
    public $is_admin = false;

    function __construct() {
        $this->db = new Database();
    }

    function add() {
        try {
            $sql = "INSERT INTO account (first_name, last_name, username, password, role, is_staff, is_admin) 
                    VALUES (:first_name, :last_name, :username, :password, :role, :is_staff, :is_admin)";
            
            $query = $this->db->connect()->prepare($sql);
            
            // Hash password before storing
            $hashpassword = password_hash($this->password, PASSWORD_DEFAULT);
            
            $query->bindParam(':first_name', $this->first_name);
            $query->bindParam(':last_name', $this->last_name);
            $query->bindParam(':username', $this->username);
            $query->bindParam(':password', $hashpassword);
            $query->bindParam(':role', $this->role);
            $query->bindParam(':is_staff', $this->is_staff, PDO::PARAM_BOOL);
            $query->bindParam(':is_admin', $this->is_admin, PDO::PARAM_BOOL);
            
            return $query->execute();
        } catch (PDOException $e) {
            error_log('Account::add - Error: ' . $e->getMessage());
            return false;
        }
    }

    function usernameExist($username, $excludeID = null) {
        try {
            $sql = "SELECT COUNT(*) FROM account WHERE username = :username";
            if ($excludeID) {
                $sql .= " AND id != :excludeID";
            }
            
            $query = $this->db->connect()->prepare($sql);
            $query->bindParam(':username', $username);
            
            if ($excludeID) {
                $query->bindParam(':excludeID', $excludeID);
            }
            
            $query->execute();
            return $query->fetchColumn() > 0;
            
        } catch (PDOException $e) {
            error_log('Account::usernameExist - Error: ' . $e->getMessage());
            return false;
        }
    }

    function login($username, $password) {
        try {
            $sql = "SELECT * FROM account WHERE username = :username LIMIT 1";
            $query = $this->db->connect()->prepare($sql);
            $query->bindParam(':username', $username);
            
            if ($query->execute()) {
                $data = $query->fetch(PDO::FETCH_ASSOC);
                if ($data && password_verify($password, $data['password'])) {
                    return true;
                }
            }
            return false;
            
        } catch (PDOException $e) {
            error_log('Account::login - Error: ' . $e->getMessage());
            return false;
        }
    }

    function fetch($username) {
        try {
            $sql = "SELECT * FROM account WHERE username = :username LIMIT 1";
            $query = $this->db->connect()->prepare($sql);
            $query->bindParam(':username', $username);
            
            if ($query->execute()) {
                return $query->fetch(PDO::FETCH_ASSOC);
            }
            return null;
            
        } catch (PDOException $e) {
            error_log('Account::fetch - Error: ' . $e->getMessage());
            return null;
        }
    }

    function getAllAccounts() {
        try {
            $sql = "SELECT 
                    id,
                    username,
                    first_name,
                    last_name,
                    role,
                    is_staff,
                    is_admin,
                    CASE 
                        WHEN is_admin = 1 THEN 'admin'
                        WHEN is_staff = 1 THEN 'staff'
                        ELSE 'user'
                    END as role_type
                FROM account 
                ORDER BY id ASC";
            
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log('Account::getAllAccounts - Error: ' . $e->getMessage());
            return [];
        }
    }

    function getAccountById($id) {
        try {
            $sql = "SELECT * FROM account WHERE id = :id LIMIT 1";
            $query = $this->db->connect()->prepare($sql);
            $query->bindParam(':id', $id);
            
            if ($query->execute()) {
                return $query->fetch(PDO::FETCH_ASSOC);
            }
            return null;
            
        } catch (PDOException $e) {
            error_log('Account::getAccountById - Error: ' . $e->getMessage());
            return null;
        }
    }

    function updateAccount($id, $data) {
        try {
            $sql = "UPDATE account SET 
                    first_name = :first_name,
                    last_name = :last_name,
                    username = :username,
                    role = :role,
                    is_staff = :is_staff,
                    is_admin = :is_admin
                    WHERE id = :id";
            
            if (!empty($data['password'])) {
                $sql = "UPDATE account SET 
                        first_name = :first_name,
                        last_name = :last_name,
                        username = :username,
                        password = :password,
                        role = :role,
                        is_staff = :is_staff,
                        is_admin = :is_admin
                        WHERE id = :id";
            }
            
            $query = $this->db->connect()->prepare($sql);
            
            $params = [
                ':id' => $id,
                ':first_name' => $data['first_name'],
                ':last_name' => $data['last_name'],
                ':username' => $data['username'],
                ':role' => $data['role'],
                ':is_staff' => $data['is_staff'],
                ':is_admin' => $data['is_admin']
            ];
            
            if (!empty($data['password'])) {
                $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            
            return $query->execute($params);
            
        } catch (PDOException $e) {
            error_log('Account::updateAccount - Error: ' . $e->getMessage());
            return false;
        }
    }

    function deleteAccount($id) {
        try {
            $sql = "DELETE FROM account WHERE id = :id";
            $query = $this->db->connect()->prepare($sql);
            return $query->execute([':id' => $id]);
            
        } catch (PDOException $e) {
            error_log('Account::deleteAccount - Error: ' . $e->getMessage());
            return false;
        }
    }
}

// $obj = new Account();

// $obj->add();
