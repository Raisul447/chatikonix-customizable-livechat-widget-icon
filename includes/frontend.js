document.addEventListener('DOMContentLoaded', function() {
    var wrapper = document.getElementById('rslclwifw-livechat-wrapper');
    if (!wrapper) return;

    var btn = wrapper.querySelector('.rslclwifw-main-btn');
    if (btn) {
        btn.addEventListener('click', function(e) {
            e.stopPropagation(); 
            wrapper.classList.toggle('rslclwifw-active');
        });

        document.addEventListener('click', function(e) {
            if (!wrapper.contains(e.target)) {
                wrapper.classList.remove('rslclwifw-active');
            }
        });
    }
});