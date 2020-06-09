define(
    ['jquery', 'core/ajax', 'core/notification', 'core/str', 'core/templates', 'core/url'],
    function($, AJAX, NOTIFICATION, STR, TEMPLATES, URL) {
    return {
        interval: undefined,
        meetingcheck: function() {
            console.log('mod_msteams/main:meetingcheck()');
            if ($('form[action="modedit.php"] input[name="externalurl"]').val() == '') {
                $('#meetingapp').removeClass('hidden');
                this.interval = setInterval(this.getAnchors, 10);
            }
        },
        getAnchors: function() {
            var M = this;
            //console.log('mod_msteams/main:getAnchors()');
            var compare = "https://teams.microsoft.com/l/meetup-join/";
            $($('#meetingapp')[0].contentDocument).find('a').each(function() {
                var url = $(this).attr('href');
                if (url.substring(0, compare.length) == compare) {
                    $('form[action="modedit.php"] input[name="externalurl"]').val(url);
                    $('#meetingapp').addClass('hidden');
                    $('#meetingsuccess').removeClass('hidden');
                    clearInterval(M.interval);
                }
            });
        },
    };
});
