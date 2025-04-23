document.addEventListener("DOMContentLoaded", () => {
    
    function toggleMenu(toggle, menu, icon, iconBefore, iconAfter) {
        toggle.addEventListener('click', function() {
            menu.classList.toggle('active');

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

});