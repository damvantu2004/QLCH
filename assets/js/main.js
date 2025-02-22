document.addEventListener('DOMContentLoaded', function() {
    var searchBox = document.getElementById('search-box');
    var searchInput = document.querySelector('.search__input');
    var commentForm = document.querySelector('.comment-form');
    var evaluateBtn = document.querySelector('.evaluate__btn');

    // Kiểm tra nếu searchBox tồn tại trước khi thêm sự kiện
    if (searchBox && searchInput) {
        searchBox.addEventListener('click', function() {
            searchBox.classList.add('open');
            searchInput.focus();
        });
    }

    // Kiểm tra nếu evaluateBtn và commentForm tồn tại
    if (evaluateBtn && commentForm) {
        evaluateBtn.addEventListener('click', function() {
            commentForm.classList.toggle('active');
        });
    }
});
