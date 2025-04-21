import './stats.js'

(() => {
    if (location.pathname !== "/") return; // uniquement pour la page d'accueil

    document.querySelectorAll(".btn.btn-danger").forEach(el => {
        el.addEventListener("click", (e) => {
            const id = el.getAttribute("data-id")
            const csrf = el.getAttribute("data-csrf")

            fetch(`/liste/${id}`, {
                method: "DELETE",
                headers: {},
                body: JSON.stringify({
                    _token: csrf
                })
            })
                .then(r => {
                    console.log(r);
                    if (r.status === 200) {
                        el.parentElement.parentElement.parentElement.remove()
                    }
                })
        })
    })
})()