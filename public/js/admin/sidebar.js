document.addEventListener('DOMContentLoaded', function() {
    const leftCollapse = document.getElementById('leftCollapse');
    const rightCollapse = document.getElementById('rightCollapse');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');

    // Expand sidebar
    rightCollapse.addEventListener('click', function() {
        sidebar.classList.add('md:w-64', 'active');
        content.classList.replace('md:ml-16', 'md:ml-64');
    });

    // Collapse sidebar
    leftCollapse.addEventListener('click', function() {
        document.querySelectorAll('.peer').forEach(item => {
            item.classList.remove('active');
        });
        sidebar.classList.remove('md:w-64', 'active');
        content.classList.replace('md:ml-64', 'md:ml-16');
    });

    // Handle CMS menu and sub-menu clicks
    document.querySelectorAll('.peer').forEach(item => {
        const isCmsMenu = item.classList.contains('group/menu');
        const subMenuLinks = item.querySelectorAll('ul li a');

        if (isCmsMenu) {
            const parentLink = item.querySelector('a');
            parentLink.addEventListener('click', (e) => {
                e.preventDefault(); // Prevent navigation
                document.querySelectorAll('.peer').forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                    }
                });
                item.classList.toggle('active');
            });

            // Handle clicks on sub-menu links
            subMenuLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.stopPropagation(); // Prevent bubbling to parent .peer
                    // Allow navigation to sub-menu link
                    // No need to toggle 'active' class, as PHP handles it
                });
            });
        } else {
            // Handle clicks on non-CMS menu items
            item.addEventListener('click', () => {
                document.querySelectorAll('.peer').forEach(otherItem => {
                    otherItem.classList.remove('active');
                });
                item.classList.add('active');
            });
        }
    });
});