'use strict'

/////////////ローディング画面の設定/////////////
window.addEventListener('load', function () {   ////画面がロード(訪問)された時発動  /////
  const loading = document.getElementById('loading');
  if (loading) {
// アニメーションが2.5秒後に終わるので、その時に最初から全体を覆っているアニメーションロード画面を非表示にする
    setTimeout(() => {
      loading.style.display = 'none';
    }, 2500);
  }
});

/////////////ドロワーメニューの設定/////////////
const drawerMenu = document.getElementById("drawerMenu");
const drawerOpenButton = document.getElementById("drawerOpenButton");
const drawerCloseButton = document.getElementById("drawerCloseButton");

/////////////openボタンをクリックでドロワーメニューにopenのクラスがつく→出てくる/////////////
drawerOpenButton.addEventListener("click", () => {
  drawerMenu.style.display = "block";
  requestAnimationFrame(() => {
    drawerMenu.classList.add("open");
  });
});
/////////////closeボタンをクリックでドロワーメニューのopenのクラスが外れる→隠れる/////////////
drawerCloseButton.addEventListener("click", () => {
  drawerMenu.classList.remove("open");
  drawerMenu.addEventListener("transitionend", function hideAfterClose(e) {////常に画面にいないようにしてる(横にずれてから存在を消す)  /////
    if (e.propertyName === "transform") {
      drawerMenu.style.display = "none";
      drawerMenu.removeEventListener("transitionend", hideAfterClose);
    }
  });
});

/////////////画面サイズが変わった時のテキスト変更/////////////
updateTextBySize(); // 初回読み込み時に実行
window.addEventListener("resize", updateTextBySize);  // ウィンドウリサイズ時に実行

function updateTextBySize() {
  const homeLink = document.querySelector(".breadcrumbList li:first-child a");
  const businessHours = document.getElementById('businessHours');
  if (window.matchMedia("(max-width: 768px)").matches) {
    homeLink.textContent = "Top"; // スマホサイズの場合
    businessHours.textContent = "8:30 ～21:30";
  }else {
    homeLink.textContent = "Home"; // PCサイズの場合
    businessHours.textContent = "営業時間　8:30 – 21:30";
  }
}    
//////////////////////////モーダルウィンドウ//////////////////////////
const modalWindow = document.getElementById("modalWindow");
const modalOpenBtns = document.querySelectorAll(".modalOpenBtn");//forEachを使うためにNodeListで取得
const modalCloseBtn = document.getElementById("modalCloseBtn");

modalOpenBtns.forEach((btn) => {
  btn.addEventListener("click", function() {
    const dishName = this.id;
    let size = window.matchMedia("(max-width: 768px)").matches? "Sp" : "Pc";
    document.getElementById('modalImage').src = `images/${dishName}${size}.png`;
    modalWindow.style.display = "block";
    modalWindow.classList.remove("modalHide");
    modalWindow.classList.add("modalShow");
    document.body.classList.add("noScroll"); // スクロール禁止
  });
});
function closing() {
  modalWindow.classList.replace("modalShow", "modalHide");
  setTimeout(() => {
    modalWindow.classList.remove("modalHide");
    modalWindow.style.display = "none";
    document.body.classList.remove("noScroll");
  },500);
}
modalCloseBtn.addEventListener("click", closing);
modalWindow.addEventListener("click", (event) => {
  if (event.target === modalWindow) {
    closing();
  };
});
//////////////////////////backToTop//////////////////////////
const btn = document.getElementById('backToTop');
btn.addEventListener('click', () => {
  window.scrollTo({top: 0,behavior: 'smooth'}); // スムーズにスクロール
});