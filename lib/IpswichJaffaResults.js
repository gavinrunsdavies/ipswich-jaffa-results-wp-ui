let ipswichjaffarc = {

    formatTime: function (time) {
        if (time.startsWith("00:")) {
            time = time.substring(3);
        }

        if (time.startsWith("0")) {
            time = time.substring(1);
        }

        return time;
    },

    formatDate: function (date) {
        return (new Date(date)).toDateString();
    },

    secondsToTime: function (totalSeconds) {
        let hours = Math.floor(totalSeconds / 3600);
        // Workaround for decimal places in totalSeconds %= 3600;
        totalSeconds = (totalSeconds * 1000 % 3600000)/1000;
        let minutes = Math.floor(totalSeconds / 60);        

        let secondsWithMs = (totalSeconds % 60).toFixed(2);

        let [seconds, milliseconds] = secondsWithMs.split(".");

        let time;

        if (hours > 0) {
            time = hours + ":";
            time += String(minutes).padStart(2, "0") + ":";
            time += String(seconds).padStart(2, "0");
        } else if (minutes > 0) {
            time = minutes + ":";
            time += String(seconds).padStart(2, "0");
        } else {
            time = seconds;
        }

        time += milliseconds

        return time;
    }
};
