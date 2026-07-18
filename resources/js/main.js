import jQuery from 'jquery';
window.$ = jQuery;

$(() => {

    $('.mobile-header-menu-btn').on('click', function () {
        $('.mobile-header-menu-list').slideToggle('slow');
    });
    $('.alert-close-btn').on('click', function () {
        $(this).parent().hide();
    });

    $('#send_otp_btn').on('click', function (e) {
        e.preventDefault();
        if ($('#accept_rules').is(':checked')) {
            $('#send_otp_form').submit();
        } else {
            $('.alert-danger').text('لطفا ابتدا با قوانین و مقررات موافقت بفرمایید.').show();
            setTimeout(function () {
                $('.alert-danger').hide();
            }, 5000);
        }
    });

    setTimeout(function () {
        $('.alert').hide();
    }, 7000);

    //products search
    let productsTitle = $('.auto-complate-box li').map(function () {
        return $(this).text();
    }).get();
    function changeSearch() {
        let baseSearch = $('.base_search:checked').val();
        if (baseSearch == 'name') {
            $('#search_form').removeClass('hidden');
            $('#name_search_result').removeClass('hidden');
            $('#nutrient_search_form').addClass('hidden');
            $('#nutrient_search_result').addClass('hidden');
        } else {
            $('#search_form').addClass('hidden');
            $('#name_search_result').addClass('hidden');
            $('#nutrient_search_form').removeClass('hidden');
            $('#nutrient_search_result').removeClass('hidden');
        }
    }
    changeSearch();
    $('.base_search').on('input', changeSearch);

    $('.search-input').on('keyup', function () {

        let searchWord = $(this).val();
        let autoComplateBox = $('.auto-complate-box');
        if (searchWord) {
            autoComplateBox.addClass('active');

            let filteredWords = productsTitle.filter(function (word) {
                return word.includes(searchWord);
            });

            productsTitleWordsGenerator(filteredWords);
        } else {
            autoComplateBox.removeClass('active');
        }
    });

    function productsTitleWordsGenerator(wordsArray) {

        $('.auto-complate-box li').addClass('hidden');

        wordsArray.forEach(function (word) {
            $(`.auto-complate-box li:contains(${word})`).removeClass('hidden');
        });

        if ($('.auto-complate-box li:not(.hidden)').length == 0) {
            $('.auto-complate-box').removeClass('active');
        }

        select();
    }

    function select() {
        $('.auto-complate-box li').on('click', function () {
            let selectedFood = $(this).text();
            $('.search-input').val(selectedFood);
            $('.auto-complate-box').removeClass('active');
            $('#search_form').submit();
        });
    }

    var searchIsDown = false,
        searchStartY,
        searchScrollTop;

    var $searchSlider = $(".search-nutrients-box");

    $searchSlider.on("mousedown touchstart", function (e) {
        searchIsDown = true;
        searchStartY = (e.pageY || e.originalEvent.touches[0].pageY) - $searchSlider.offset().top;
        searchScrollTop = $searchSlider.scrollTop();
        $searchSlider.css("cursor", "grabbing");
    });

    $(window).on("mouseup touchend", function () {
        searchIsDown = false;
        $searchSlider.css("cursor", "grab");
    });

    $searchSlider.on("mousemove touchmove", function (e) {
        if (!searchIsDown) return;
        e.preventDefault();
        var y = (e.pageY || e.originalEvent.touches[0].pageY) - $searchSlider.offset().top;
        var walk = (y - searchStartY) * 1; // سرعت اسکرول
        $searchSlider.scrollTop(searchScrollTop - walk);
    });


    //products search end

    //product page
    ////get src small img and set to big img
    $('.product-product-small-image').on('click', function () {
        $('.product-product-image').attr('src', $(this).attr('src'));
    });

    ////scroll images box
    $(".product-product-images-box").on("dragstart", function (e) {
        e.preventDefault();
    });

    var productImagesIsDown = false,
        productImagesStartX,
        productImagesScrollLeft;

    var $productImagesSlider = $(".product-product-images-box");

    $productImagesSlider.on("mousedown touchstart", function (e) {
        productImagesIsDown = true;
        productImagesStartX = (e.pageX || e.originalEvent.touches[0].pageX) - $productImagesSlider.offset().left;
        productImagesScrollLeft = $productImagesSlider.scrollLeft();
        $productImagesSlider.css("cursor", "grabbing");
    });

    $(window).on("mouseup touchend", function () {
        productImagesIsDown = false;
        $productImagesSlider.css("cursor", "grab");
    });

    $productImagesSlider.on("mousemove touchmove", function (e) {
        if (!productImagesIsDown) return;
        e.preventDefault();
        var x = (e.pageX || e.originalEvent.touches[0].pageX) - $productImagesSlider.offset().left;
        var walk = (x - productImagesStartX) * 2;
        $productImagesSlider.scrollLeft(productImagesScrollLeft - walk);
    });

    //// handel dynamic progress bar width
    $('.progress-bar').each(function () {
        let progress = $(this).data('progress');
        $(this).css('--progress', progress);
    })

    ////scroll products box
    let productsScrollBox = $('.products-container-scroll');
    let productsScrollBoxWidth = productsScrollBox[0] ? productsScrollBox[0].scrollWidth - productsScrollBox.width() : 0;
    let productsScrolled = 0;
    $(".related-product-box").on("dragstart", function (e) {
        e.preventDefault();
    });

    var relatedProdutsIsDown = false,
        relatedProdutsStartX,
        relatedProdutsScrollLeft;

    productsScrollBox.on("mousedown touchstart", function (e) {
        clearInterval(productsScrollInterval);
        relatedProdutsIsDown = true;
        relatedProdutsStartX = (e.pageX || e.originalEvent.touches[0].pageX) - productsScrollBox.offset().left;
        relatedProdutsScrollLeft = productsScrollBox.scrollLeft();
        productsScrollBox.css("cursor", "grabbing");
    });

    $(window).on("mouseup touchend", function () {
        relatedProdutsIsDown = false;
        productsScrollBox.css("cursor", "grab");
        // productsScrollInterval = setInterval(productsInterval, 5000);
    });

    productsScrollBox.on("mousemove touchmove", function (e) {
        if (!relatedProdutsIsDown) return;
        e.preventDefault();
        var x = (e.pageX || e.originalEvent.touches[0].pageX) - productsScrollBox.offset().left;
        var walk = (x - relatedProdutsStartX) * 2;
        productsScrollBox.scrollLeft(relatedProdutsScrollLeft - walk);
    });

    let productsScrollInterval = setInterval(productsInterval, 3000);

    function productsInterval() {
        if (productsScrolled == -productsScrollBoxWidth) {
            productsScrolled = 0;
        } else {
            productsScrolled = productsScrolled - 250;
            if (productsScrolled < -productsScrollBoxWidth) {
                productsScrolled = -productsScrollBoxWidth;
            }
        }
        productsScrollBox.animate({
            scrollLeft: productsScrolled
        });
    }

    ////scroll articles box
    let articlesScrollBox = $('.articles-container-scroll');
    let articlesScrollBoxWidth = articlesScrollBox[0] ? articlesScrollBox[0].scrollWidth - articlesScrollBox.width() : 0;
    let articlesScrolled = 0;

    var articlesScrollBoxIsDown = false,
        articlesScrollBoxStartX,
        articlesScrollBoxScrollLeft;

    productsScrollBox.on("mousedown touchstart", function (e) {
        clearInterval(articlesScrollInterval);
        articlesScrollBoxIsDown = true;
        articlesScrollBoxStartX = (e.pageX || e.originalEvent.touches[0].pageX) - productsScrollBox.offset().left;
        articlesScrollBoxScrollLeft = productsScrollBox.scrollLeft();
        productsScrollBox.css("cursor", "grabbing");
    });

    $(window).on("mouseup touchend", function () {
        articlesScrollBoxIsDown = false;
        productsScrollBox.css("cursor", "grab");
        // articlesScrollInterval = setInterval(articlesInterval, 5000);
    });

    productsScrollBox.on("mousemove touchmove", function (e) {
        if (!articlesScrollBoxIsDown) return;
        e.preventDefault();
        var x = (e.pageX || e.originalEvent.touches[0].pageX) - productsScrollBox.offset().left;
        var walk = (x - articlesScrollBoxStartX) * 2;
        productsScrollBox.scrollLeft(articlesScrollBoxScrollLeft - walk);
    });


    let articlesScrollInterval = setInterval(articlesInterval, 3000);

    function articlesInterval() {
        if (articlesScrolled == -articlesScrollBoxWidth) {
            articlesScrolled = 0;
        } else {
            articlesScrolled = articlesScrolled - 250;
            if (articlesScrolled < -articlesScrollBoxWidth) {
                articlesScrolled = -articlesScrollBoxWidth;
            }
        }
        articlesScrollBox.animate({
            scrollLeft: articlesScrolled
        });
    }

    ////prevent decimal number in count product
    $('#product_count').on('keyup', function () {
        let value = Math.floor($(this).val());
        value = value >= 1 ? value : 1;
        $(this).val(value);
    });
    ////calc total price product page
    function calcTotalPriceProduct() {
        let omdePrices = $('#omde_prices').val();
        let price = $('#product_price').data('price');
        let mainPrice = $('#product_main_price').data('main_price');
        let weight = $('#product_weight').val();
        weight = weight ? Number(weight) : 1;
        let count = $('#product_count').val();
        if (omdePrices) {
            omdePrices = JSON.parse(omdePrices);
            let perWeight = 0;
            for (let omdeWeight in omdePrices) {
                omdeWeight = Number(omdeWeight);
                if (weight >= omdeWeight) {
                    perWeight = omdeWeight;
                }
            }
            if (omdePrices[perWeight]) {
                price = omdePrices[perWeight];
                $('.omde-list-style').removeClass('bg-success');
                $(`#omde_list_style_${perWeight}`).addClass('bg-success');
            } else {
                $('.omde-list-style').removeClass('bg-success');
                $('#omde_list_style_0').addClass('bg-success');
            }

        }

        price = price * weight * count;
        mainPrice = mainPrice * weight * count;
        let totalDiscount = mainPrice - price;
        price = Math.round(price).toLocaleString();
        mainPrice = Math.round(mainPrice).toLocaleString();
        totalDiscount = Math.round(totalDiscount).toLocaleString();
        $('#product_price').text(price + ' تومان');
        $('#product_main_price').text(mainPrice + ' تومان');
        $('#total_discount').text(totalDiscount + ' تومان');
    }
    calcTotalPriceProduct();
    $('#increase_product_count_btn').on('click', function () {
        let countElem = $('#product_count');
        let count = countElem.val();
        count++;
        countElem.val(count);
        calcTotalPriceProduct();
    });
    $('#decrease_product_count_btn').on('click', function () {
        let countElem = $('#product_count');
        let count = countElem.val();
        if (count > 1) {
            count--;
            countElem.val(count);
        }
        calcTotalPriceProduct();
    });
    $('#product_count').on('change', function () {
        calcTotalPriceProduct();
    });
    $('#product_weight').on('input', function () {
        calcTotalPriceProduct();
    });
    ////liekes star
    $('.likes-star-btn').on('click', function () {
        $(this).prevAll('.likes-star-btn').addBack().find('.star').addClass('active');
        $(this).nextAll('.likes-star-btn').find('.star').removeClass('active');
        $('#like').val($(this).val());

    });
    ////share product link
    $('.share-btn').on('click', function () {
        let link = window.location;
        navigator.clipboard.writeText(link);
        $('.alert-success').text('لینک محصول کپی شد').show();
        setTimeout(function () {
            $('.alert-success').hide();
        }, 3000);
    });
    ////validate send comment
    $('.add-comment-btn').on('click', function () {
        let isLogin = $(this).val();
        if (isLogin) {
            $('.add-comment-box').show();
            $(this).hide();
        } else {
            $('.alert-danger').text('برای ثبت نظر ابتدا باید ورود کنید').show();
            setTimeout(function () {
                $('.alert-danger').hide();
            }, 5000);
        }
    });
    $('#send_comment_btn').on('click', function (e) {
        e.preventDefault();
        let like = $('#like').val();
        if (like == 0) {
            $('.alert-danger').text('لطفا به محصول امتیاز دهید').show();
            setTimeout(function () {
                $('.alert-danger').hide();
            }, 5000);
            return;
        }
        $('#comment_form').submit();
    });
    ////expire time verify sms
    function setExpireTime() {
        let time = 120; let minit = 0;
        let expireTimeElem = $('#expire_time');
        let second = 0;
        let minitText = '00';
        let secondText = '00';
        let expireTimeInterval = setInterval(function () {
            --time;
            if (time >= 0) {
                minit = Math.trunc(time / 60);
                second = time % 60;
                minitText = String(minit);
                secondText = String(second);
                if (minit < 10) {
                    minitText = `0${minit}`;
                }
                if (second < 10) {
                    secondText = `0${second}`;
                }
                expireTimeElem.text(`${minitText}:${secondText}`);
            } else {
                clearInterval(expireTimeInterval);
                $('#again_mobile').val($('#first_mobile').val());
                $('#again_form').show();
            }

        }, 1000);
    }
    let verifySmsBox = $('#vreify_sms_box:not(.d-none)');
    if (verifySmsBox.length) {
        setExpireTime();
    }
    ////calc total price shoping cart page
    let minPriceFreePostal = Number($('#post_price').data('min_price_free_postal'));
    let selfCity = $('#post_price').data('self_city');

    async function calcTotalPriceCart() {
        let finalPriceProducts = 0;
        let finalDiscountProducts = 0;
        let finalPrice = 0;
        let isOmde = false;

        $('.cart-product-count').each(function (i, obj) {

            let productId = $(obj).data('product_id');
            let productType = $(obj).data('product_type');
            let omdePrices = $(`#omde_prices_${productId}`).val();
            let weightElem = $('#cart_product_weight_' + productId);
            let productWeight = weightElem.val();
            let countElem = $('#cart_product_count_' + productId);
            let productCount = countElem.val();

            if (productWeight && (isNaN(productWeight) || productWeight <= 0)) {
                weightElem.val(1);
                productWeight = 1;
                $('.alert-danger').text('گزینه وزن را به درستی وارد نمایید.').show();
                setTimeout(function () {
                    $('.alert-danger').hide();
                }, 5000);

            }
            if (productCount && (isNaN(productCount) || productCount <= 0)) {
                countElem.val(1);
                productCount = 1;
                $('.alert-danger').text('گزینه تعداد را به درستی وارد نمایید.').show();
                setTimeout(function () {
                    $('.alert-danger').hide();
                }, 5000);

            }
            productWeight = productWeight ? Number(productWeight) : 1;
            let productPrice = $('#cart_product_price_' + productId).data('price');
            let productMainPrice = $('#cart_product_main_price_' + productId).data('price');

            if (productType == 'omde') {
                isOmde = true;
                if (omdePrices) {
                    omdePrices = JSON.parse(omdePrices);
                    let perWeight = 0;
                    for (let omdeWeight in omdePrices) {
                        omdeWeight = Number(omdeWeight);

                        if (productWeight >= omdeWeight) {
                            perWeight = omdeWeight;
                        }
                    }
                    if (omdePrices[perWeight]) {
                        productPrice = omdePrices[perWeight];

                        $('.omde-list-style').removeClass('bg-success');
                        $(`#omde_list_style_${perWeight}`).addClass('bg-success');
                    } else {
                        $('.omde-list-style').removeClass('bg-success');
                        $('#omde_list_style_0').addClass('bg-success');
                    }

                }
            }
            let price = productWeight * productPrice;
            let mainPrice = productWeight * productMainPrice;
            let totalMainPrice = productWeight * productMainPrice * productCount;
            let totalPriceProduct = productCount * productWeight * productPrice;
            let totalDiscountProduct = productCount * (mainPrice - price);
            totalDiscountProduct = Math.round(totalDiscountProduct);
            if (totalDiscountProduct) {
                finalDiscountProducts += totalDiscountProduct;
            }
            price = Math.round(price).toLocaleString();
            mainPrice = Math.round(mainPrice).toLocaleString();
            totalPriceProduct = Math.round(totalPriceProduct);
            finalPriceProducts += totalPriceProduct;

            totalMainPrice = Math.round(totalMainPrice);
            $('#total_price_product_' + productId).data('total_price_product', totalPriceProduct).text(totalPriceProduct.toLocaleString() + ' تومان');
            $('#total_discount_product_' + productId).data('total_discount_product_', totalDiscountProduct).text(totalDiscountProduct.toLocaleString() + ' تومان');
            $('#cart_product_main_price_' + productId).text(totalMainPrice.toLocaleString() + ' تومان');
        });

        let postPrice = calcPostalPrice();
        if (!isOmde) {
            if (minPriceFreePostal > 10 && finalPriceProducts > minPriceFreePostal) {
                postPrice = 0;
            }
        }

        finalPrice = finalPriceProducts + postPrice;
        finalPrice = Math.round(finalPrice).toLocaleString();
        postPrice = Math.round(postPrice).toLocaleString();
        $('#post_price').text(postPrice + ' تومان');
        $('.cart-final-price').text(finalPrice + ' تومان');

        if (finalDiscountProducts > 0) {
            finalDiscountProducts = Math.round(finalDiscountProducts).toLocaleString();
            $('#cart_discount_products').text(finalDiscountProducts + ' تومان').parent().show();
        } else {
            $('#cart_discount_products').parent().hide();
        }

    }

    function calcPostalPrice() {
        let sendWay = $('#send_way').val();
        if (sendWay == 'barbary' || sendWay == 'bus' || sendWay == 'tipax') {
            return 0;
        }
        let productCountInputs = $('.cart-product-count');
        let postPriceElem = $('#post_price');
        let basePostPrice = Number(postPriceElem.data('base_post_price'));
        let manyWeightPrice = Number(postPriceElem.data('many_weight_price'));
        let finalWeightInG = 0;
        productCountInputs.each(function () {
            let pId = $(this).data('product_id');
            let productCount = Number($(this).val());
            let productWeight = Number($('#cart_product_weight_' + pId).val());
            let productUnit = $(this).data('product_unit');

            if (productUnit == 'kg') {
                finalWeightInG += productCount * productWeight * 1000;
            } else if (productUnit == 'g') {
                finalWeightInG += productCount * productWeight;
            } else {
                let amountUnit = productUnit.split('_');
                let amountWeight = Number(amountUnit[0]);
                let unitWeight = amountUnit[1];
                if (unitWeight == 'kg') {
                    finalWeightInG += productCount * amountWeight * 1000;
                } else if (unitWeight == 'g') {
                    finalWeightInG += productCount * amountWeight;
                }
            }

        });
        finalWeightInG = Math.ceil(finalWeightInG / 1000);
        if (finalWeightInG > 1) {
            basePostPrice += (finalWeightInG - 1) * manyWeightPrice;
        }

        if (selfCity || selfCity === 0) {
            basePostPrice = selfCity;
        }

        return Math.round(basePostPrice);
    }
    calcTotalPriceCart();

    $('.cart-product-count').on('change', function () {
        calcTotalPriceCart();
    });

    $('#send_way').on('change', function () {
        calcTotalPriceCart();
    });
    $('.increase-product-count-cart').on('click', function () {
        let productId = $(this).data('product_id');
        let countElem = $('#cart_product_count_' + productId);
        let count = countElem.val();
        count++;
        countElem.val(count);
        calcTotalPriceCart();
    });
    $('.decrease-product-count-cart').on('click', function () {
        let productId = $(this).data('product_id');
        let countElem = $('#cart_product_count_' + productId);
        let count = countElem.val();
        if (count > 1) {
            count--;
            countElem.val(count);
            calcTotalPriceCart();
        }
    });
    $('.cart-product-weight').on('input', function () {
        calcTotalPriceCart();
    });
    $('.gateway-pay').on('click', function () {
        $('.gateway-pay').parent().removeClass('border border-success');
        $(this).parent().addClass('border border-success');
    });
    ////generate select cities and proviences
    $('#province').on('change', function () {
        let provinceId = $(this).val();
        $('#city').children().remove();
        $('.city-province-' + provinceId).clone().appendTo('#city');
        $('#city').val('');
    });
    ////display attachment file name tiket
    $('#tiket_attachment_input').on('change', function () {
        let fileName = $(this)[0].files[0].name;
        $('#tiket_attachment_name').text(fileName);
    });
    //// articles search form submit
    $('#article_category_select').on('change', function () {
        $('#articles_search_form').submit();
    });
    $('#article_orderby_select').on('change', function () {
        $('#articles_search_form').submit();
    });

    ////nuts page
    $('.nuts-weight-input').on('input', function () {
        calcAllNutsSelected();
    });

    $('.nuts-unit-input').on('change', function () {
        calcAllNutsSelected();
    });

    let nutsTotalWeightInG = 0;

    function calcAllNutsSelected() {
        let nutsWeightInputs = $('.nuts-weight-input');
        let totalWeightInG = 0;
        let totalPriceProducts = 0;
        let finalPrice = 0;
        let allAmounts = [];
        let basePostalPrice = Number($('#base_postal_price').val());
        let manyWeightPrice = Number($('#many_weight_price').val());
        let minPriceFreePostal = Number($('#min_price_free_postal').val());
        let selfCity = $('#self_city').val();

        nutsWeightInputs.each(function (index, input) {
            let elem = $(input);
            let amount = elem.val();

            if (amount && isNaN(amount)) {
                $('.alert-danger').text('مقدار وارد شده باید عدد باشد.').show();
                setTimeout(function () {
                    $('.alert-danger').hide();
                }, 5000);
                amount = 0;
                elem.val(0);
            }

            amount = Number(amount);
            let id = elem.data('c_id');
            let name = elem.data('c_name');
            let mainPrice = Number(elem.data('c_price'));
            let mainUnit = elem.data('c_main_unit');
            let price = 0;

            let selectedUnit = $(`#nuts_unit_input_${id}`).val();
            let selectedUnitFa = selectedUnit == 'g' ? 'گرم' : 'کیلوگرم';

            if (mainUnit === selectedUnit) {
                price = amount * mainPrice;
            } else if (mainUnit === 'kg' && selectedUnit === 'g') {
                price = amount * mainPrice / 1000;
            } else if (mainUnit === 'g' && selectedUnit === 'kg') {
                price = amount * mainPrice * 1000;
            }

            totalPriceProducts += price;

            if (selectedUnit === 'g') {
                totalWeightInG += amount;
            } else {
                totalWeightInG += amount * 1000;
            }



            allAmounts.push({
                'id': id,
                'selectedUnit': selectedUnit,
                'amount': amount
            });

            if (!amount) {
                $(`#li_nut_${id}`).remove();

            } else if ($(`#li_nut_${id}`).length) {

                $(`#li_nut_amount_${id}`).text(`${amount.toLocaleString()} ${selectedUnitFa}`);
                $(`#li_nut_price_${id}`).text(`${price.toLocaleString()} تومان`);

            } else {
                let newList = `
                    <li id="li_nut_${id}">
                        <span class="custom-list-style"></span>
                        <span id="li_nut_name_${id}" class="ms-1">${name}</span>
                        <span class="ms-2">(<span id="li_nut_amount_${id}">${amount} ${selectedUnitFa}</span> - <span id="li_nut_percent_${id}"></span>
                            )</span>
                        <span id="li_nut_price_${id}">${price.toLocaleString()} تومان</span>
                    </li>
                `;
                $('.user-combinashions-list').append(newList);
            }

        });

        allAmounts.forEach(function (item) {

            let percent = 0;

            if (item.selectedUnit === 'g') {
                percent = item.amount / totalWeightInG * 100;
            } else {
                percent = item.amount * 1000 / totalWeightInG * 100;
            }

            $(`#li_nut_percent_${item.id}`).text(`${Math.round(percent)}%`);


        });
        nutsTotalWeightInG = totalWeightInG;
        let postPrice = calcPostalPriceNuts(totalWeightInG, basePostalPrice, manyWeightPrice, minPriceFreePostal, selfCity, totalPriceProducts);
        finalPrice = Math.round(postPrice + totalPriceProducts);
        $('#final_price_products').text(`${totalPriceProducts.toLocaleString()} تومان`);
        $('#final_post_price').text(`${postPrice.toLocaleString()} تومان`);
        $('#final_price').text(`${finalPrice.toLocaleString()} تومان`);

        return { 'totalWeightInG': totalWeightInG, 'totalPriceProducts': totalPriceProducts };

    }

    calcAllNutsSelected();

    function calcPostalPriceNuts(weightInG, basePostalPrice, manyWeightPrice, minPriceFreePostal, selfCity, finalPrice) {

        if (weightInG == 0 || !basePostalPrice) {
            return 0;
        }
        let weightInKg = Math.ceil(weightInG / 1000);
        let postPrice = basePostalPrice;
        if (weightInKg > 1) {
            postPrice += (weightInKg - 1) * manyWeightPrice;
        }

        if (selfCity) {
            postPrice = selfCity;
        }

        if (minPriceFreePostal > 10 && finalPrice > minPriceFreePostal) {
            postPrice = 0;
        }

        return Math.round(postPrice);
    }

    $('#nuts_pay_btn').on('click', function (e) {
        e.preventDefault();
        let isLogin = $('#base_postal_price').val() == undefined ? false : true;
        if (isLogin && nutsTotalWeightInG > 0) {
            $('#nuts_pay_form').submit();
        } else if (isLogin && nutsTotalWeightInG == 0) {
            $('.alert-danger').text('هیچ محصولی انتخاب نشده است.').show();
            setTimeout(function () {
                $('.alert-danger').hide();
            }, 5000);
        } else {
            $('.alert-danger').text('جهت ثبت سفارش ابتدا باید ورود کنید.').show();
            setTimeout(function () {
                $('.alert-danger').hide();
            }, 5000);
        }

    });


});
