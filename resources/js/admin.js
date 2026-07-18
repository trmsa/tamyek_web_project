$('#admin_category_image_input').on('change', function () {
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#admin_category_image').attr('src', e.target.result);
        };

        reader.readAsDataURL(this.files[0]);
    }
});
$('#admin_slider_image_input').on('change', function () {
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#admin_slider_image').attr('src', e.target.result);
        };

        reader.readAsDataURL(this.files[0]);
    }
});
$('.admin-product-image-input').on('change', function () {
    let imageId = $(this).data('image');
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(imageId).attr('src', e.target.result);
        };

        reader.readAsDataURL(this.files[0]);
    }
});
let imagesCount = Number($('#admin_products_images_count').val());
$('#admin_add_image_input_btn').on('click', function () {

    let newInput = `<div class="col-md-4 col-xl-3">
              <button type="button" class="btn btn-outline-danger btn-sm remov-parent-btn">✕</button>
              <label for="admin_product_image_input_${imagesCount}" class="w-100" role="button">
                <span class="d-block mb-2">تصویر ${imagesCount + 1}:</span>
                <img src="/images/icons/camera.webp" alt="افزودن تصویر" title="افزودن تصویر" class="admin-product-image w-100" id="admin_product_image_${imagesCount}">
              </label>
              <input type="file" name="images_${imagesCount}" id="admin_product_image_input_${imagesCount}" data-image="#admin_product_image_${imagesCount}" class="custom-file-input-hidden admin-product-image-input">
            </div>`;

    $('#admin_products_images_box').append(newInput);

    imagesCount++;
    $('#admin_products_images_count').val(imagesCount);

    $('.admin-product-image-input').on('change', function () {
        let imageId = $(this).data('image');
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(imageId).attr('src', e.target.result);
            };

            reader.readAsDataURL(this.files[0]);
        }
    });

    $('.remov-parent-btn').on('click', function () {
        $(this).parent().remove();
    });

});

$('.admin-remov-produc-image-btn').on('click', function () {
    let deletingImageIndex = $(this).val();
    $(this).parent().remove();
    $('#admin_products_images_box').append(`<input type="hidden" name="deleted_images[]" value="${deletingImageIndex}">`);
});

$('.remov-parent-btn').on('click', function () {
    $(this).parent().remove();
});

$('#admin_add_keywords_input_btn').on('click', function () {
    $('#admin_keywords_input_box').append('<div class="ms-2"><button type="button" class="btn btn-outline-danger btn-sm remov-parent-btn">✕</button><input type="text" class="form-control mini-input" name="keywords[]"></div>');

    $('.remov-parent-btn').on('click', function () {
        $(this).parent().remove();
    });

});

////postal page
$('#admin_add_near_province_btn').on('click', function () {
    $('#modal_city_selext_box').hide();
    $('#modal_add_province_city_btn').unbind('click');
    $('#modal_add_province_city_btn').on('click', function () {
        let provinceId = $('#province').val();
        let provinceName = $('#province option:selected').text();
        let newElements = `
                <div class="col-md-6 mb-4">
                    <button type="button" class="btn btn-outline-danger btn-sm remov-parent-btn">✕</button>
                    <label class="form-label">${provinceName}</label>
                    <input type="number" class="form-control hidden-arrow" name="nears_price[${provinceId}]">
                </div>
              `;
        $('#near_provinces_box').append(newElements);
        $('.remov-parent-btn').on('click', function () {
            $(this).parent().remove();
        });
    });
});

$('#admin_add_big_city_btn').on('click', function () {
    $('#modal_city_selext_box').show();
    $('#modal_add_province_city_btn').unbind('click');
    $('#modal_add_province_city_btn').on('click', function () {
        let cityId = $('#city').val();
        let cityName = $('#city option:selected').text();
        let newElements = `
                <div class="col-md-6 mb-4">
                    <button type="button" class="btn btn-outline-danger btn-sm remov-parent-btn">✕</button>
                    <label class="form-label">${cityName}</label>
                    <input type="number" class="form-control hidden-arrow" name="big_cities_price[${cityId}]">
                </div>
              `;
        $('#big_cities_box').append(newElements);
        $('.remov-parent-btn').on('click', function () {
            $(this).parent().remove();
        });
    });
});

$('#admin_add_self_province_btn').on('click', function () {
    $('#modal_city_selext_box').hide();
    $('#modal_add_province_city_btn').unbind('click');
    $('#modal_add_province_city_btn').on('click', function () {
        let provinceId = $('#province').val();
        let provinceName = $('#province option:selected').text();
        let newElements = `
                <div class="col-md-6 mb-4">
                    <button type="button" class="btn btn-outline-danger btn-sm remov-parent-btn">✕</button>
                    <label class="form-label">${provinceName}</label>
                    <input type="number" class="form-control hidden-arrow" name="self_province_price[${provinceId}]">
                </div>
              `;
        $('#self_province_box').append(newElements);
        $('.remov-parent-btn').on('click', function () {
            $(this).parent().remove();
        });
    });
});

$('#admin_add_self_city_btn').on('click', function () {
    $('#modal_city_selext_box').show();
    $('#modal_add_province_city_btn').unbind('click');
    $('#modal_add_province_city_btn').on('click', function () {
        let cityId = $('#city').val();
        let cityName = $('#city option:selected').text();
        let newElements = `
                <div class="col-md-6 mb-4">
                    <button type="button" class="btn btn-outline-danger btn-sm remov-parent-btn">✕</button>
                    <label class="form-label">${cityName}</label>
                    <input type="number" class="form-control hidden-arrow" name="self_city_price[${cityId}]">
                </div>
              `;
        $('#self_city_box').append(newElements);
        $('.remov-parent-btn').on('click', function () {
            $(this).parent().remove();
        });
    });
});