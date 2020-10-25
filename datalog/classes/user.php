<?php
class User {
    public $id = 0;
    public $firstname = "";
    public $lastname = "";
    public $username = "";
    public $password = "";
    public $createdAt = 0;
    public $updatedAt = 0;

    public function login($uname, $sentPassword)
    {
        global $mysqli;
        $statement = $mysqli->prepare("SELECT id, username, firstname, lastname, password, createdAt, updatedAt FROM user WHERE username LIKE ?");
        if($statement) {
            $statement->bind_param('s', $uname);
            $statement->execute();
            $statement->bind_result($id, $username, $firstname, $lastname, $password, $createdAt, $updatedAt);
            
            if ($statement->fetch()) {
                $o = new User();
                $o->id = $id;
                $o->username = $username;
                $o->firstname = $firstname;
                $o->lastname = $lastname;
                $o->password = NULL;
                $o->createdAt = $createdAt;
                $o->updatedAt = $updatedAt;
                if($password == md5($sentPassword)) {
                    return $o;
                }
                else {
                    return NULL;
                }
            }
            return NULL;
        }
        else {
            echo $mysqli->error;
            exit;
        }
    }

    public function existingUser($username){
        global $mysqli;
        $statement = $mysqli->prepare("SELECT count(username) FROM user WHERE username LIKE ?");
        $statement->bind_param('s', $username);
        $statement->execute();
        $statement->bind_result($count);
        $statement->fetch();
        if(!$count){
            $count = NULL;
        }
        return $count;
    }

    public function getForId($id) {
        global $mysqli;
        $statement = $mysqli->prepare("SELECT username, firstname, lastname, password, createdAt, updatedAt FROM user WHERE id LIKE ?");
        if($statement) {
            $statement->bind_param('i', $id);
            $statement->execute();
            $statement->bind_result($username, $firstname, $lastname, $password, $createdAt, $updatedAt);
            $array = array(); 
            while ($statement->fetch()) {
                $o = new Datalog();
                $o->id = $id;
                $o->firstname = $firstname;
                $o->lastname = $lastname;
                $o->username = $username;
                $o->password = $password;
                $o->createdAt = $createdAt;
                $o->updatedAt = $updatedAt;
                $array[] = $o;
            }
            return $array;
        }
        else {
            echo $mysqli->error;
            exit;
        }
    }

    public function deleteAll()
    {
        global $mysqli;
        $statement = $mysqli->prepare("DELETE FROM user");
        $r = $statement->execute();
        return $r;
    }
    
    public function delete($id)
    {
        global $mysqli;
        $statement = $mysqli->prepare("DELETE FROM user WHERE id LIKE ?");
        $statement->bind_param('i', $id);
        $r = $statement->execute();
        return $r;
    }

    public function save() {
        global $mysqli;
        $statement = $mysqli->prepare("INSERT INTO user (username, firstname, lastname, password, createdAt, updatedAt) VALUES (?, ?, ?, ?, ?, ?)");
        $statement->bind_param('ssssdd', $this->username, $this->firstname, $this->lastname, $this->password, $this->createdAt, $this->updatedAt);
        $r = $statement->execute();
        if($r)
            return $mysqli->insert_id;
        else {
            return FALSE;
        }
    }

    public function update($username, $oldPassword, $newPassword) {
        if($this->login($username, $oldPassword) != NULL) {
            list($msec, $sec) = explode(' ', microtime());
            $timestamp = (int) ($sec.substr($msec, 2, 3));
            global $mysqli;
            $password = md5($newPassword);
            $statement = $mysqli->prepare("UPDATE user SET password=?, updatedAt=? WHERE username=?");
            $statement->bind_param('sis', $password, $timestamp, $username);
            $r = $statement->execute();
            if($r)
                return "Password updated successfully.";
            else
                return "Password updation error.";
        }
        else {
            return "Password incorrect.";
        }
    }
}
?>