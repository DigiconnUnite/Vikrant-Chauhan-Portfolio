<!doctype html>
<html class="no-js" lang="zxx">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Contact - Vikrant Chauhan </title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="assets/img/a-images/vikrant-logo.png">

  <!-- CSS here -->
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/css/animate.css">
  <link rel="stylesheet" href="assets/css/font-awesome-pro.css">
  <link rel="stylesheet" href="assets/css/spacing.css">
  <link rel="stylesheet" href="assets/css/index.css">
  <link rel="stylesheet" href="assets/css/about.css">
  <link rel="stylesheet" href="assets/css/contact.css">
</head>

<body>
  <?php include 'components/header.php' ?>

  <!-- Breadcrumb Section -->
  <section class="breadcrumb-area" style="background-image: url('assets/img/a-images/contact-banner.png');">
    <div class="breadcrumb-overlay"></div>
    <div class="breadcrumb-content">
      <h1 class="breadcrumb-title">Contact Us</h1>
      <div class="breadcrumb-nav">
        <a href="index.php"><i class="fa-solid fa-house"></i> Home</a>
        <span class="separator">>></span>
        <span class="current">Contact</span>
      </div>
    </div>
  </section>

  <main>

    <!-- Contact Info Cards Section -->
    <section class="contact-info-area">
      <div class="container">
        <div class="contact-info-cards">
          <!-- Location Card -->
          <div class="contact-info-card">
            <div class="contact-icon">
              <i class="fa-solid fa-location-dot"></i>
            </div>
            <h4>Location</h4>
            <p>86-एत्मादपुर विधानसभा क्षेत्र <br> (आगरा)</p>
          </div>

          <!-- Phone Card -->
          <div class="contact-info-card">
            <div class="contact-icon">
              <i class="fa-solid fa-phone"></i>
            </div>
            <h4>My Phone</h4>
            <p><a href="tel:+911234567890">+91 7148596874</a><br>
              <a href="tel:+919876543210">+91 8574859621</a>
            </p>
          </div>

          <!-- Email Card -->
          <div class="contact-info-card">
            <div class="contact-icon">
              <i class="fa-solid fa-envelope"></i>
            </div>
            <h4>Email</h4>
            <p><a>info@vikrantchauhan.com</a><br>
              <a>support@vikrantchauhan.com</a>
            </p>
          </div>

          <!-- Opening Hours Card -->
          <div class="contact-info-card">
            <div class="contact-icon">
              <i class="fa-solid fa-clock"></i>
            </div>
            <h4>Opening Hours</h4>
            <p>Mon - Fri: 9:00 AM - 6:00 PM<br>Sat - Sun: Closed</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Form Section -->
    <section class="contact-form-area">
      <div class="container">
        <div class="contact-form-wrapper">
          <!-- Left Side - Image with Overlay Card -->
          <div class="contact-image-section">
            <div class="contact-person-image">
              <img src="assets/img/a-images/contact-img.jpg" alt="Contact Person">
            </div>
          </div>

          <!-- Right Side - Contact Form -->
          <div class="contact-form-section">
            <span class="contact-form-badge">CONTACT US</span>
            <h2 class="contact-form-title">Get in Touch With Us!</h2>

            <?php
            // Display success or error message
            if (isset($_SESSION['contact_message'])) {
              $message = $_SESSION['contact_message'];
              $type = $_SESSION['contact_message_type'];
              $icon = $type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
              echo "<div class='alert-message alert-$type'>
                        <i class='fa-solid $icon'></i>
                        <span>$message</span>
                      </div>";
              // Clear the message after displaying
              unset($_SESSION['contact_message']);
              unset($_SESSION['contact_message_type']);
            }
            ?>

            <form class="contact-form" id="contactForm" action="send_email.php" method="POST" novalidate>
              <div class="form-row">
                <div class="form-group">
                  <input type="text" class="form-control" id="name" placeholder="Your Name" name="name" required>
                  <span class="error-message" id="name-error"></span>
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" id="subject" placeholder="Your Subject" name="subject"
                    required>
                  <span class="error-message" id="subject-error"></span>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <input type="email" class="form-control" id="email" placeholder="Your Email" name="email" required>
                  <span class="error-message" id="email-error"></span>
                </div>
                <div class="form-group">
                  <input type="tel" class="form-control" id="phone" placeholder="Your Phone" name="phone" required>
                  <span class="error-message" id="phone-error"></span>
                </div>
              </div>

              <div class="form-group">
                <textarea class="form-control" id="message" placeholder="Your Message" name="message" rows="5"
                  required></textarea>
                <span class="error-message" id="message-error"></span>
              </div>

              <button type="submit" class="submit-btn" id="submitBtn">
                Send Message <i class="fa-solid fa-arrow-right"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>


  </main>

  <?php include 'components/footer.php' ?>

  <script>
    // ========================================
    // FRONTEND FORM VALIDATION
    // ========================================

    document.addEventListener('DOMContentLoaded', function () {
      const form = document.getElementById('contactForm');
      const submitBtn = document.getElementById('submitBtn');

      // Get form fields
      const nameField = document.getElementById('name');
      const emailField = document.getElementById('email');
      const phoneField = document.getElementById('phone');
      const subjectField = document.getElementById('subject');
      const messageField = document.getElementById('message');

      // Validation functions
      function validateName() {
        const value = nameField.value.trim();
        if (value.length < 2) {
          showError(nameField, 'name-error');
          return false;
        }
        showSuccess(nameField, 'name-error');
        return true;
      }

      function validateEmail() {
        const value = emailField.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
          showError(emailField, 'email-error');
          return false;
        }
        showSuccess(emailField, 'email-error');
        return true;
      }

      function validatePhone() {
        const value = phoneField.value.trim();
        const phoneRegex = /^[0-9]{10,15}$/;
        const cleanedPhone = value.replace(/[^0-9]/g, '');
        if (!phoneRegex.test(cleanedPhone)) {
          showError(phoneField, 'phone-error');
          return false;
        }
        showSuccess(phoneField, 'phone-error');
        return true;
      }

      function validateSubject() {
        const value = subjectField.value.trim();
        if (value.length < 3) {
          showError(subjectField, 'subject-error');
          return false;
        }
        showSuccess(subjectField, 'subject-error');
        return true;
      }

      function validateMessage() {
        const value = messageField.value.trim();
        if (value.length < 10) {
          showError(messageField, 'message-error');
          return false;
        }
        showSuccess(messageField, 'message-error');
        return true;
      }

      function showError(field, errorId) {
        field.classList.add('error');
        field.classList.remove('success');
        document.getElementById(errorId).style.display = 'block';
      }

      function showSuccess(field, errorId) {
        field.classList.remove('error');
        field.classList.add('success');
        document.getElementById(errorId).style.display = 'none';
      }

      // Real-time validation on blur
      nameField.addEventListener('blur', validateName);
      emailField.addEventListener('blur', validateEmail);
      phoneField.addEventListener('blur', validatePhone);
      subjectField.addEventListener('blur', validateSubject);
      messageField.addEventListener('blur', validateMessage);

      // Form submission
      form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Validate all fields
        const isNameValid = validateName();
        const isEmailValid = validateEmail();
        const isPhoneValid = validatePhone();
        const isSubjectValid = validateSubject();
        const isMessageValid = validateMessage();

        // If all valid, submit form
        if (isNameValid && isEmailValid && isPhoneValid && isSubjectValid && isMessageValid) {
          // Add loading state to button
          submitBtn.classList.add('loading');
          submitBtn.innerHTML = 'Sending...';

          // Submit the form
          form.submit();
        } else {
          // Scroll to first error
          const firstError = form.querySelector('.error');
          if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
          }
        }
      });

      // Auto-hide success/error messages after 5 seconds
      const alertMessage = document.querySelector('.alert-message');
      if (alertMessage) {
        setTimeout(function () {
          alertMessage.style.opacity = '0';
          setTimeout(function () {
            alertMessage.style.display = 'none';
          }, 300);
        }, 5000);
      }
    });
  </script>

</body>

</html>