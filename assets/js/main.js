// Main functionality
document.addEventListener('DOMContentLoaded', () => {
    // Mobile Menu Toggle
    const menuBtn = document.querySelector('.mobile-menu-btn');
    const mainNav = document.querySelector('.main-nav');
    if(menuBtn && mainNav) {
        menuBtn.addEventListener('click', () => {
            const isExpanded = menuBtn.getAttribute('aria-expanded') === 'true';
            menuBtn.setAttribute('aria-expanded', !isExpanded);
            mainNav.classList.toggle('active');
        });
    }

    // Password Toggle
    const togglePasswords = document.querySelectorAll('.toggle-password');
    togglePasswords.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const input = e.target.previousElementSibling;
            if (input && input.type === 'password') {
                input.type = 'text';
                e.target.textContent = '🙈';
            } else if (input) {
                input.type = 'password';
                e.target.textContent = '👁️';
            }
        });
    });
});
