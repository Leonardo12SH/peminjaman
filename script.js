// JavaScript for PT. Visdat Teknik Utama Registration Website

// FilePond Configuration
document.addEventListener('DOMContentLoaded', function() {
    // Register FilePond plugins
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginFileValidateType,
        FilePondPluginFileValidateSize
    );

    // Initialize FilePond for all file inputs
    const fileInputs = document.querySelectorAll('.filepond');
    
    fileInputs.forEach(input => {
        const pond = FilePond.create(input, {
            labelIdle: 'Drag & Drop file atau <span class="filepond--label-action">Browse</span>',
            labelFileWaitingForSize: 'Menunggu ukuran file',
            labelFileSizeNotAvailable: 'Ukuran file tidak tersedia',
            labelFileLoading: 'Loading',
            labelFileLoadError: 'Error saat loading',
            labelFileProcessing: 'Uploading',
            labelFileProcessingComplete: 'Upload selesai',
            labelFileProcessingAborted: 'Upload dibatalkan',
            labelFileProcessingError: 'Error saat upload',
            labelFileProcessingRevertError: 'Error saat revert',
            labelFileRemoveError: 'Error saat hapus',
            labelTapToCancel: 'tap untuk cancel',
            labelTapToRetry: 'tap untuk retry',
            labelTapToUndo: 'tap untuk undo',
            labelButtonRemoveItem: 'Hapus',
            labelButtonAbortItemLoad: 'Abort',
            labelButtonRetryItemLoad: 'Retry',
            labelButtonAbortItemProcessing: 'Cancel',
            labelButtonUndoItemProcessing: 'Undo',
            labelButtonRetryItemProcessing: 'Retry',
            labelButtonProcessItem: 'Upload',
            
            // File validation
            acceptedFileTypes: getAcceptedTypes(input.name),
            maxFileSize: '5MB',
            maxFiles: 1,
            
            // Upload configuration
            server: {
                process: 'upload.php',
                revert: 'delete.php',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            },
            
            // Styling
            stylePanelLayout: 'compact',
            styleLoadIndicatorPosition: 'center bottom',
            styleProgressIndicatorPosition: 'right bottom',
            styleButtonRemoveItemPosition: 'left bottom',
            styleButtonProcessItemPosition: 'right bottom'
        });

        // Store FilePond instance for later use
        input.filePond = pond;
    });

    // Form validation and submission
    const form = document.getElementById('registrationForm');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
    }

    // Dynamic form behavior
    setupDynamicForm();
});

// Get accepted file types based on input name
function getAcceptedTypes(inputName) {
    switch(inputName) {
        case 'cv_file':
            return ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        case 'photo_file':
            return ['image/jpeg', 'image/jpg', 'image/png'];
        case 'certificate_file':
        case 'sim_file':
            return ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        default:
            return ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/jpg', 'image/png'];
    }
}

// Handle form submission
function handleFormSubmit(e) {
    e.preventDefault();
    
    // Show loading
    showLoading();
    
    // Validate form
    if (!validateForm()) {
        hideLoading();
        return false;
    }
    
    // Create FormData
    const formData = new FormData(e.target);
    
    // Add FilePond files
    const fileInputs = document.querySelectorAll('.filepond');
    fileInputs.forEach(input => {
        if (input.filePond && input.filePond.getFiles().length > 0) {
            const file = input.filePond.getFiles()[0];
            if (file.file) {
                formData.set(input.name, file.file);
            }
        }
    });
    
    // Submit form
    fetch('process.php', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showMessage('Lamaran Anda berhasil dikirim! Terima kasih telah mendaftar di PT. Visdat Teknik Utama.', 'success');
            e.target.reset();
            // Reset FilePond
            fileInputs.forEach(input => {
                if (input.filePond) {
                    input.filePond.removeFiles();
                }
            });
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            showMessage(data.message || 'Terjadi kesalahan saat mengirim lamaran. Silakan coba lagi.', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showMessage('Terjadi kesalahan saat mengirim lamaran. Silakan coba lagi.', 'error');
    });
}

