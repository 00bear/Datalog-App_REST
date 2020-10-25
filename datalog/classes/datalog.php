<?php
class Datalog {
    public $id = 0;
    public $temperature = true;
    public $datetime = "";
    public $latitude = 0;
    public $longitude = 0;
    public $motion = FALSE;
    public $createdAt = 0;
    public $updatedAt = 0;

    public function getAll() {
        global $mysqli;
        $statement = $mysqli->prepare("SELECT id, temperature, datetime, latitude, longitude, motion, createdAt, updatedAt FROM datalog ORDER BY id DESC");
        if($statement) {
            $statement->execute();
            $statement->bind_result($id, $temperature, $datetime, $latitude, $longitude, $motion, $createdAt, $updatedAt);
            $array = array(); 
            while ($statement->fetch()) {
                $o = new Datalog();
                $o->id = $id;
                $o->temperature = $temperature;
                $o->datetime = $datetime;
                $o->latitude = $latitude;
                $o->longitude = $longitude;
                $o->motion = $motion;
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

    public function getLast() {
        global $mysqli;
        $statement = $mysqli->prepare("SELECT id, temperature, datetime, latitude, longitude, motion, createdAt, updatedAt FROM datalog ORDER BY id DESC LIMIT 1");
        if($statement) {
            $statement->execute();
            $statement->bind_result($id, $temperature, $datetime, $latitude, $longitude, $motion, $createdAt, $updatedAt);
            $array = array(); 
            while ($statement->fetch()) {
                $o = new Datalog();
                $o->id = $id;
                $o->temperature = $temperature;
                $o->datetime = $datetime;
                $o->latitude = $latitude;
                $o->longitude = $longitude;
                $o->motion = $motion;
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

    public function getDatalogForDate($date)
    {
        global $mysqli;
        $datetime = $date . "%";
        $statement = $mysqli->prepare("SELECT id, temperature, datetime, latitude, longitude, motion, createdAt, updatedAt FROM datalog WHERE datetime LIKE ?");
        if($statement) {
            $statement->bind_param('s', $datetime);
            $statement->execute();
            $statement->bind_result($id, $temperature, $datetime, $latitude, $longitude, $motion, $createdAt, $updatedAt);
            $array = array(); 
            while ($statement->fetch()) {
                $o = new Datalog();
                $o->id = $id;
                $o->temperature = $temperature;
                $o->datetime = $datetime;
                $o->latitude = $latitude;
                $o->longitude = $longitude;
                $o->motion = $motion;
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
        return $r;
    }

    public function getForId($id) {
        global $mysqli;
        $statement = $mysqli->prepare("SELECT id, temperature, datetime, latitude, longitude, motion, createdAt, updatedAt FROM datalog WHERE id LIKE ?");
        if($statement) {
            $statement->bind_param('i', $id);
            $statement->execute();
            $statement->bind_result($id, $temperature, $datetime, $latitude, $longitude, $motion, $createdAt, $updatedAt);
            $array = array(); 
            while ($statement->fetch()) {
                $o = new Datalog();
                $o->id = $id;
                $o->temperature = $temperature;
                $o->datetime = $datetime;
                $o->latitude = $latitude;
                $o->longitude = $longitude;
                $o->motion = $motion;
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

    public function getCount() {
        global $mysqli;
        $statement = $mysqli->prepare("SELECT COUNT(id) FROM datalog");
        $statement->execute();
        $statement->bind_result($count);
        $statement->fetch();
        if (!$count) {
            $count = 0;
        }
        return $count;
    }

    public function deleteAll()
    {
        global $mysqli;
        $statement = $mysqli->prepare("DELETE FROM datalog");
        $r = $statement->execute();
        return $r;
    }
    
    public function delete($id)
    {
        global $mysqli;
        $statement = $mysqli->prepare("DELETE FROM datalog WHERE id LIKE ?");
        $statement->bind_param('i', $id);
        $r = $statement->execute();
        return $r;
    }

    public function save() {
        global $mysqli;
        $statement = $mysqli->prepare("INSERT INTO datalog (temperature, datetime, latitude, longitude, motion, createdAt, updatedAt) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $statement->bind_param('ssddidd', $this->temperature, $this->datetime, $this->latitude, $this->longitude, $this->motion, $this->createdAt, $this->updatedAt);
        $r = $statement->execute();
        if($r)
            return $mysqli->insert_id;
        else {
            echo $mysqli->error;
            return FALSE;
        }
    }

    public function update() {
        global $mysqli;
        $statement = $mysqli->prepare("UPDATE datalog SET temperature=?, datetime=?, latitude=?, longitude=?, motion=?, updatedAt=? WHERE id=?");
        $statement->bind_param('isssddidi', $this->temperature, $this->datetime, $this->latitude, $this->longitude, $this->motion, $this->updatedAt, $this->id);
        $r = $statement->execute();
        if($r)
            return TRUE;
        else {
            echo $mysqli->error;
            return FALSE;
        }
    }
}
?>