<?php
// headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Post.php';

// instantiate database
$database = new Database();
$db = $database->connect();

// instantiate blog post object
$post = new Post($db);

// get id from url
$post->id = isset($_GET['id']) ? $_GET['id'] : die(); // if id is set, use. else, die

// get post
$post->read_single();

$post_array = array(
    'id' => $post->id,
    'title' => $post->title,
    'body' => $post->body,
    'author' => $post->author,
    'category_id' => $post->category_id,
    'category_name' => $post->category_name
);

// convert to json
print_r(json_encode($post_array));