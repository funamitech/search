<?php
    class DefinitionRequest extends EngineRequest {

        public function get_request_url() {
            $split_query = explode(" ", $this->query);
            $reversed_split_q = array_reverse($split_query);
            $word_to_define = $reversed_split_q[1];
            return "https://api.dictionaryapi.dev/api/v2/entries/en/$word_to_define";
        }

        public function parse_results($response) {
            $json_response = json_decode($response, true);

            if (!array_key_exists("title", $json_response))
            {
                $definition = $json_response[0]["meanings"][0]["definitions"][0]["definition"];

                $source = "https://dictionaryapi.dev";
                return array(
                    "special_response" => array(
                        "response" => htmlspecialchars($definition),
                        "source" => $source
                    )
                );
            }

        }
    }
?>
