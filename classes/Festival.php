<?php
require_once 'Connection.php';

class Festival {
    public $id;
    public $title;
    public $description;
    public $city;
    public $start_date;
    public $end_date;
    public $image_path;

    public function __construct() {
    }

    public function save() {
        $params = array(
            'title' => $this->title,
            'description' => $this->description,
            'city' => $this->city,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'image_path' => $this->image_path
        );
        
        if ($this->id === NULL) {
            $sql = "INSERT INTO festivals(
                        title, description, city, start_date, end_date, image_path
                    ) VALUES (
                        :title, :description, :city, :start_date, :end_date, :image_path
                    )";
        }
        else if ($this->id !== NULL) {
            $params["id"] = $this->id;

            $sql = "UPDATE festivals SET
                        title = :title,
                        description = :description,
                        city = :city,
                        start_date = :start_date,
                        end_date = :end_date,
                        image_path = :image_path
                    WHERE id = :id";
        }

        $conn = Connection::getInstance();
        $stmt = $conn->prepare($sql);
        $success = $stmt->execute($params);
        if (!$success) {
            throw new Exception("Failed to save festival");
        }
        else {
            $rowCount = $stmt->rowCount();
            if ($rowCount !== 1) {
                throw new Exception("Error saving festival");
            }
            if ($this->id === NULL) {
                $this->id = $conn->lastInsertId('festivals');
            }
        }
    }

    public function delete() {
        if (empty($this->id)) {
            throw new Exception("Unsaved festival cannot be deleted");
        }
        $params = array(
            'id' => $this->id
        );
        $sql = 'DELETE FROM festivals WHERE id = :id';
        $connection = Connection::getInstance();
        $stmt = $connection->prepare($sql);
        $success = $stmt->execute($params);
        if (!$success) {
            throw new Exception("Failed to delete festival");
        }
        else {
            $rowCount = $stmt->rowCount();
            if ($rowCount !== 1) {
                throw new Exception("Error deleting festival");
            }
        }
    }

    public static function all() {
        $sql = 'SELECT * FROM festivals';
        $connection = Connection::getInstance();
        $stmt = $connection->prepare($sql);
        $success = $stmt->execute();
        if (!$success) {
            throw new Exception("Failed to retrieve festivals");
        }
        else {
            $festivals = $stmt->fetchAll(PDO::FETCH_CLASS, 'Festival');
            return $festivals;
        }
    }

    public static function find($id) {
        $params = array(
            'id' => $id
        );
        $sql = 'SELECT * FROM festivals WHERE id = :id';
        $connection = Connection::getInstance();
        $stmt = $connection->prepare($sql);
        $success = $stmt->execute($params);
        if (!$success) {
            throw new Exception("Failed to retrieve festival");
        }
        else {
            $festival = $stmt->fetchObject('Festival');
            return $festival;
        }
    }
}
?>
