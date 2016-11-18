jQuery(function ($) {
    var jsonIfy = function (str) {
            try {
                return JSON.parse(str);
            } catch (e) {
                return false;
            }
        },
        listToJson = function (cv) {
            var returnArray = [];
            $('#cos-sortable > li').each(function () {
                returnArray.push({
                    title: $(this).html(),
                    value: $(this).attr('data-name')
                });
            });
            $('#custom-search-order').val(JSON.stringify(returnArray));
        },
        reorderListByJson = function () {
            var $customSearchOrder = $('#custom-search-order'),
                jsonObject = jsonIfy($customSearchOrder.val()),
                htmlClass = 'ui-state-default',
                html = '',
                $cosSortable = $('#cos-sortable');
            if (jsonObject && jsonObject.length > 0) {

                jsonObject.forEach(function (object) {
                    html += '<li class="' + htmlClass + '" data-name="' + object.value + '">' + object.title + '</li>';
                });
                $cosSortable.html(html);
            }
            $ul.css('visibility', 'visible');


        },
        $ul = $('#cos-form ul');
    $('#cos-sortable').sortable({
        stop: function () {
            listToJson();
        }
    });
    reorderListByJson();
    $ul.css('visibility', 'hidden');

    $('#cos-form').on('submit', function (e) {

        if (!$.trim($('#custom-search-order').val())) {
            e.preventDefault();
            e.stopPropagation();
            listToJson();
            setTimeout(function () {
                $(this).find('input[type="submit"]').click();
            }.bind(this), 10);

        }

    });
});