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
        totalSeconds %= 3600;
        let minutes = Math.floor(totalSeconds / 60);
        let seconds = Math.floor(totalSeconds % 60);

        let milliseconds = '';
        let parts = String(totalSeconds).split(".");       
        if (parts.length > 1) {
            milliseconds = "." + parts[1];
        }

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
