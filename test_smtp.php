<?php
/**
 * ========================================
 * SMTP TEST SCRIPT
 * ========================================
 * 
 * This script tests your SMTP configuration.
 * Run this file to verify your email settings work correctly.
 * 
 * USAGE: http://localhost/your-project/test_smtp.php
 * 
 * SECURITY: Delete this file after testing in production!
 */

// Load SMTP configuration
require_once 'config/smtp_config.php';

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Enable error display for testing
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SMTP Test - Vikrant Chauhan Portfolio</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .container {
      background: white;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      max-width: 600px;
      width: 100%;
      padding: 40px;
    }

    h1 {
      color: #333;
      margin-bottom: 10px;
      font-size: 28px;
    }

    .subtitle {
      color: #666;
      margin-bottom: 30px;
      font-size: 14px;
    }

    .info-box {
      background: #f8f9fa;
      border-left: 4px solid #667eea;
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 5px;
    }

    .info-label {
      font-weight: bold;
      color: #333;
      display: inline-block;
      width: 140px;
    }

    .info-value {
      color: #666;
    }

    .btn {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      padding: 15px 30px;
      border-radius: 10px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      width: 100%;
      transition: all 0.3s ease;
      margin-top: 20px;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.5);
    }

    .btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    .result {
      margin-top: 20px;
      padding: 20px;
      border-radius: 10px;
      display: none;
    }

    .result.success {
      background: #d4edda;
      border: 2px solid #c3e6cb;
      color: #155724;
      display: block;
    }

    .result.error {
      background: #f8d7da;
      border: 2px solid #f5c6cb;
      color: #721c24;
      display: block;
    }

    .result-icon {
      font-size: 50px;
      margin-bottom: 15px;
    }

    .result-title {
      font-size: 20px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .result-message {
      font-size: 14px;
      line-height: 1.6;
    }

    .debug-output {
      background: #f1f3f5;
      padding: 15px;
      border-radius: 5px;
      margin-top: 15px;
      font-family: 'Courier New', monospace;
      font-size: 12px;
      max-height: 300px;
      overflow-y: auto;
      white-space: pre-wrap;
    }

    .warning {
      background: #fff3cd;
      border: 2px solid #ffc107;
      color: #856404;
      padding: 15px;
      border-radius: 10px;
      margin-top: 20px;
      font-size: 14px;
    }

    .warning strong {
      display: block;
      margin-bottom: 5px;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>🧪 SMTP Configuration Test</h1>
    <p class="subtitle">Test your email settings before using the contact form</p>

    <div class="info-box">
      <div><span class="info-label">SMTP Host:</span> <span class="info-value"><?php echo SMTP_HOST; ?></span></div>
      <div><span class="info-label">SMTP Port:</span> <span class="info-value"><?php echo SMTP_PORT; ?></span></div>
      <div><span class="info-label">Encryption:</span> <span class="info-value"><?php echo SMTP_ENCRYPTION; ?></span>
      </div>
      <div><span class="info-label">From Email:</span> <span class="info-value"><?php echo SMTP_FROM_EMAIL; ?></span>
      </div>
      <div><span class="info-label">Admin Email:</span> <span class="info-value"><?php echo ADMIN_EMAIL; ?></span></div>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $mail = new PHPMailer(true);
      $debugOutput = '';

      try {
        // Capture debug output
        ob_start();

        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_ENCRYPTION;
        $mail->Port = SMTP_PORT;
        $mail->Timeout = SMTP_TIMEOUT;
        $mail->CharSet = SMTP_CHARSET;

        // Recipients
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress(ADMIN_EMAIL, ADMIN_NAME);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'SMTP Test - Configuration Successful';
        $mail->Body = '
                    <h2 style="color: #667eea;">✅ SMTP Test Successful!</h2>
                    <p>Your SMTP configuration is working correctly.</p>
                    <p><strong>Test Details:</strong></p>
                    <ul>
                        <li>Date: ' . date('Y-m-d H:i:s') . '</li>
                        <li>SMTP Host: ' . SMTP_HOST . '</li>
                        <li>Port: ' . SMTP_PORT . '</li>
                        <li>Encryption: ' . SMTP_ENCRYPTION . '</li>
                    </ul>
                    <p>Your contact form is now ready to use!</p>
                ';
        $mail->AltBody = 'SMTP Test Successful! Your configuration is working correctly.';

        $mail->send();

        $debugOutput = ob_get_clean();

        echo '<div class="result success">
                        <div class="result-icon">✅</div>
                        <div class="result-title">Success! Email Sent</div>
                        <div class="result-message">
                            Test email has been sent successfully to <strong>' . ADMIN_EMAIL . '</strong><br>
                            Check your inbox (and spam folder) to confirm receipt.<br><br>
                            <strong>Your SMTP configuration is working correctly!</strong>
                        </div>
                    </div>';

        if (!empty($debugOutput)) {
          echo '<div class="debug-output">' . htmlspecialchars($debugOutput) . '</div>';
        }

      } catch (Exception $e) {
        $debugOutput = ob_get_clean();

        echo '<div class="result error">
                        <div class="result-icon">❌</div>
                        <div class="result-title">Error Sending Email</div>
                        <div class="result-message">
                            <strong>Error:</strong> ' . htmlspecialchars($mail->ErrorInfo) . '<br><br>
                            <strong>Common Solutions:</strong><br>
                            • Verify SMTP username and password in config/smtp_config.php<br>
                            • For Gmail: Use App Password (not regular password)<br>
                            • Check if firewall is blocking port ' . SMTP_PORT . '<br>
                            • Verify SMTP host and port are correct<br>
                            • Check if 2-Step Verification is enabled (Gmail)
                        </div>
                    </div>';

        if (!empty($debugOutput)) {
          echo '<div class="debug-output">' . htmlspecialchars($debugOutput) . '</div>';
        }
      }
    }
    ?>

    <form method="POST">
      <button type="submit" class="btn">🚀 Send Test Email</button>
    </form>

    <div class="warning">
      <strong>⚠️ Security Warning:</strong>
      Delete this test file (test_smtp.php) after successful testing, especially on production servers!
    </div>
  </div>
</body>

</html>