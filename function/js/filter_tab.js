$(document).ready(function () {
    function filterTable() {
        const searchValue = $('#searchInput').val().toLowerCase();
        const statusValue = $('.status-button.bg-indigo-100').data('status');

        $('#userTable tbody tr').each(function () {
            const rowText = $(this).text().toLowerCase();
            const statusText = $(this).find('td:nth-child(6)').text().toLowerCase();

            const matchesSearch = rowText.includes(searchValue);
            const matchesStatus = !statusValue || statusText.includes(statusValue);

            $(this).toggle(matchesSearch && matchesStatus);
        });
    }

    $('#searchInput').on('input', filterTable);

    // Handle filter tab clicks
    $('.status-button').on('click', function () {
        $('.status-button').removeClass('bg-indigo-100 text-indigo-700 font-semibold');
        $(this).addClass('bg-indigo-100 text-indigo-700 font-semibold');
        filterTable();
    });
});