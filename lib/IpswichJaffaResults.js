var ipswichjaffarc = {

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
    }
};
