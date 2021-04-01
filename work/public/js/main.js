'use strict';
{
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            checkbox.parentNode.submit();
        });
    });

    //
    const deletes = document.querySelectorAll('.delete');
    deletes.forEach(span => {
        span.addEventListener('click', () => {
            if (!confirm('タスクを削除してもいいですか？')) {
                return;
            }
            span.parentNode.submit();
        });
    });

    const purge = document.querySelector('.purge');
    purge.addEventListener('click', () => {
        if (!confirm('全てのタスクを削除してもいいですか？')) {
            return;
        }
        purge.parentNode.submit();
    });

}


