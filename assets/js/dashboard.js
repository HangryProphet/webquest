function toggleMenu() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('open');
}

function closeMenu() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.remove('open');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    
    if (window.innerWidth <= 1024 && 
        !sidebar.contains(event.target) && 
        !menuToggle.contains(event.target) &&
        sidebar.classList.contains('open')) {
        closeMenu();
    }
});

// Add click handlers to roadmap nodes
document.addEventListener('DOMContentLoaded', function() {
    const levelNodes = document.querySelectorAll('.level-node');
    
    levelNodes.forEach((node, index) => {
        // Only make clickable if not locked
        if (!node.classList.contains('locked')) {
            node.style.cursor = 'pointer';
            node.addEventListener('click', function() {
                const adventureNumber = index + 1;
                window.location.href = `adventure${adventureNumber}.php`;
            });
        }
    });

    // Handle top button hover popups on mobile (tap to show/hide)
    if (window.innerWidth <= 768) {
        const topBtnWrappers = document.querySelectorAll('.top-btn-wrapper');
        
        topBtnWrappers.forEach(wrapper => {
            const btn = wrapper.querySelector('.top-btn');
            const popup = wrapper.querySelector('.hover-popup');
            
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                
                // Close all other popups
                document.querySelectorAll('.hover-popup, .sidebar-hover-popup').forEach(p => {
                    if (p !== popup) {
                        p.style.opacity = '0';
                        p.style.visibility = 'hidden';
                    }
                });
                
                // Toggle current popup
                if (popup.style.opacity === '1') {
                    popup.style.opacity = '0';
                    popup.style.visibility = 'hidden';
                } else {
                    popup.style.opacity = '1';
                    popup.style.visibility = 'visible';
                }
            });
        });

        // Handle sidebar menu hover popups on mobile (tap to show/hide)
        const menuItemWrappers = document.querySelectorAll('.menu-item-wrapper');
        
        menuItemWrappers.forEach(wrapper => {
            const menuItem = wrapper.querySelector('.menu-item');
            const popup = wrapper.querySelector('.sidebar-hover-popup');
            
            menuItem.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Close all other popups
                document.querySelectorAll('.hover-popup, .sidebar-hover-popup').forEach(p => {
                    if (p !== popup) {
                        p.style.opacity = '0';
                        p.style.visibility = 'hidden';
                    }
                });
                
                // Toggle current popup
                if (popup.style.opacity === '1') {
                    popup.style.opacity = '0';
                    popup.style.visibility = 'hidden';
                } else {
                    popup.style.opacity = '1';
                    popup.style.visibility = 'visible';
                }
            });
        });
        
        // Close popups when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.top-btn-wrapper') && !e.target.closest('.menu-item-wrapper')) {
                document.querySelectorAll('.hover-popup, .sidebar-hover-popup').forEach(popup => {
                    popup.style.opacity = '0';
                    popup.style.visibility = 'hidden';
                });
            }
        });
    }
});