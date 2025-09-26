document.addEventListener('DOMContentLoaded', function() {
    // Menu activation
    const menuItems = document.querySelectorAll('.admin-menu li');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            menuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Sidebar toggle functionality
    const sidebar = document.querySelector('.admin-sidebar');
    const mainContent = document.querySelector('.admin-main');
    const sidebarToggle = document.getElementById('sidebarToggle');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');

            // Change icon based on state
            const icon = sidebarToggle.querySelector('i');
            if (sidebar.classList.contains('collapsed')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }

    // Notification dropdown functionality
    const notificationIcon = document.getElementById('notificationIcon');
    const notificationDropdown = document.getElementById('notificationDropdown');

    if (notificationIcon) {
        notificationIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle('active');
            if (userDropdown) userDropdown.classList.remove('active');
        });
    }

    // User dropdown functionality
    const adminUser = document.getElementById('adminUser');
    const userDropdown = document.getElementById('userDropdown');

    if (adminUser) {
        adminUser.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
            if (notificationDropdown) notificationDropdown.classList.remove('active');
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (notificationIcon && notificationDropdown &&
            !notificationIcon.contains(e.target) && !notificationDropdown.contains(e.target)) {
            notificationDropdown.classList.remove('active');
        }

        if (adminUser && userDropdown &&
            !adminUser.contains(e.target) && !userDropdown.contains(e.target)) {
            userDropdown.classList.remove('active');
        }
    });

    // Responsive adjustments
    function checkWidth() {
        if (window.innerWidth <= 768) {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('expanded');
        } else {
            sidebar.classList.remove('collapsed');
            mainContent.classList.remove('expanded');
        }
    }

    checkWidth();
    window.addEventListener('resize', checkWidth);
});
