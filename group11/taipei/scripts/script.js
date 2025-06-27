'use strict'

// ローディング画面の設定
window.addEventListener('load', function () {
  const loading = document.getElementById('loading');
  if (loading) {
    setTimeout(() => {
      loading.style.display = 'none';
    }, 2500);
  }
});

// ドロワーメニューの設定
const drawerMenu = document.getElementById("drawerMenu");
const drawerOpenButton = document.getElementById("drawerOpenButton");
const drawerCloseButton = document.getElementById("drawerCloseButton");

// ドロワーメニューを開く
drawerOpenButton.addEventListener("click", () => {
  drawerMenu.style.display = "block";
  requestAnimationFrame(() => {
    drawerMenu.classList.add("open");
  });
});

// ドロワーメニューを閉じる
drawerCloseButton.addEventListener("click", () => {
  drawerMenu.classList.remove("open");
  drawerMenu.addEventListener("transitionend", function hideAfterClose(e) {
    if (e.propertyName === "transform") {
      drawerMenu.style.display = "none";
      drawerMenu.removeEventListener("transitionend", hideAfterClose);
    }
  });
});

// すべてのボタンとモーダルを取得
const modalButtons = document.querySelectorAll('.jsModalButton');
const modals = document.querySelectorAll('.jsModal');

// 各ボタンにクリックイベント
modalButtons.forEach(button => {
  button.addEventListener('click', () => {
    const targetId = button.getAttribute('data-target');
    const modal = document.getElementById(targetId);
    if (modal) {
      modal.classList.add('isActive');
      document.body.classList.add('noScroll');
    }
  });
});

// 閉じるボタン
const closeButtons = document.querySelectorAll('.jsModalClose');
closeButtons.forEach(button => {
  button.addEventListener('click', () => {
    const modal = button.closest('.jsModal');
    modal.classList.remove('isActive');
    document.body.classList.remove('noScroll');
  });
});

// モーダル外をクリックして閉じる
modals.forEach(modal => {
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.classList.remove('isActive');
      document.body.classList.remove('noScroll');
    }
  });
});

//////////////////////////backToTop//////////////////////////
const btn = document.getElementById('backToTop');
btn.addEventListener('click', () => {
  window.scrollTo({top: 0, behavior: 'smooth'}); // スムーズにスクロール
});