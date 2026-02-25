<?php
/**
 * ========================================
 * CONTACT FORM EMAIL HANDLER
 * ========================================
 * 
 * This script handles contact form submissions using PHPMailer with SMTP.
 * It includes validation, sanitization, and proper error handling.
 */

// Start session for storing messages
session_start();

// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Set to 0 in production

// Load SMTP configuration
require_once 'config/smtp_config.php';

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Autoload PHPMailer (make sure you have PHPMailer installed)
require 'vendor/autoload.php';

/**
 * Sanitize input data
 */
function sanitize_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
  return $data;
}

/**
 * Validate email address
 */
function validate_email($email)
{
  return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Validate phone number (basic validation)
 */
function validate_phone($phone)
{
  // Remove all non-numeric characters
  $phone = preg_replace('/[^0-9]/', '', $phone);
  // Check if it's between 10-15 digits
  return strlen($phone) >= 10 && strlen($phone) <= 15;
}

/**
 * Send email using PHPMailer with SMTP
 */
function send_contact_email($name, $email, $phone, $subject, $message)
{
  $mail = new PHPMailer(true);

  try {
    // ========================================
    // SERVER SETTINGS
    // ========================================
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_ENCRYPTION;
    $mail->Port = SMTP_PORT;
    $mail->Timeout = SMTP_TIMEOUT;
    $mail->CharSet = SMTP_CHARSET;

    // Debug settings (enable only for troubleshooting)
    $mail->SMTPDebug = SMTP_DEBUG;

    // ========================================
    // RECIPIENTS
    // ========================================
    $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
    $mail->addAddress(ADMIN_EMAIL, ADMIN_NAME);
    $mail->addReplyTo($email, $name); // User's email for easy reply

    // ========================================
    // CONTENT
    // ========================================
    $mail->isHTML(true);
    $mail->Subject = 'New Contact Form Submission: ' . $subject;

    // Create email body with proper formatting
    $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }
                .email-container {
                    max-width: 600px;
                    margin: 20px auto;
                    background: #ffffff;
                    border-radius: 10px;
                    overflow: hidden;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                }
                .email-header {
                    background: linear-gradient(135deg, #0e3531 0%, #19443f 100%);
                    color: #ffffff;
                    padding: 30px;
                    text-align: center;
                }
                .email-header h1 {
                    margin: 0;
                    font-size: 24px;
                }
                .email-body {
                    padding: 30px;
                }
                .info-row {
                    margin-bottom: 20px;
                    padding-bottom: 15px;
                    border-bottom: 1px solid #eee;
                }
                .info-label {
                    font-weight: bold;
                    color: #0e3531;
                    display: inline-block;
                    width: 100px;
                }
                .info-value {
                    color: #555;
                }
                .message-box {
                    background: #f8f9fa;
                    padding: 20px;
                    border-radius: 8px;
                    margin-top: 20px;
                    border-left: 4px solid #e67850;
                }
                .email-footer {
                    background: #f8f9fa;
                    padding: 20px;
                    text-align: center;
                    font-size: 12px;
                    color: #666;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="email-header">
                    <h1>📧 New Contact Form Submission</h1>
                </div>
                <div class="email-body">
                    <p>You have received a new message from your website contact form.</p>
                    
                    <div class="info-row">
                        <span class="info-label">Name:</span>
                        <span class="info-value">' . htmlspecialchars($name) . '</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value"><a href="mailto:' . htmlspecialchars($email) . '">' . htmlspecialchars($email) . '</a></span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span class="info-value">' . htmlspecialchars($phone) . '</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Subject:</span>
                        <span class="info-value">' . htmlspecialchars($subject) . '</span>
                    </div>
                    
                    <div class="message-box">
                        <strong>Message:</strong><br>
                        ' . nl2br(htmlspecialchars($message)) . '
                    </div>
                </div>
                <div class="email-footer">
                    <p>This email was sent from your website contact form at ' . date('Y-m-d H:i:s') . '</p>
                    <p>Click "Reply" to respond directly to the sender.</p>
                </div>
            </div>
        </body>
        </html>
        ';

    // Plain text version for email clients that don't support HTML
    $mail->AltBody = "New Contact Form Submission\n\n"
      . "Name: $name\n"
      . "Email: $email\n"
      . "Phone: $phone\n"
      . "Subject: $subject\n\n"
      . "Message:\n$message\n\n"
      . "---\n"
      . "Sent: " . date('Y-m-d H:i:s');

    // Send email
    $mail->send();
    return ['success' => true, 'message' => 'Your message has been sent successfully! We will get back to you soon.'];

  } catch (Exception $e) {
    // Log error (in production, log to file instead of displaying)
    error_log("Email Error: {$mail->ErrorInfo}");
    return ['success' => false, 'message' => 'Sorry, there was an error sending your message. Please try again later or contact us directly.'];
  }
}

// ========================================
// PROCESS FORM SUBMISSION
// ========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Initialize error array
  $errors = [];

  // ========================================
  // VALIDATE AND SANITIZE INPUT
  // ========================================

  // Name validation
  if (empty($_POST['name'])) {
    $errors[] = 'Name is required';
  } else {
    $name = sanitize_input($_POST['name']);
    if (strlen($name) < 2) {
      $errors[] = 'Name must be at least 2 characters long';
    }
  }

  // Email validation
  if (empty($_POST['email'])) {
    $errors[] = 'Email is required';
  } else {
    $email = sanitize_input($_POST['email']);
    if (!validate_email($email)) {
      $errors[] = 'Please enter a valid email address';
    }
  }

  // Phone validation
  if (empty($_POST['phone'])) {
    $errors[] = 'Phone number is required';
  } else {
    $phone = sanitize_input($_POST['phone']);
    if (!validate_phone($phone)) {
      $errors[] = 'Please enter a valid phone number';
    }
  }

  // Subject validation
  if (empty($_POST['subject'])) {
    $errors[] = 'Subject is required';
  } else {
    $subject = sanitize_input($_POST['subject']);
    if (strlen($subject) < 3) {
      $errors[] = 'Subject must be at least 3 characters long';
    }
  }

  // Message validation
  if (empty($_POST['message'])) {
    $errors[] = 'Message is required';
  } else {
    $message = sanitize_input($_POST['message']);
    if (strlen($message) < 10) {
      $errors[] = 'Message must be at least 10 characters long';
    }
  }

  // ========================================
  // SEND EMAIL IF NO ERRORS
  // ========================================

  if (empty($errors)) {
    $result = send_contact_email($name, $email, $phone, $subject, $message);

    if ($result['success']) {
      $_SESSION['contact_message'] = $result['message'];
      $_SESSION['contact_message_type'] = 'success';
    } else {
      $_SESSION['contact_message'] = $result['message'];
      $_SESSION['contact_message_type'] = 'error';
    }
  } else {
    $_SESSION['contact_message'] = implode('<br>', $errors);
    $_SESSION['contact_message_type'] = 'error';
  }

  // Redirect back to contact page
  header('Location: contact.php');
  exit;

} else {
  // If accessed directly without POST, redirect to contact page
  header('Location: contact.php');
  exit;
}
?>