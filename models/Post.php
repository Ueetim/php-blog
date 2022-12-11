<?php

class Post
{
    private $conn;
    private $table = 'posts';

    // post properties
    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $created_at;

    // constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // get posts
    public function readPosts()
    {
        // create query
        $query = 'SELECT 
                c.name as category_name,
                p.id,
                p.category_id,
                p.title,
                p.body,
                p.author,
                p.created_at
            FROM 
                ' . $this->table . ' p
            LEFT JOIN 
                categories c ON p.category_id = c.id
            ORDER BY
                p.created_at DESC
        ';

        // prepare statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    public function read_single()
    {
        // create query
        $query = 'SELECT 
                c.name as category_name,
                p.id,
                p.category_id,
                p.title,
                p.body,
                p.author,
                p.created_at
            FROM 
                ' . $this->table . ' p
            LEFT JOIN 
                categories c ON p.category_id = c.id
            WHERE
                p.id = ?
            LIMIT 0,1
        ';

        // prepare statement
        $stmt = $this->conn->prepare($query);

        // bind ID
        $stmt->bindParam(1, $this->id);

        // execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // get row count
        $num = $stmt->rowCount();

        // check if posts exist
        if ($num > 0) {
            // set properties
            $this->title = $row['title'];
            $this->body = $row['body'];
            $this->author = $row['author'];
            $this->category_id = $row['category_id'];
            $this->category_name = $row['category_name'];

            $post_array = array(
                'id' => $this->id,
                'title' => $this->title,
                'body' => $this->body,
                'author' => $this->author,
                'category_id' => $this->category_id,
                'category_name' => $this->category_name
            );

            echo json_encode($post_array);
        } else {
            // no posts
            echo json_encode(
                array('message' => 'Post not found')
            );
        }


        // return $stmt;
    }
}
