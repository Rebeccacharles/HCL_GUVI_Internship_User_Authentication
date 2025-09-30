$(document).ready(function() {
    
    // Check if user is logged in
    const sessionToken = localStorage.getItem('sessionToken');
    const userEmail = localStorage.getItem('userEmail');
    
    if (!sessionToken || !userEmail) {
        window.location.href = 'login.html';
        return;
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

    // Load user profile
    function loadProfile() {
        $.ajax({
            url: 'php/profile.php',
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-Session-Token': sessionToken
            },
            success: function(response) {
                if (response.success) {
                    // Populate form with user data
                    $('#email').val(response.data.email || userEmail);
                    $('#fullName').val(response.data.fullName || '');
                    $('#age').val(response.data.age || '');
                    $('#dob').val(response.data.dob || '');
                    $('#contact').val(response.data.contact || '');
                    $('#address').val(response.data.address || '');
                } else {
                    if (response.message === 'Invalid or expired session') {
                        showAlert('Session expired. Please login again.', 'warning');
                        setTimeout(function() {
                            logout();
                        }, 2000);
                    } else {
                        showAlert(response.message || 'Failed to load profile.', 'danger');
                    }
                }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    showAlert('Session expired. Please login again.', 'warning');
                    setTimeout(function() {
                        logout();
                    }, 2000);
                } else {
                    showAlert('An error occurred while loading profile.', 'danger');
                }
            }
        });
    }

    // Update profile
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            fullName: $('#fullName').val().trim(),
            age: $('#age').val(),
            dob: $('#dob').val(),
            contact: $('#contact').val().trim(),
            address: $('#address').val().trim()
        };
        
        // Show loading state
        $('#btnText').addClass('d-none');
        $('#btnSpinner').removeClass('d-none');
        $('#updateBtn').prop('disabled', true);
        
        $.ajax({
            url: 'php/update_profile.php',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            headers: {
                'X-Session-Token': sessionToken
            },
            data: JSON.stringify(formData),
            success: function(response) {
                if (response.success) {
                    showAlert('Profile updated successfully!', 'success');
                } else {
                    if (response.message === 'Invalid or expired session') {
                        showAlert('Session expired. Please login again.', 'warning');
                        setTimeout(function() {
                            logout();
                        }, 2000);
                    } else {
                        showAlert(response.message || 'Failed to update profile.', 'danger');
                    }
                }
                resetButton();
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    showAlert('Session expired. Please login again.', 'warning');
                    setTimeout(function() {
                        logout();
                    }, 2000);
                } else {
                    let errorMessage = 'An error occurred while updating profile.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showAlert(errorMessage, 'danger');
                }
                resetButton();
            }
        });
    });
    
    // Reset button state
    function resetButton() {
        $('#btnText').removeClass('d-none');
        $('#btnSpinner').addClass('d-none');
        $('#updateBtn').prop('disabled', false);
    }

    // Logout functionality
    function logout() {
        localStorage.removeItem('sessionToken');
        localStorage.removeItem('userEmail');
        window.location.href = 'login.html';
    }

    $('#logoutBtn').on('click', function() {
        logout();
    });

    // Load profile on page load
    loadProfile();
    
});
