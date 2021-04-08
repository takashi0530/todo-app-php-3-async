'use strict';
{
    // indexページを開いた時点でカスタムデータ属性の値のtokenを取得しておく。後に再利用するため
    const token = document.querySelector('main').dataset.token;

    // 入力した値（タスク名）
    const input = document.querySelector('[name="title"]');

    const ul = document.querySelector('ul');

    // ページ読み込み時にタスク入力にカーソルフォーカスをあてる
    input.focus();

    // ulがクリックされるたびに、中の要素にもクリックイベントが伝播していくため新しく追加した要素にもイベントを設定できるようになる
    ul.addEventListener('click', e => {
        if (e.target.type === 'checkbox') {
            fetch('?action=toggle', {
                method: 'POST',
                body: new URLSearchParams({
                    id    : e.target.parentNode.dataset.id,
                    token : token,
                }),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('このタスクは既に削除されています。');
                }
                // responseがjson形式かチェックしてその結果を返す
                return response.json();
            })
            .then(json => {
                if (json.is_done !== e.target.checked) {
                    alert('このtodoは既にアップデートされています。画面を更新します');
                    e.target.checked = json.is_done;
                }
            })
            .catch(err => {
                alert(err.message);
                // ページ全体を再読み込み
                location.reload();
            });
        }

        if (e.target.classList.contains('delete')) {
            if (!confirm('タスクを削除してもいいですか？')) {
                return;
            }
            fetch('?action=delete', {
                method: 'POST',
                body: new URLSearchParams({
                    id    : e.target.parentNode.dataset.id,
                    token : token,
                }),
            });
            // spanの親要素まるごと削除する（<li>要素ごと消す）
            e.target.parentNode.remove();

        }
    });

    function addTodo(id, titleValue) {

        // タスク行（li要素）の生成
        const li = document.createElement('li');
        li.dataset.id = id;

        // checkboxの生成
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';

        // タスク名（title）の生成
        const title = document.createElement('span');
        title.textContent = titleValue

        // 削除用xの生成
        const deleteSpan = document.createElement('span');
        deleteSpan.textContent = 'x';
        deleteSpan.classList.add('delete');

        li.appendChild(checkbox);
        li.appendChild(title);
        li.appendChild(deleteSpan);

        // 今作ったliを、ulのfirstchildの前に挿入させる
        ul.insertBefore(li, ul.firstChild);
    }

    document.querySelector('form').addEventListener('submit', e => {
        e.preventDefault();

        const title = input.value;

        fetch('?action=add', {
            method: 'POST',
            body: new URLSearchParams({
                title : title,
                token : token,
            }),
        })
        .then(response => response.json())  // 処理がreturnだけの場合、{}とreturn省略できる
        .then(json => {
            addTodo(json.id, title);
        });

        input.value = '';
        input.focus();


    })

    // 要素のクラス名がpurgeのものを監視する
    const purge = document.querySelector('.purge');
    purge.addEventListener('click', () => {
        if (!confirm('選択したタスクを全て削除してもいいですか？')) {
            return;
        }
        fetch('?action=purge', {
            method: 'POST',
            body: new URLSearchParams({
                token : token,
            }),
        });
        // 全てのli要素を定数lisに代入する
        const lis = document.querySelectorAll('li');
        lis.forEach(li => {
            if (li.children[0].checked) {   // li要素の中にある、一番最初の子要素が、checkありならtrueが返る。checkなしならfalseが返る。   <input type="checkbox" data-id="32">  <input type="checkbox" data-id="36" checked="">
                // checkされているli要素をまるごと消す
                li.remove();
            }
        });
    });




}


