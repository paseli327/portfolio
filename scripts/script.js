document.getElementById('searchButton').addEventListener('click', function() {
        var searchQuery = document.getElementById('search').value;
        if (searchQuery) {
            window.location.href = 'product.php?search_query=' + encodeURIComponent(searchQuery);
        }
    });

    document.getElementById('search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            var searchQuery = document.getElementById('search').value;
            if (searchQuery) {
                window.location.href = 'product.php?search_query=' + encodeURIComponent(searchQuery);
            }
        }
    });

// パスワード欄で全ての記号を禁止
    // 半角記号すべてを対象とするパターン（PCとスマホの両方に対応）
    const symbolPattern = /[!-/:#-@[-`{-~！-／：-＠［-｀｛-～、-ヶ]/; 

    ['passwordInput', 'passwordConfirmInput'].forEach(function(id) {
        const inputElement = document.getElementById(id);
        if (inputElement) {
            inputElement.addEventListener('input', function(event) {
                let value = this.value;
                this.value = value.replace(symbolPattern, '');
            });
        }
    });