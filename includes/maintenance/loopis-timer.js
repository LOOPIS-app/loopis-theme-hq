/**
 * Simple countdown timer for LOOPIS maintenance page.
 * Takes a Unix timestamp (seconds since epoch) and counts down to that time.
 */
var startCountdown = function(readyTimestamp) {
    var update = function() {
        var now = Math.floor(Date.now() / 1000);
        var diff = Math.max(0, readyTimestamp - now);

        var days = Math.floor(diff / 86400);
        diff -= days * 86400;

        var hours = Math.floor(diff / 3600);
        diff -= hours * 3600;

        var minutes = Math.floor(diff / 60);
        var seconds = diff % 60;

        fillTimerValue('timerResultDays', days);
        fillTimerValue('timerResultHours', hours);
        fillTimerValue('timerResultMinutes', minutes);
        fillTimerValue('timerResultSeconds', seconds);

        if (readyTimestamp > now) {
            setTimeout(update, 1000);
        }
    };

    update();
};

var fillTimerValue = function(elementId, value) {
    var element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = (value < 10) ? '0' + value : value;
    }
};
