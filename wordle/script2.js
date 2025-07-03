  let answer = "";
    const maxTries = 6;
    let currentRow = 0;
    const board = document.getElementById("board");
    const guessInput = document.getElementById("guessInput");
    const submitBtn = document.getElementById("submitBtn");
    const message = document.getElementById("message");
    const loading = document.getElementById("loading");
    const keyboard = document.getElementById("keyboard");
    const isDebug = true; // デバッグ表示

    const keyboardLayout = [
      ["Q", "W", "E", "R", "T", "Y", "U", "I", "O", "P"],
      ["A", "S", "D", "F", "G", "H", "J", "K", "L"],
      ["Enter", "Z", "X", "C", "V", "B", "N", "M", "Delete"]
    ];

    // ボード作成
    for (let i = 0; i < maxTries; i++) {
      const row = document.createElement("div");
      row.className = "row";
      for (let j = 0; j < 5; j++) {
        const tile = document.createElement("div");
        tile.className = "tile";
        row.appendChild(tile);
      }
      board.appendChild(row);
    }

    // キーボード作成
    function createKeyboard() {
      keyboardLayout.forEach(row => {
        const rowDiv = document.createElement("div");
        rowDiv.className = "key-row";
        row.forEach(key => {
          const keyBtn = document.createElement("button");
          keyBtn.textContent = key;
          keyBtn.className = "key";
          if (key === "Enter") keyBtn.classList.add("enter");
          if (key === "Delete") keyBtn.classList.add("delete");
          keyBtn.onclick = () => handleKeyPress(key);
          rowDiv.appendChild(keyBtn);
        });
        keyboard.appendChild(rowDiv);
      });
    }

    // キークリック処理
    function handleKeyPress(key) {
      if (guessInput.disabled) return;

      if (key === "Enter") {
        validateAndSubmit();
      } else if (key === "Delete") {
        guessInput.value = guessInput.value.slice(0, -1);
      } else if (key.length === 1 && guessInput.value.length < 5) {
        guessInput.value += key;
      }
    }

    // キーボード色更新
    function updateKeyboardColors(guess, answer, used) {
      for (let i = 0; i < 5; i++) {
        const key = guess[i];
        const keyBtn = [...keyboard.querySelectorAll(".key")].find(k => k.textContent === key);
        if (!keyBtn) continue;

        if (guess[i] === answer[i]) {
          keyBtn.classList.remove("present", "absent");
          keyBtn.classList.add("correct");
        } else if (answer.includes(guess[i])) {
          if (!keyBtn.classList.contains("correct")) {
            keyBtn.classList.remove("absent");
            keyBtn.classList.add("present");
          }
        } else {
          if (!keyBtn.classList.contains("correct") && !keyBtn.classList.contains("present")) {
            keyBtn.classList.add("absent");
          }
        }
      }
    }

    // 入力検証
    function validateAndSubmit() {
      const guess = guessInput.value.toUpperCase();

      if (guess.length !== 5) {
        alert("5文字の単語を入力してください");
        return;
      }

      if (guess === answer) {
        submitGuess(guess);
        return;
      }

      fetch(`https://api.dictionaryapi.dev/api/v2/entries/en/${guess.toLowerCase()}`)
        .then(res => {
          if (res.ok) {
            submitGuess(guess);
          } else {
            alert("その単語は辞書に存在しません！");
          }
        })
        .catch(err => {
          console.error(err);
          alert("辞書チェック中にエラーが発生しました");
        });
    }

    // 回答処理
    function submitGuess(guess) {
      const row = board.children[currentRow];
      const used = Array(5).fill(false);

      for (let i = 0; i < 5; i++) {
        row.children[i].textContent = guess[i];
        row.children[i].className = "tile";
      }

      for (let i = 0; i < 5; i++) {
        if (guess[i] === answer[i]) {
          row.children[i].classList.add("correct");
          used[i] = true;
        }
      }

      for (let i = 0; i < 5; i++) {
        if (guess[i] === answer[i]) continue;

        if (answer.includes(guess[i])) {
          let added = false;
          for (let j = 0; j < 5; j++) {
            if (guess[i] === answer[j] && !used[j]) {
              row.children[i].classList.add("present");
              used[j] = true;
              added = true;
              break;
            }
          }
          if (!added) row.children[i].classList.add("absent");
        } else {
          row.children[i].classList.add("absent");
        }
      }

      updateKeyboardColors(guess, answer, used);

      if (guess === answer) {
        message.textContent = "正解！おめでとう！";
        disableInput();
        return;
      }

      currentRow++;
      guessInput.value = "";

      if (currentRow >= maxTries) {
        message.textContent = `残念！正解は「${answer}」でした。`;
        disableInput();
      }
    }

    function disableInput() {
      guessInput.disabled = true;
      submitBtn.disabled = true;
    }

    // 単語取得
    fetch("https://random-word-api.vercel.app/api?words=1&length=5")
      .then(res => res.json())
      .then(data => {
        answer = data[0].toUpperCase();
        if (isDebug) console.log("正解（デバッグ）:", answer);
        loading.textContent = "プレイ開始！";
        guessInput.disabled = false;
        submitBtn.disabled = false;
      })
      .catch(err => {
        loading.textContent = "単語の取得に失敗しました。ページを再読み込みしてください。";
        console.error(err);
      });

    createKeyboard(); // 最後にキーボード生成