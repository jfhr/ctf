window.addEventListener('load', () => {
    function applyConsent(isConsent) {
        if (isConsent) {
            for (const el of document.getElementsByClassName('uses-cookie')) {
                el.style.display = 'list-item';
            }
        } else {
            for (const el of document.getElementsByClassName('uses-cookie')) {
                el.style.display = 'none';
            }
        }
    }

    const cookieCheckbox = document.getElementById('cookie-consent');
    cookieCheckbox.addEventListener('input', () => {
        applyConsent(cookieCheckbox.checked);
    });

    if (cookieCheckbox.checked) {
        applyConsent(true);
    }

    const target = 'ArrowUpArrowUpArrowDownArrowDownArrowLeftArrowRightArrowLeftArrowRightbaEnter';
    let actual = '';
    window.addEventListener('keyup', ev => {
        actual += ev.key;
        if (actual === target) {
            const cid = document.getElementById('ctx').dataset.cid;
            window.location = `cid.php?cid=${cid}`;
        } else if (!target.startsWith(actual)) {
            actual = ev.key;
        }
    });
});
