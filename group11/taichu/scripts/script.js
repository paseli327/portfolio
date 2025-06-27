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
const modalButtons = document.querySelectorAll('.js-modal-button');
const modals = document.querySelectorAll('.js-modal');

// 各ボタンにクリックイベント
modalButtons.forEach(button => {
  button.addEventListener('click', () => {
    const targetId = button.getAttribute('data-target');
    const modal = document.getElementById(targetId);
    if (modal) {
      modal.classList.add('is-active');
      document.body.classList.add('no-scroll');
    }
  });
});

// 閉じるボタン
const closeButtons = document.querySelectorAll('.js-modal-close');
closeButtons.forEach(button => {
  button.addEventListener('click', () => {
    const modal = button.closest('.js-modal');
    modal.classList.remove('is-active');
    document.body.classList.remove('no-scroll');
  });
});

// モーダル外をクリックして閉じる
modals.forEach(modal => {
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.classList.remove('is-active');
      document.body.classList.remove('no-scroll');
    }
  });
});
//////////////////////////モーダルウィンドウ//////////////////////////
      const modalWindow = document.getElementById("modalWindow");
      const modalOpenBtns = document.getElementsByClassName("modalOpenBtn");
      const modalCloseBtn = document.getElementById("modalCloseBtn");
      const facilityData  = [
        {
          name:"新天地西洋博物館",
          imagePath1:"museum1",
          imagePath2:"museum2",
          hour:"10:00〜18:00",
          ad:"台湾台中市東区旱渓東路一段456号1階",
        },
        {
          name:"アジア近代美術館",
          imagePath1:"ajiaMuseum1",
          imagePath2:"ajiaMuseum2",
          hour:"09:30～17:00",
          ad:"413台湾台中市霧峰區柳豐路500號",
        },
        {
          name:"勤美術館",
          imagePath1:"chinMuseum1",
          imagePath2:"chinMuseum2",
          hour:"09:00〜17:00",
          ad:"台中市西區館前路71號",
        },
        {
          name:"台中文学館",
          imagePath1:"TaichungLiteratureMuseum1",
          imagePath2:"TaichungLiteratureMuseum2",
          hour:"09:00〜17:00",
          ad:"台中市楽群街38号",
        },
      ];
 
      for (let i = 0; i < modalOpenBtns.length; i++) {
        modalOpenBtns[i].addEventListener("click", function() {
          let size = getDeviceSize();
          document.getElementById('modalTitle').textContent = facilityData[i].name;
          document.getElementById('modalImage1').src = `images/${facilityData[i].imagePath1}${size}.png`;
          document.getElementById('modalImage2').src = `images/${facilityData[i].imagePath2}${size}.png`;
          document.getElementById('openingHours').textContent = "全日 : " + facilityData[i].hour;
          document.getElementById('adress').textContent = "住所 : " + facilityData[i].ad;
          modalWindow.style.display = "block";
          modalWindow.classList.remove("modalHide");
          modalWindow.classList.add("modalShow");
          document.body.classList.add("noScroll"); // スクロール禁止
          
        });
      };
      modalCloseBtn.addEventListener("click", closing);
      modalWindow.addEventListener("click", (event) => {
        if (event.target === modalWindow) {
          closing();
        };
      });
      // デバイスサイズ取得関数
      function getDeviceSize() {
        return window.matchMedia("(max-width: 768px)").matches ? "Sp" : "Pc";
      }
      // 終了時処理の関数
      function closing() {
        modalWindow.classList.remove("modalShow");
        modalWindow.classList.add("modalHide");
        setTimeout(() => {
          modalWindow.classList.remove("modalHide");
          modalWindow.style.display = "none";
          document.body.classList.remove("noScroll");
        },500);
      }
        const btn = document.getElementById('backToTop');

    btn.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'  // スムーズにスクロール
      });
    });