// Form validation
function validateForm() {
    let isValid = true;
    const requiredFields = [
        'full_name', 'email', 'phone', 'birth_date', 'gender', 'position', 
        'education', 'experience_years', 'address', 'work_vision', 'work_mission', 'motivation'
    ];
    
    // Clear previous error messages
    document.querySelectorAll('.error-message').forEach(el => el.remove());
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    
    // Validate required fields
    requiredFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field && !field.value.trim()) {
            showFieldError(field, 'Field ini wajib diisi');
            isValid = false;
        }
    });
    
    // Validate email
    const email = document.getElementById('email');
    if (email && email.value && !isValidEmail(email.value)) {
        showFieldError(email, 'Format email tidak valid');
        isValid = false;
    }
    
    // Validate phone
    const phone = document.getElementById('phone');
    if (phone && phone.value && !isValidPhone(phone.value)) {
        showFieldError(phone, 'Format nomor telepon tidak valid');
        isValid = false;
    }
    
    // Validate required files
    const cvFile = document.querySelector('input[name="cv_file"]');
    const photoFile = document.querySelector('input[name="photo_file"]');
    
    if (cvFile && cvFile.filePond && cvFile.filePond.getFiles().length === 0) {
        showMessage('CV/Resume wajib diupload', 'error');
        isValid = false;
    }
    
    if (photoFile && photoFile.filePond && photoFile.filePond.getFiles().length === 0) {
        showMessage('Foto 3x4 wajib diupload', 'error');
        isValid = false;
    }
    
    return isValid;
}

// Show field error
function showFieldError(field, message) {
    field.classList.add('is-invalid');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message text-danger small mt-1';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
}

// Email validation
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Phone validation
function isValidPhone(phone) {
    const phoneRegex = /^[\+]?[0-9\-\(\)\s]{8,20}$/;
    return phoneRegex.test(phone.replace(/\s/g, ''));
}

// Show loading
function showLoading() {
    let loading = document.querySelector('.loading');
    if (!loading) {
        loading = document.createElement('div');
        loading.className = 'loading';
        loading.innerHTML = '<div class="loading-spinner"></div>';
        document.body.appendChild(loading);
    }
    loading.style.display = 'flex';
}

// Hide loading
function hideLoading() {
    const loading = document.querySelector('.loading');
    if (loading) {
        loading.style.display = 'none';
    }
}

// Show message
function showMessage(message, type) {
    // Remove existing messages
    document.querySelectorAll('.alert').forEach(el => el.remove());
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert at the top of the form
    const form = document.getElementById('registrationForm');
    form.parentNode.insertBefore(alertDiv, form);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Setup dynamic form behavior
function setupDynamicForm() {
    const positionSelect = document.getElementById('position');
    const simFileContainer = document.querySelector('input[name="sim_file"]').closest('.col-md-6');
    const certificateFileContainer = document.querySelector('input[name="certificate_file"]').closest('.col-md-6');
    
    // Show/hide SIM upload based on position
    positionSelect.addEventListener('change', function() {
        if (this.value === 'Driver') {
            simFileContainer.style.display = 'block';
            simFileContainer.querySelector('label').innerHTML = 'SIM A/C (wajib untuk Driver) *';
        } else {
            simFileContainer.style.display = 'block';
            simFileContainer.querySelector('label').innerHTML = 'SIM A/C (jika ada)';
        }
        
        // Show certificate field for technical positions
        const technicalPositions = ['Teknisi FOT', 'Teknisi FOC', 'Teknisi Jointer'];
        if (technicalPositions.includes(this.value)) {
            certificateFileContainer.querySelector('label').innerHTML = 'Sertifikat K3 (wajib untuk posisi teknis) *';
        } else {
            certificateFileContainer.querySelector('label').innerHTML = 'Sertifikat K3 (jika ada)';
        }
    });
    
    // Auto-format phone number
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.startsWith('0')) {
            value = '62' + value.substring(1);
        }
        if (value.startsWith('8')) {
            value = '62' + value;
        }
        
        // Format: +62 xxx xxxx xxxx
        if (value.startsWith('62')) {
            value = value.replace(/^62/, '+62 ');
            value = value.replace(/(\+62 \d{3})(\d{4})(\d{4})/, '$1 $2 $3');
        }
        
        this.value = value;
    });
    
    // Character counter for textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        const maxLength = textarea.getAttribute('maxlength') || 1000;
        const counter = document.createElement('small');
        counter.className = 'text-muted float-end';
        counter.textContent = `0/${maxLength}`;
        textarea.parentNode.appendChild(counter);
        
        textarea.addEventListener('input', function() {
            const length = this.value.length;
            counter.textContent = `${length}/${maxLength}`;
            
            if (length > maxLength * 0.9) {
                counter.className = 'text-warning float-end';
            } else {
                counter.className = 'text-muted float-end';
            }
        });
    });
}

// Smooth scrolling for form sections
function scrollToSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
    }
}

// Print function for admin
function printApplication(applicationId) {
    window.open(`print.php?id=${applicationId}`, '_blank');
}

// Export to PDF function for admin
function exportToPDF(applicationId) {
    window.location.href = `export.php?id=${applicationId}&format=pdf`;
}