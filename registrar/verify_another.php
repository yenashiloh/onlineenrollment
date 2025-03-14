<?php
// Include config file
include_once 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

// Start session and error reporting
session_start();
error_reporting(E_ALL);

// Check if the user is logged in
$registrar_id = $_SESSION['registrar_id'];
if (!isset($registrar_id)) {
    header('location: login.php');
    exit; // Stop further execution
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if student ID is provided in the form
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $student_id = $_POST['id'];
        
        $sql = "UPDATE student SET isVerified = 1 WHERE student_id = :student_id";

        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bindParam(":student_id", $param_id, PDO::PARAM_INT);

            // Set parameters
            $param_id = $_POST['id'];

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Fetch the user's email address
                $email_sql = "SELECT users.email 
                FROM student 
                INNER JOIN users ON student.userId = users.id 
                WHERE student.student_id = :student_id";
                if ($email_stmt = $conn->prepare($email_sql)) {
                    $email_stmt->bindParam(":student_id", $param_id, PDO::PARAM_INT);
                    if ($email_stmt->execute()) {
                        $row = $email_stmt->fetch(PDO::FETCH_ASSOC);
                        $recipient_email = $row['email'];

                        try {
                            //Instantiation and passing `true` enables exceptions
                            $mail = new PHPMailer(true);
                            //Enable verbose debug output
                            $mail->SMTPDebug = 0; // SMTP::DEBUG_SERVER;

                            //Send using SMTP
                            $mail->isSMTP();

                            //Set the SMTP server to send through
                            $mail->Host = 'smtp.gmail.com';

                            //Enable SMTP authentication
                            $mail->SMTPAuth = true;

                            //SMTP username
                            $mail->Username = 'easternachieveracademyoftaguig@gmail.com';

                            //SMTP password
                            $mail->Password = 'bqqitkwxkwzmaexx';

                            //Enable TLS encryption;
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

                            //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                            $mail->Port = 587;

                            //Recipients
                            $mail->setFrom('easternachieveracademyoftaguig@gmail.com', 'EAATI - REGISTRAR');

                            //Add a recipient
                            $mail->addAddress($recipient_email);
                            //Set email format to HTML
                            $mail->isHTML(true);

                            $mail->Subject = 'Verified Enrollment Registration';
                            $mail->Body = '<p>We are pleased to inform you that your application has been successfully verified. Your enrollment process is now complete.</p>
                            <p>If you have any further questions or concerns, please feel free to contact us.</p>
                            <p>Thank you,</p>
                            <p>Registrar|Eastern Achiever Academy Of Taguig</p>';

                            $mail->send();
                            header("location: enrollment.php?verified=1");
                            exit();
                        } catch (Exception $e) {
                            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        }
                    } else {
                        echo "Oops! Something went wrong while fetching email address.";
                    }
                } else {
                    echo "Oops! Something went wrong while preparing email query.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statements
        unset($stmt);
        unset($email_stmt);
    } else {
        // ID parameter is missing or invalid. Redirect to error page
        header("location: error.php");
        exit();
    }
} else {
    // Redirect to appropriate page if the form is not submitted directly
    header("location: error.php");
    exit();
}
?>
