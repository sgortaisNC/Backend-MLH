const btnAlerte = document.getElementById('btnAlerte');
if (btnAlerte) {

    const divAlerte = document.getElementById('alerte');

    btnAlerte.addEventListener("click", () => {
        window.localStorage.setItem('alerte',divAlerte.dataset.alerteid);
        divAlerte.remove();
    });
}

const alertDOM = document.querySelector('#alerte');

if (alertDOM && window.localStorage.getItem('alerte') == alertDOM.dataset.alerteid) {
    alertDOM.remove();
}


