<?php
    class QwantImageSearch extends EngineRequest {
        public function get_request_url() {
            $page = $this->page / 10 + 1; // qwant has a different page system
            $query = urlencode($this->query);
            
            return "https://lite.qwant.com/?q=$query&t=images&p=$page";
        }

        public function get_results() {
            $results = array();
            $xpath = get_xpath(curl_multi_getcontent($this->ch));

            if (!$xpath)
                return $results;

            foreach($xpath->query("//a[@rel='noopener']") as $result)
            {       
                    $image = $xpath->evaluate(".//img", $result)[0];

                    if ($image)
                    {
                        $encoded_url = $result->getAttribute("href");
                        $encoded_url_split1 = explode("==/", $encoded_url)[1];
                        $encoded_url_split2 = explode("?position", $encoded_url_split1)[0];
                        $real_url = urldecode(base64_decode($encoded_url_split2));
                        $real_url = check_for_privacy_frontend($real_url);

                        $alt = $image->getAttribute("alt");
                        $thumbnail = urlencode($image->getAttribute("src"));

                        array_push($results, 
                            array (
                                "thumbnail" => urldecode(htmlspecialchars($thumbnail)),
                                "alt" => htmlspecialchars($alt),
                                "url" => htmlspecialchars($real_url)
                            )
                        );
        
                    }
            }

            return $results;
        }
        
        public static function print_results($results) {
            echo "<div class=\"image-result-container\">";

                foreach($results as $result)
                {
                    $thumbnail = urlencode($result["thumbnail"]);
                    $alt = $result["alt"];
                    $url = $result["url"];

                    echo "<a title=\"$alt\" href=\"$url\" target=\"_blank\">";
                    echo "<img src=\"image_proxy.php?url=$thumbnail\">";
                    echo "</a>";
                }

            echo "</div>";
        }
    }
?>
