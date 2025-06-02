document.addEventListener('DOMContentLoaded', function() {
    const leftCollapse = document.getElementById('leftCollapse');
    const rightCollapse = document.getElementById('rightCollapse');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');


    rightCollapse.addEventListener('click', function() {
        sidebar.classList.add('md:w-64', 'active')
        content.classList.replace('md:ml-16', 'md:ml-64')
    });

    leftCollapse.addEventListener('click', function() {
        document.querySelectorAll('.peer').forEach(item => {
            item.classList.remove('active');
        });
        sidebar.classList.remove('md:w-64', 'active')
        content.classList.replace('md:ml-64', 'md:ml-16')
    });

    document.querySelectorAll('.peer').forEach(item => {
        item.addEventListener('click', () => {
            if (item.classList.contains('active')) {
                item.classList.remove('active');
                document.querySelectorAll('.peer').forEach(item => {
                    item.classList.remove('active');
                });
            } else {
                document.querySelectorAll('.peer').forEach(item => {
                    item.classList.remove('active');
                });
                item.classList.add('active');
            }
        });
    });
});