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
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        
        const email = $('#email').val().trim();
        const password = $('#password').val();
        
        // Reset validation states
        $('.form-control').removeClass('is-invalid');
        
        // Validate email
        if (!validateEmail(email)) {
            $('#email').addClass('is-invalid');
            showAlert('Please enter a valid email address.', 'danger');
            return;
        }
        
        // Validate password
        if (!password) {
            $('#password').addClass('is-invalid');
            showAlert('Please enter your password.', 'danger');
            return;
        }
        
        // Show loading state
        $('#btnText').addClass('d-none');
        $('#btnSpinner').removeClass('d-none');
        $('#loginBtn').prop('disabled', true);
        
        // Send AJAX request
        $.ajax({
            url: 'php/login.php',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                email: email,
                password: password
            }),
            success: function(response) {
                if (response.success) {
                    // Store session token in localStorage
                    localStorage.setItem('sessionToken', response.sessionToken);
                    localStorage.setItem('userEmail', email);
                    
                    showAlert('Login successful! Redirecting to profile...', 'success');
                    
                    // Redirect to profile page after 1 second
                    setTimeout(function() {
                        window.location.href = 'profile.html';
                    }, 1000);
                } else {
                    showAlert(response.message || 'Login failed. Please check your credentials.', 'danger');
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
        $('#loginBtn').prop('disabled', false);
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
    
});
