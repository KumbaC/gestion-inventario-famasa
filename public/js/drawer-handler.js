(function() {
    window.openDrawer = function(drawerId) {
        console.log('Opening drawer:', drawerId);
        
        if (window.LaraDrawers && window.LaraDrawers[drawerId]) {
            console.log('Opening drawer via registry');
            window.LaraDrawers[drawerId].open = true;
            return;
        }
        
        const drawerEl = document.querySelector(`[data-drawer-id="${drawerId}"]`);
        if (drawerEl && window.Alpine) {
            console.log('Opening drawer via Alpine');
            const alpineInstance = window.Alpine.getComponent(drawerEl);
            if (alpineInstance) {
                alpineInstance.open = true;
                return;
            }
        }
    
        console.log('Opening drawer via event dispatch');
        window.dispatchEvent(new CustomEvent('open-drawer-' + drawerId));
    };
    
    // Initialize all drawer triggers
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-drawer-trigger]').forEach(function(element) {
            element.addEventListener('click', function(e) {
                const drawerId = this.getAttribute('data-drawer-trigger');
                if (drawerId) {
                    e.preventDefault();
                    window.openDrawer(drawerId);
                    return false;
                }
            });
        });
    });
})();
