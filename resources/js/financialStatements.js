document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('filterForm');
    const resultsContainer = document.getElementById('results');
    const paginationContainer = document.getElementById('pagination');

    if (filterForm) {
        // Add event listeners to the inputs
        filterForm.addEventListener('input', function () {
            const search = document.getElementById('search').value;
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            // Send AJAX request
            fetch(`${filterForm.action}?search=${search}&start_date=${startDate}&end_date=${endDate}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
                .then((response) => response.text())
                .then((html) => {
                    // Update the table content
                    resultsContainer.innerHTML = html;

                    // Clear pagination if not needed
                    paginationContainer.innerHTML = '';
                })
                .catch((error) => console.error('Error:', error));
        });
    }
});
