<script>
    // localStorageからテーマを読み込んで設定
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
      document.documentElement.setAttribute('data-theme', savedTheme);
    }

    // ページの残りの部分がロードされたら、イベントリスナーを設定
    window.addEventListener('load', () => {
      document.querySelectorAll('.theme-controller').forEach((elem) => {
        elem.checked = elem.value === savedTheme;
        elem.addEventListener('change', (event) => {
          if (event.target.checked) {
            document.documentElement.setAttribute('data-theme', event.target.value);
            localStorage.setItem('theme', event.target.value);
          }
        });
      });
    });
  </script>