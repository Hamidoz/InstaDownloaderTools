<?php
if (isset($_POST['submit'])) {

    if (isset($_POST['typez'])) {
        if ($_POST['typez'] == 1) {
            if ($_POST['link'] != "" || $_POST['link'] != null) {

                $target = $_POST['link'];
                if (filter_var($target, FILTER_VALIDATE_URL)) {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
                    curl_setopt($ch, CURLOPT_POST, FALSE);
                    curl_setopt($ch, CURLOPT_URL, $target);       // Target site
                    curl_setopt($ch, CURLOPT_REFERER, '');            // Referer value
                    curl_setopt($ch, CURLOPT_VERBOSE, FALSE);         // Minimize logs
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);         // No certificate
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);          // Follow redirects
                    curl_setopt($ch, CURLOPT_MAXREDIRS, 4);             // Limit redirections to four
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);          // Return in string
                    $return_array = curl_exec($ch);
                    curl_close($ch);
                    $file = ($return_array);
                    preg_match('/"is_video": (.*), "/', $file, $goto_url);
                    $arr = explode(',', trim($goto_url[1]));
                    if ($arr[0] == "false") {
                        preg_match('/display_url": "(.*)", "is_video"/', $file, $goto_url);
                        echo '<center><img src="' . $goto_url[1] . '" width="50%"><br> Right Click on image and save it <center>';
                    } elseif ($arr[0] == "true") {
                        preg_match('/video_url": "(.*)", "video_view_count/', $file, $goto_url);
                        echo $goto_url[1];
                    }
                } else {
                    echo "Please provide a valid url";
                }


            }
        } else if ($_POST['typez'] == 2) {

            if ($_POST['batch'] != "" || $_POST['batch'] != null) {

                # create new zip object
                $zip = new ZipArchive();
                $zip_name = "tempimages/" . time() . ".zip"; // Zip name
                $zip->open($zip_name, ZipArchive::CREATE);

                foreach (preg_split("/((\r?\n)|(\r\n?))/", $_POST['batch']) as $line) {

                    if (filter_var($line, FILTER_VALIDATE_URL)) {
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
                        curl_setopt($ch, CURLOPT_POST, FALSE);
                        curl_setopt($ch, CURLOPT_URL, $line);       // Target site
                        curl_setopt($ch, CURLOPT_REFERER, '');            // Referer value
                        curl_setopt($ch, CURLOPT_VERBOSE, FALSE);         // Minimize logs
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);         // No certificate
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);          // Follow redirects
                        curl_setopt($ch, CURLOPT_MAXREDIRS, 4);             // Limit redirections to four
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);          // Return in string
                        $return_array = curl_exec($ch);
                        curl_close($ch);
                        $file = ($return_array);
                        if (!preg_match('/"is_video": (.*), "/', $file, $goto_url)) {
                        } else {
                            preg_match('/"is_video": (.*), "/', $file, $goto_url);
                            $arr = explode(',', trim($goto_url[1]));
                            if ($arr[0] == "false") {
                                preg_match('/display_url": "(.*)", "is_video"/', $file, $goto_url);
                                $zip->addFromString(basename($goto_url[1]), file_get_contents($goto_url[1]));
                            } elseif ($arr[0] == "true") {
                                preg_match('/video_url": "(.*)", "video_view_count/', $file, $goto_url);
                                $zip->addFromString(basename($goto_url[1]), file_get_contents($goto_url[1]));
                            }
                        }


                    } else {
                    }
                }
                # close zip
                $zip->close();
                echo $zip_name;

            }
        }
    }

} else {
    header("Location: index.html");
    die();
}



