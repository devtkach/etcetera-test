// Display Taxonomy Categories Ajax

jQuery(document).ready(function($) {
    if ($('.filter')) {
        $('.filter').on('click', function () {
            var filterValue = $(this).data('filter');
            var page = 1;

            $('.filter').removeClass('active');
            $('.filter').removeClass('btn-primary');
            $('.filter').addClass('btn-secondary');

            $(this).toggleClass('btn-secondary');
            $(this).toggleClass('btn-primary');
            $(this).toggleClass('active');

            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'filter_real_estate',
                    term: filterValue,
                    page: page
                },
                success: function (response) {
                    $('#real-estate-listings').html(response);
                }
            });
        });
    }
});
