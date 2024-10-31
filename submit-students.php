<?php

die(__DIR__ . '/vendor/autoload.php');


session_start();
include_once ('submit-email.php');
require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// MySQL database credentials
$servername = $_ENV['DB_HOST'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$dbname = $_ENV['DB_NAME'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$key = 0;
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST["name"];
    $code = $_POST["code"];
    $email = $_POST["email"];
    
     // Prepare and bind SQL statement
     $student = $conn->prepare("INSERT IGNORE INTO students (name, student_code, email) VALUES (?, ?, ?)");
     $student->bind_param("sss", $name, $code, $email);
     $student->execute(); // Execute the statement

    if ( $student->affected_rows > 0 ) {
        $count_students = $conn->query("SELECT count(*) as count FROM students");
        $students_result = $count_students->fetch_assoc();
        $count_students_result = $students_result['count'];

        /* $colorsResult = $conn->query("SELECT color FROM colors");
        while ($row = $colorsResult->fetch_assoc()) {
            $colorsArray[] = $row;
        } */
            // echo "<pre>";print_r($key );echo "</pre>"; 

        switch (true) {
            case ($count_students_result > 250) and ($count_students_result <= 300):
                $color = 'Rojo';
                break;
            case ($count_students_result > 300) and ($count_students_result <= 350):
                $color = 'Azul';
                break;
            case ($count_students_result > 350) and ($count_students_result <= 400):
                $color = 'Rosa';
                break;
            case ($count_students_result > 400) and ($count_students_result <= 450):
                $color = 'Verde';
                break;
            case ($count_students_result > 450) and ($count_students_result <= 500):
                $color = 'Amarillo';
                break;
            default:
                $color = 'Roja';
                break;
        }

     $conn->query("UPDATE students SET team = '$color', status='SI' where student_code = $code");
              
    echo "¡Felicidades " . $name . "! ¡Perteneces al equipo " . $color . "!<br><br>";
    echo 'Has sido registrado (a) con éxito al Rally "Gestores Ambientales". <br>'; 
    echo "Tu código de estudiante: ". $code  . " <br>";
    echo "Tu correo institucional: " . $email . "<br><br>";
    echo "¡Mucho éxito!<br>";

    } else {
        echo "El alumno ya se encuentra registrado ";
    }

    $student->close();


    $response = send_email($email, $code, $name, $color);

}

$conn->close();
