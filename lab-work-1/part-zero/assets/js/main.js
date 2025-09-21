let currentOpen = null;
let currentButton = null;

function loadScript(script, outputId, button) {
    const out = document.getElementById(outputId);

    if (currentOpen && currentOpen !== outputId) {
        document.getElementById(currentOpen).classList.remove('open');
        if (currentButton) currentButton.classList.remove('active');
    }

    if (out.classList.contains('open')) {
        out.classList.remove('open');
        button.classList.remove('active');
        currentOpen = null;
        currentButton = null;
        return;
    }

    fetch(script)
        .then(response => response.text())
        .then(data => {
            out.innerHTML = "<pre>" + data + "</pre>";
            out.classList.add('open');

            if (currentButton) currentButton.classList.remove('active');
            button.classList.add('active');

            currentOpen = outputId;
            currentButton = button;
        })
        .catch(err => alert("Ошибка загрузки: " + err));
}
