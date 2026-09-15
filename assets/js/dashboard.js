// Dashboard sidebar logic
document.addEventListener('DOMContentLoaded', () => {
    const sidebarToggle = document.querySelector('.mobile-sidebar-toggle');
    const sidebarNav = document.querySelector('.sidebar-nav-container');
    if(sidebarToggle && sidebarNav) {
        sidebarToggle.addEventListener('click', () => {
            sidebarNav.classList.toggle('active');
        });
    }
});
