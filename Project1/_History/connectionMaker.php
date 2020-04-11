<?php

function get_connection(){
//    $conn = mysqli_connect('127.0.0.1', 'root', '12345678', 'g3t02');
    $conn = mysqli_connect('localhost', 'root', '', 'g3t02');
    return $conn;
}