      'use strict';
      /////////////ローディング画面の設定/////////////
      window.addEventListener('load', function () {////画面がロード(訪問)された時発動  /////
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

const track = document.getElementById("sliderTrack");
  const slides = document.querySelectorAll(".slide");
  const dotsContainer = document.getElementById("dotsContainer");
  const prevBtn = document.getElementById("prevBtn");
  const nextBtn = document.getElementById("nextBtn");

  const realSlideCount = 5;
  let currentIndex = 3; // クローンを含む実際の位置（最初の本物のindex）
  let autoSlideInterval;

  // ドット生成
  const dots = [];
  for (let i = 0; i < realSlideCount; i++) {
    const dot = document.createElement("span");
    dot.classList.add("dot");
    if (i === 0) dot.classList.add("active");
    dot.addEventListener("click", () => {
      currentIndex = i + 3; // クローン3枚分補正
      updateSlider();
      resetAutoSlide();
    });
    dotsContainer.appendChild(dot);
    dots.push(dot);
  }

  function getSlideOffset() {
    let GAP =0;
    if (window.matchMedia("(max-width: 768px)").matches) {
     GAP =20;
    }else{
     GAP = 80;
    }
    return slides[0].offsetWidth + GAP;
  }

  function updateSlider() {
    const offset = getSlideOffset();
    track.style.transition = 'transform 0.5s ease-in-out';
    track.style.transform = `translateX(-${offset * currentIndex}px)`;

    // ドット更新
    let dotIndex = (currentIndex - 3 + realSlideCount) % realSlideCount;
    dots.forEach(dot => dot.classList.remove("active"));
    dots[dotIndex].classList.add("active");
  }

  function nextSlide() {
    currentIndex++;
    updateSlider();

    // クローン1に達したら本物1へジャンプ
    if (currentIndex === slides.length - 3) {
      setTimeout(() => {
        track.style.transition = "none";
        currentIndex = 3;
        track.style.transform = `translateX(-${getSlideOffset() * currentIndex}px)`;
      }, 500);
    }
  }

  function prevSlide() {
    currentIndex--;
    updateSlider();

    // クローン最後に達したら本物最後へジャンプ
    if (currentIndex === 2) {
      setTimeout(() => {
        track.style.transition = "none";
        currentIndex = realSlideCount + 2; // 5 + 2 = 7
        track.style.transform = `translateX(-${getSlideOffset() * currentIndex}px)`;
      }, 500);
    }
  }

  nextBtn.addEventListener("click", () => {
    nextSlide();
    resetAutoSlide();
  });

  prevBtn.addEventListener("click", () => {
    prevSlide();
    resetAutoSlide();
  });

  function startAutoSlide() {
    autoSlideInterval = setInterval(() => {
      nextSlide();
    }, 3000);
  }

  function resetAutoSlide() {
    clearInterval(autoSlideInterval);
    startAutoSlide();
  }

  window.addEventListener("load", () => {
    updateSlider();
    startAutoSlide();
  });
  const btn = document.getElementById('backToTop');

    btn.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'  // スムーズにスクロール
      });
    });