function mainfunction() {
    var link = $('#link').val();
    var typez = $('#typez').val();
    var batch = $('#batch').val();
    if (link == "") {
        if (batch == "-1") {
            alert("Please paste a link");
        }
    } else if (batch == "") {
        if (link == "-1") {
            alert("Please paste a link");
        }
    } else {
        if (typez == "1") {
            $.post("download.php",
                {
                    submit: "submit",
                    typez: "1",
                    link: link
                },
                function (data) {
                    if (data.indexOf(".mp4") + 1)
                        window.location.href = data;
                    else {
                        $("#imagesection").empty();
                        $("#imagesection").append(data);
                    }
                });
        } else if (typez == "2") {
            $.post("download.php",
                {
                    submit: "submit",
                    typez: "2",
                    batch: batch
                },
                function (data) {
                    var currenturl = window.location.href;
                    currenturl = currenturl.replace("batchdownload.html", "");
                    window.location = currenturl + data;
                });


        }
    }

};