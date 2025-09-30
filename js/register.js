$(document).ready(function() {
    
    // Check if user is already logged in
    if (localStorage.getItem('sessionToken')) {
        window.location.href = 'profile.html';
    }

    // Form validation
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function validatePassword(password) {
        return password.length >= 6;
    }

    // Show alert message
    function showAlert(message, type) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('#alertMessage').html(alertHtml);
        
        // Auto dismiss after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }

    // Handle form submission
    $('#registerForm').on('submit', function(e) {
        e.preventDefault();
        
        const email = $('#email').val().trim();
        const password = $('#password').val();
        const confirmPassword = $('#confirmPassword').val();
        
        // Reset validation states
        $('.form-control').removeClass('is-invalid');
        
        // Validate email
        if (!validateEmail(email)) {
            $('#email').addClass('is-invalid');
            showAlert('Please enter a valid email address.', 'danger');
            return;
        }
        
        // Validate password
        if (!validatePassword(password)) {
            $('#password').addClass('is-invalid');
            showAlert('Password must be at least 6 characters long.', 'danger');
            return;
        }
        
        // Check if passwords match
        if (password !== confirmPassword) {
            $('#confirmPassword').addClass('is-invalid');
            showAlert('Passwords do not match.', 'danger');
            return;
        }
        
        // Show loading state
        $('#btnText').addClass('d-none');
        $('#btnSpinner').removeClass('d-none');
        $('#registerBtn').prop('disabled', true);
        
        // Send AJAX request
        $.ajax({
            url: 'php/register.php',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                email: email,
                password: password
            }),
            success: function(response) {
                if (response.success) {
                    showAlert('Registration successful! Redirecting to login...', 'success');
                    
                    // Redirect to login page after 2 seconds
                    setTimeout(function() {
                        window.location.href = 'login.html';
                    }, 2000);
                } else {
                    showAlert(response.message || 'Registration failed. Please try again.', 'danger');
                    resetButton();
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred. Please try again.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showAlert(errorMessage, 'danger');
                resetButton();
            }
        });
    });
    
    // Reset button state
    function resetButton() {
        $('#btnText').removeClass('d-none');
        $('#btnSpinner').addClass('d-none');
        $('#registerBtn').prop('disabled', false);
    }
    
    // Real-time validation feedback
    $('#email').on('blur', function() {
        const email = $(this).val().trim();
        if (email && !validateEmail(email)) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    $('#password').on('blur', function() {
        const password = $(this).val();
        if (password && !validatePassword(password)) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    $('#confirmPassword').on('keyup', function() {
        const password = $('#password').val();
        const confirmPassword = $(this).val();
        
        if (confirmPassword && password !== confirmPassword) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
});
