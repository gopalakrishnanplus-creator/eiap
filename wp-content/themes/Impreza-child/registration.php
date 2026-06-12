<?php get_header(); 
//Template Name:User_Register 
?>
<style>
.container {
    width: 100%;
    max-width: 450px;
    padding: 50px;
    background: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    width: 100%;
    padding: 10px;
    background: #007BFF;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background: #0056b3;
}
.reg-cont{
    padding-top:200px;
}

/* Style for the new form row structure */
.form-row {
    margin-bottom: 15px;
}

.form-row label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-row input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.required {
    color: red;
}

@media (max-width: 600px) {
    .container {
        padding: 10px;
    }

    .form-group input,
    .form-row input {
        padding: 8px;
    }

    button {
        padding: 8px;
        font-size: 14px;
    }
}
.error-message {
    color: red;
    font-weight: bold;
    margin-top: 10px;
}
</style>

<div class="container reg-cont">
    <h2>Registration Form</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="uname" placeholder="username" 
                   value="<?php echo isset($_POST['uname']) ? esc_attr($_POST['uname']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="whatsapp">WhatsApp No</label>
            <input type="number" id="whatsapp" name="whatsapp" min="1000000000" max="9999999999" 
                   value="<?php echo isset($_POST['whatsapp']) ? esc_attr($_POST['whatsapp']) : ''; ?>" required>
            <span id="whatsappError" style="color: red; display: none;">Please enter a 10-digit number</span>
        </div>
        
        <!-- Updated IAP Registration ID Field with new solution -->
        <p class="form-row form-row-wide">
            <label for="iap_registration_id">IAP Registration ID&nbsp;<span class="required">*</span></label>
            <input type="text"
                   name="iap_registration_id"
                   id="iap_registration_id"
                   value="<?php echo isset( $_POST['iap_registration_id'] ) ? esc_attr( $_POST['iap_registration_id'] ) : ''; ?>"
                   maxlength="20"
                   placeholder="Your IAP Registration ID"
                   required />
        </p>
        
        <div class="form-group">
            <label for="email">Email ID</label>
            <input type="email" name="email" placeholder="email" 
                   value="<?php echo isset($_POST['email']) ? esc_attr($_POST['email']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="password" required>
        </div>
        <div class="form-group">
            <label for="cnf_password">Confirm Password</label>
            <input type="password" name="confpassword" placeholder="confpassword" required>
        </div>
        
        <?php wp_nonce_field( 'diap_user_register', 'diap_register_nonce' ); ?>
        
        <input type="submit" name="submit" value="Register">
    </form>

    <?php 
    global $wpdb;
    if (isset($_POST['submit'])) {
        // Verify nonce for security
        if (!wp_verify_nonce($_POST['diap_register_nonce'], 'diap_user_register')) {
            echo '<div class="error-message">Security verification failed. Please try again.</div>';
            return;
        }

        $username     = sanitize_text_field($_POST['uname']);
        $email        = sanitize_email($_POST['email']);
        $password     = sanitize_text_field($_POST['password']);
        $confpassword = sanitize_text_field($_POST['confpassword']);
        $whatsapp     = sanitize_text_field($_POST['whatsapp']);
        $iap_reg_id   = sanitize_text_field($_POST['iap_registration_id']);

        // Ensure IAP Registration ID is provided
        if (empty($iap_reg_id)) {
            echo '<div class="error-message">Please enter your IAP Registration ID.</div>';
            return;
        }

        // Validate IAP Registration ID length (max 20 characters as per maxlength)
        if (strlen($iap_reg_id) > 20) {
            echo '<div class="error-message">IAP Registration ID must be 20 characters or less.</div>';
            return;
        }

        // Password match check
        if ($password === $confpassword) {
            $user_id = wp_create_user($username, $password, $email);
            if (!is_wp_error($user_id)) {
                // Save WhatsApp number
                update_user_meta($user_id, 'whatsapp', $whatsapp);
                // Save IAP Registration ID
                update_user_meta($user_id, 'iap_registration_id', $iap_reg_id);
                // Redirect to thank you page
                echo "<script>setTimeout(function(){ window.location.href='/thank-you/'; }, 0);</script>";
            } else {
                echo '<div class="error-message">Error creating user: ' . $user_id->get_error_message() . '</div>';
            }
        } else {
            echo '<div class="error-message">Passwords do not match.</div>';
        }
    }
    ?>
</div>

<script>
    const whatsappInput = document.getElementById('whatsapp');
    const whatsappError = document.getElementById('whatsappError');

    whatsappInput.addEventListener('input', function() {
        if (!this.checkValidity()) {
            whatsappError.style.display = 'block';
        } else {
            whatsappError.style.display = 'none';
        }
    });
</script>

<?php 
get_footer();
?>