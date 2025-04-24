document.addEventListener("DOMContentLoaded", () => {
    
    function toggleMenu(toggleBtn, _menu, icon, iconBefore, iconAfter) {
        toggleBtn.addEventListener('click', function() {
            _menu.classList.toggle('active');

            if(icon.classList.contains(iconBefore)){
                icon.classList.remove(iconBefore);
                icon.classList.add(iconAfter);
            } else{
                icon.classList.remove(iconAfter);
                icon.classList.add(iconBefore);
            }
        });
    }

    const menuToggle = document.querySelector('.menu-toggle');
    const menu = document.querySelector('.menu')
    const menuIcon = menuToggle.querySelector('i');
    toggleMenu(menuToggle, menu, menuIcon, 'fa-bars', 'fa-times');

    const dropdownToggle = document.querySelector('.dropdown-toggle');
    if(dropdownToggle) {
        const dropdown = document.querySelector('.dropdown')
        const dropdownIcon = dropdownToggle.querySelector('i');
        toggleMenu(dropdownToggle, dropdown, dropdownIcon, 'fa-chevron-down', 'fa-chevron-up');
    }
});