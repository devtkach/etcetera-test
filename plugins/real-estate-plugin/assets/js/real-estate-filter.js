jQuery(document).ready(function ($) {
    let currentPage = 1;
    $(document).on('click', '#real-estate-filter-submit', function (e) {
        e.preventDefault();
        currentPage = 1;
        realEstateFilter();
    });

    $('#real-estate-filter-reset').on('click', function () {
        $('#real-estate-filter')[0].reset();
        currentPage = 1;
        realEstatePagination();
    });

    $(document).on('click', '.ajax-initial-page-link', function () {
        currentPage = $(this).data('page');
        realEstatePagination();
    });

    $(document).on('click', '.ajax-page-link', function () {
        currentPage = $(this).data('page');
        realEstateFilter();
    });

    function realEstateFilter(extraData = {}) {
        var formData = {
            action: 'real_estate_filter',
            number_of_floors: $('#number_of_floors').val(),
            building_type: $('#building_type').val(),
            eco_rating: $('#eco_rating').val(),
            number_of_rooms: $('#number_of_rooms').val(),
            balcony: $('input[name="balcony"]:checked').val() || 'no',
            bathroom: $('input[name="bathroom"]:checked').val() || 'no',
            paged: currentPage,
        };

        $.extend(formData, extraData);

        $.ajax({
            url: real_estate_ajax.ajax_url,
            type: 'POST',
            data: formData,
            beforeSend: function () {
                $('#real-estate-results').html('<p>Loading...</p>');
            },
            success: function (response) {
                $('#real-estate-results').html(response);
            },
            error: function () {
                $('#real-estate-results').html('<p>There was an error processing the request.</p>');
            }
        });
    }

    function realEstatePagination(extraData = {}) {
        var formData = {
            action: 'real_estate_pagination',
            paged: currentPage,
        };

        $.extend(formData, extraData);

        $.ajax({
            url: real_estate_ajax.ajax_url,
            type: 'POST',
            data: formData,
            beforeSend: function () {
                $('#real-estate-results').html('<p>Loading...</p>');
            },
            success: function (response) {
                $('#real-estate-results').html(response);
            },
            error: function () {
                $('#real-estate-results').html('<p>There was an error processing the request.</p>');
            }
        });
    }
});
