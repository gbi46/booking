jQuery(function ($) {
    $(document).ajaxSend(function () {
        $("#loading").show();
        /*$("#overlay-content").show();
         $("#loading-blocks").show();*/
    }).ajaxComplete(function () {
        $("#loading").hide();
        /*$("#overlay-content").hide();
         $("#loading-blocks").hide();*/
    });

    $('#search-form').on('change', '.searchField', function (event) {
        changeSearch();
    });

    $(".form-disable-button-after-submit").submit(function () {
        $(".submit-button").attr("disabled", true);
        $(".submit-button").val(LOADING_NAME);
        //$(".submit-button").before("<img src='" + INDICATOR + "' alt='Loading' border='0' style='text-decoration: none; vertical-align: middle; margin-right: 5px; height: 24px; width: 24px;' />");
        return true;
    });
});

function focusSubmit(elem) {
    elem.keypress(function (e) {
        if (e.which == 13) {
            $(this).blur();
            $("#btnleft").focus().click();
        }
    });
}

$(window).bind('popstate', function (event) {
    var state = event.originalEvent.state;
    if (state) {
        if (state.callFrom == 'reloadApartmentList') {
            //$('div.main-content-wrapper').html(state.response);
            window.location.href = state.path;
        }
    }
    /*else {
     window.location.reload();
     }*/
});

function reloadApartmentList(url) {
    $.ajax({
        type: 'GET',
        url: url,
        /*data: {is_ajax: 1},*/
        /*ajaxStart: UpdatingProcess(resultBlock, updateText),*/
        success: function (msg) {
            history.pushState({callFrom: 'reloadApartmentList', path: url, response: msg}, null, url);

            $('div.main-content-wrapper').html(msg);
            $('div.ratingview > span > input').rating({'readOnly': true});

            list.apply();

            // smooth scroll to
            var dest = 0;
            if ($("#appartment_box").offset().top > $(document).height() - $(window).height()) {
                dest = $(document).height() - $(window).height();
            } else {
                dest = $("#appartment_box").offset().top;
            }
            $("html,body").animate({scrollTop: dest}, 500, "swing");


            /*$('#update_div').remove();
             $('#update_text').remove();
             $('#update_img').remove();
             */
        }
    });
}

function UpdatingProcess(resultBlock, updateText) {
    $('#update_div').remove();
    $('#update_text').remove();
    $('#update_img').remove();

    var opacityBlock = $('#' + resultBlock);

    if (opacityBlock.width() != null) {
        var width = opacityBlock.width();
        var height = opacityBlock.height();
        var left_pos = opacityBlock.offset().left;
        var top_pos = opacityBlock.offset().top;
        $('body').append('<div id=\"update_div\"></div>');

        var cssValues = {
            'z-index': '1005',
            'position': 'absolute',
            'left': left_pos,
            'top': top_pos,
            'width': width,
            'height': height,
            'border': '0px solid #FFFFFF',
            'background-image': 'url(' + bg_img + ')'
        }

        $('#update_div').css(cssValues);

        var left_img = left_pos + width / 2 - 16;
        var left_text = left_pos + width / 2 + 24;
        var top_img = top_pos + height / 2 - 16;
        var top_text = top_img + 8;

        $('body').append("<img id='update_img' src='" + INDICATOR + "' style='position:absolute;z-index:1006; left: " + left_img + "px;top: " + top_img + "px;'>");
        $('body').append("<div id='update_text' style='position:absolute;z-index:6; left: " + left_text + "px;top: " + top_text + "px;'>" + updateText + "</div>");
    }
}

var searchLock = false;

function changeSearch() {
    if (params.change_search_ajax != 1) {
        return false;
    }

    if (!searchLock) {
        searchLock = true;

        $.ajax({
            url: CHANGE_SEARCH_URL,
            data: $('#search-form').serialize(),
            dataType: 'json',
            type: 'get',
            success: function (data) {
                $('#btnleft').html(data.string);
                searchLock = false;
            },
            error: function () {
                searchLock = false;
            }
        })
    }
}

var placemarksYmap = [];

var list = {
    lat: 0,
    lng: 0,
    ad_id: 0,

    apply: function () {
        $('div.appartment_item').each(function () {

            var existListMap = $('#list_map_block').attr('data-exist') == 1;
            if (!existListMap) {
                return;
            }

            var item = $(this);

            item.mouseover(function () {

                var ad = $(this);
                var lat = ad.attr('data-lat') + 0;
                var lng = ad.attr('data-lng') + 0;
                var id = ad.attr('data-ap-id');

                if ((list.lat != lat || list.lng != lng || list.ad_id != id) && parseFloat(lat) && parseFloat(lng)) {
                    list.lat = lat;
                    list.lng = lng;
                    list.ad_id = id;

                    if (useGoogleMap) {
                        if (typeof infoWindowsGMap !== 'undefined' && typeof infoWindowsGMap[id] !== 'undefined') {
                            for (var key in infoWindowsGMap) {
                                if (key == id) {
                                    infoWindowsGMap[key].open();
                                } else {
                                    infoWindowsGMap[key].close();
                                }
                            }
                            var latLng = new google.maps.LatLng(lat, lng);

                            mapGMap.setZoom(17);
                            mapGMap.panTo(latLng);
                            infoWindowsGMap[id].open(mapGMap, markersGMap[id]);
                        }
                    }

                    if (useYandexMap) {
                        if (typeof placemarksYMap !== 'undefined' && typeof placemarksYMap[id] !== 'undefined') {
                            if (typeof globalYMap !== 'undefined') {
                                globalYMap.panTo([parseFloat(lat), parseFloat(lng)], {'delay': 1000, 'duration': 1200, 'flying': false}).then(function () {
                                    globalYMap.setZoom(16);
                                    placemarksYMap[id].balloon.open();
                                });
                            }
                        }
                    }

                    if (useOSMap) {
                        if (typeof markersOSMap[id] !== 'undefined') {
                            mapOSMap.setZoom(16);
                            mapOSMap.panTo(new L.LatLng(lat, lng));
                            markersOSMap[id].openPopup();
                        }
                    }
                }

            });

        });
    }
}

var scriptLoaded = [];

function loadScript(url, reload) {
    reload = reload || true;

    //if(typeof scriptLoaded[url] == 'undefined' || reload){
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = url;
    document.body.appendChild(script);

    scriptLoaded[url] = 1;
    //}
}
function ajaxRequest(url, tableId) {
    $.ajax({
        url: url,
        type: "get",
        success: function () {
            $("#" + tableId).yiiGridView.update(tableId);
        }
    });
}

function addCSSRule(sheet, selector, rules) {
    //Backward searching of the selector matching cssRules
    var index = sheet.cssRules.length - 1;
    for (var i = index; i > 0; i--) {
        var current_style = sheet.cssRules[i];
        if (current_style.selectorText === selector) {
            //Append the new rules to the current content of the cssRule;
            rules = current_style.style.cssText + rules;
            sheet.deleteRule(i);
            index = i;
        }
    }
    if (sheet.insertRule) {
        sheet.insertRule(selector + "{" + rules + "}", index);
    } else {
        sheet.addRule(selector, rules, index);
    }
    return sheet.cssRules[index].cssText;
}