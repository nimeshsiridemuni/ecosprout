// Form Validation Logic
function showError(input, message) {
    const group = input.closest('.form-group');
    input.classList.remove('valid');
    input.classList.add('invalid');
    let error = group.querySelector('.text-error');
    if (!error) {
        error = document.createElement('span');
        error.className = 'text-error';
        error.setAttribute('aria-live', 'polite');
        group.appendChild(error);
    }
    error.textContent = message;
}

function showSuccess(input) {
    const group = input.closest('.form-group');
    input.classList.remove('invalid');
    input.classList.add('valid');
    const error = group.querySelector('.text-error');
    if (error) error.remove();
}

function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function validatePhone(phone) {
    return /^(?:0|\+94)[0-9]{9}$/.test(phone.replace(/\s/g, ''));
}

document.addEventListener('DOMContentLoaded', () => {
    // Register Form
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            let isValid = true;
            
            const name = document.getElementById('full_name');
            const email = document.getElementById('email');
            const phone = document.getElementById('phone');
            const password = document.getElementById('password');
            const confirm = document.getElementById('confirm_password');
            const terms = document.getElementById('accept_terms');
            
            if (!name.value.trim()) { showError(name, 'Full name is required'); isValid = false; } else showSuccess(name);
            if (!validateEmail(email.value)) { showError(email, 'Enter a valid email'); isValid = false; } else showSuccess(email);
            if (!validatePhone(phone.value)) { showError(phone, 'Enter a valid Sri Lankan phone number'); isValid = false; } else showSuccess(phone);
            if (password.value.length < 8) { showError(password, 'Password must be at least 8 characters'); isValid = false; } else showSuccess(password);
            if (password.value !== confirm.value) { showError(confirm, 'Passwords do not match'); isValid = false; } else showSuccess(confirm);
            if (!terms.checked) { showError(terms, 'You must accept the terms'); isValid = false; } else showSuccess(terms);
            
            if (isValid) {
                document.getElementById('demo-alert').style.display = 'block';
                // Backend integration note: PHP will handle the actual insertion
            }
        });
    }

    // Service Booking Form Minimum Date
    const dateInput = document.getElementById('preferred_date');
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
    }
});
