document.addEventListener('DOMContentLoaded', function () {
 
    var searchInput = document.querySelector('.search-box input[type="text"]');
    var searchBtn = document.querySelector('.search-box button');
    var products = document.querySelectorAll('.product-link');
 
    if (searchInput && searchBtn) {
        searchBtn.addEventListener('click', function () {
            filterProducts(searchInput.value.toLowerCase());
        });
 
        searchInput.addEventListener('keyup', function (e) {
            if (e.key === 'Enter') {
                filterProducts(searchInput.value.toLowerCase());
            }
        });
    }
 
    function filterProducts(query) {
        var count = 0;
        products.forEach(function (link) {
            var text = link.textContent.toLowerCase();
            if (text.indexOf(query) !== -1) {
                link.style.display = '';
                count++;
            } else {
                link.style.display = 'none';
            }
        });
        var emptyState = document.querySelector('.empty-state');
        if (!emptyState && count === 0 && query.length > 0) {
            var container = document.querySelector('.catalog-grid');
            if (container) {
                emptyState = document.createElement('div');
                emptyState.className = 'empty-state';
                emptyState.innerHTML = '<div class="empty-icon">🔍</div><p>Ничего не найдено по запросу «' + query + '»</p>';
                container.parentElement.insertBefore(emptyState, container.nextSibling);
            }
        }
    }
 
    var cartQtyInputs = document.querySelectorAll('.cart-qty');
    cartQtyInputs.forEach(function (input) {
        input.addEventListener('change', function () {
            if (this.value < 1) this.value = 1;
            updateCartTotal();
        });
    });
 
    var cartRemoveBtns = document.querySelectorAll('.cart-remove');
    cartRemoveBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var row = this.closest('tr');
            if (row) {
                row.style.opacity = '0';
                setTimeout(function () {
                    row.remove();
                    updateCartTotal();
                }, 300);
            }
        });
    });
 
    function updateCartTotal() {
        var rows = document.querySelectorAll('.cart-table tbody tr');
        var total = 0;
        var totalItems = 0;
        rows.forEach(function (row) {
            var cells = row.querySelectorAll('td');
            if (cells.length >= 3) {
                var qty = parseInt(cells[2].querySelector('input')?.value || 1, 10);
                var priceText = cells[1].textContent.replace(/\s/g, '').replace('₽', '');
                var price = parseFloat(priceText) || 0;
                total += price * qty;
                totalItems += qty;
            }
        });
        var totalEl = document.querySelector('.summary-total span:last-child');
        if (totalEl) {
            totalEl.textContent = total.toLocaleString('ru-RU') + ' ₽';
        }
    }
 
 
    var stars = document.querySelectorAll('.star-rating-input label');
    stars.forEach(function (label) {
        label.addEventListener('mouseenter', function () {
            var rating = this.previousElementSibling?.value || this.getAttribute('for');
    
        });
    });
 
    var adminSearchInput = document.querySelector('.filters input[type="text"]');
    var adminFilterBtn = document.querySelector('.filters .btn-primary');
    var adminTableBody = document.querySelector('.admin-content .table tbody');
 
    if (adminFilterBtn && adminTableBody) {
        adminFilterBtn.addEventListener('click', function () {
            var query = adminSearchInput ? adminSearchInput.value.toLowerCase() : '';
            var rows = adminTableBody.querySelectorAll('tr');
            var count = 0;
            rows.forEach(function (row) {
                var text = row.textContent.toLowerCase();
                if (text.indexOf(query) !== -1) {
                    row.style.display = '';
                    count++;
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
 
   
    var deleteBtns = document.querySelectorAll('.btn-danger[onclick*="delete"], .btn-danger[href*="delete"]');
    deleteBtns.forEach(function (btn) {
        if (!btn.hasAttribute('data-confirm')) {
            btn.setAttribute('data-confirm', 'true');
            btn.addEventListener('click', function (e) {
                if (!confirm('Вы уверены, что хотите удалить этот элемент?')) {
                    e.preventDefault();
                }
            });
        }
    });
 
   
});