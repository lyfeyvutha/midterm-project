<?php
class Category {
    // Database connection
    private $conn;
    private $table = 'categories';

    // Properties
    public $id;
    public $category;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all categories
    public function read() {
        // Create query
        $query = 'SELECT id, category FROM ' . $this->table . ' ORDER BY id DESC';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get single category by ID
    public function read_single() {
        // Create query
        $query = 'SELECT id, category FROM ' . $this->table . ' WHERE id = ? LIMIT 1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID parameter
        $stmt->bindParam(1, $this->id);

        // Execute query
        $stmt->execute();

        // Fetch the category details
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if category exists
        if (!$row) {
            return false;
        }

        // Set properties
        $this->id = $row['id'];
        $this->category = $row['category'];

        return true;
    }

        // Create a new category
        public function create() {
        // Define the SQL query to insert a new category
        $query = 'INSERT INTO ' . $this->table . ' (category) VALUES (:category) RETURNING id';

        // Prepare the SQL statement
        $stmt = $this->conn->prepare($query);

        // Clean data to prevent SQL injection
        $this->category = htmlspecialchars(strip_tags($this->category));

        // Bind the data to the prepared statement
        $stmt->bindParam(':category', $this->category);

        // Execute the SQL query
        if ($stmt->execute()) {
            // Fetch the newly generated ID after insertion
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            // Return true to indicate successful creation
            return true;
        }

        // If execution fails, print the error message
        printf("Error: %s.\n", $stmt->error);

        // Return false to indicate failure
        return false;
    }


    // Update an existing category
    public function update() {
        // Create query
        $query = 'UPDATE ' . $this->table . ' SET category = :category WHERE id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean and bind data
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }

    // Delete a category
    public function delete() {
        // Create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean and bind data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }
}
?>
