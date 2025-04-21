import Chart from 'chart.js/auto';

(async function() {
    if (location.pathname !== "/") return; // uniquement pour la page d'accueil

    const res = await fetch("/stats");
    const data = await res.json();

    new Chart(
        document.getElementById('sucreSaleCHart'),
        {
            type: 'pie',
            data: {
                labels: [
                    'Sucré',
                    'Salé',
                    'Autre'
                ],
                datasets: [{
                    label: 'Répartition Sucré/Salé',
                    data: [data.pct_sucre, data.pct_sale, data.pct_other],
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        }
    );
})();