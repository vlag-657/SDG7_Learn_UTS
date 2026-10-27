// SDG7Learn - Script Interaktivitas Minimal UI

document.addEventListener('DOMContentLoaded', () => {
    // Toggle Menu Mobile
    const menuToggle = document.querySelector('[data-menu-toggle]');
    const navigation = document.querySelector('[data-navigation]');

    if (menuToggle && navigation) {
        menuToggle.addEventListener('click', () => {
            const isOpen = navigation.classList.toggle('is-open');
            menuToggle.setAttribute('aria-expanded', String(isOpen));
        });
    }

    // Auto-dismiss alert notifikasi setelah 5 detik
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach((alert) => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 400);
        }, 5000);
    });
});