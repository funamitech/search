<?php
    class VideoSearch extends EngineRequest {
        protected $instance_url;
        public function get_request_url() {
            $this->instance_url = $this->opts->invidious_instance_for_video_results;
            $query = urlencode($this->query);
            return "$this->instance_url/api/v1/search?q=$query";
        }

        public function parse_results($response) {
            $results = array();
            $json_response = json_decode($response, true);

            foreach ($json_response as $response) {
                if ($response["type"] == "video") {
                    $title = $response["title"];
                    $url = "https://youtube.com/watch?v=" . $response["videoId"];
                    $uploader = $response["author"];
                    $views = $response["viewCount"];
                    $date = $response["publishedText"];
                    $thumbnail = $this->instance_url . "/vi/" . explode("/vi/" ,$response["videoThumbnails"][4]["url"])[1];

                    array_push($results,
                        array (
                            "title" => htmlspecialchars($title),
                            "url" =>  htmlspecialchars($url),
                            // base_url is to be removed in the future, see #47
                            "base_url" => htmlspecialchars(get_base_url($url)),
                            "uploader" => htmlspecialchars($uploader),
                            "views" => htmlspecialchars($views),
                            "date" => htmlspecialchars($date),
                            "thumbnail" => htmlspecialchars($thumbnail)
                        )
                    );
                }
            }

            return $results;
        }

        public static function print_results($results, $opts) {
            echo "<div class=\"text-result-container\">";

                foreach($results as $result) {
                    $title = $result["title"];
                    $url = $result["url"];
                    $url = check_for_privacy_frontend($url, $opts);
                    $base_url = get_base_url($url);
                    $uploader = $result["uploader"];
                    $views = $result["views"];
                    $date = $result["date"];
                    $thumbnail = $result["thumbnail"];

                    echo "<div class=\"text-result-wrapper\">";
                    echo "<a href=\"$url\">";
                    echo "$base_url";
                    echo "<h2>$title</h2>";
                    echo "<img class=\"video-img\" src=\"image_proxy.php?url=$thumbnail\">";
                    echo "<br>";
                    echo "<span>$uploader - $date - $views views</span>";
                    echo "</a>";
                    echo "</div>";
                }

            echo "</div>";
        }
    }
?>
