<?php
if (!function_exists('url_parser')) {
    // Correct url
    function url_parser(string $url): string
    {

        // multiple /// messes up parse_url, replace 2+ with 2
        $url = preg_replace('/(\/{2,})/', '//', $url);

        $parse_url = parse_url($url);

        if (empty($parse_url["scheme"])) {
            $parse_url["scheme"] = "https";
        }
        if (empty($parse_url["host"]) && !empty($parse_url["path"])) {
            // Strip slash from the beginning of path
            $parse_url["host"] = ltrim($parse_url["path"], '\/');
            $parse_url["path"] = "";
        }

        $return_url = "";

        // Check if scheme is correct
        if (!in_array($parse_url["scheme"], array("http", "https", "gopher"))) {
            $return_url .= 'https' . '://';
        } else {
            $return_url .= $parse_url["scheme"] . '://';
        }

        // Check if the right amount of "www" is set.
        $explode_host = explode(".", $parse_url["host"]);

        // Remove empty entries
        $explode_host = array_filter($explode_host);
        // And reassign indexes
        $explode_host = array_values($explode_host);

        // Contains subdomain
        if (count($explode_host) > 2) {
            // Check if subdomain only contains the letter w(then not any other subdomain).
            if (substr_count($explode_host[0], 'w') == strlen($explode_host[0])) {
                // Replace with "www" to avoid "ww" or "wwww", etc.
                $explode_host[0] = "www";

            }
        }
        $return_url .= implode(".", $explode_host);

        if (!empty($parse_url["port"])) {
            $return_url .= ":" . $parse_url["port"];
        }
        if (!empty($parse_url["path"])) {
            $return_url .= $parse_url["path"];
        }
        if (!empty($parse_url["query"])) {
            $return_url .= '?' . $parse_url["query"];
        }
        if (!empty($parse_url["fragment"])) {
            $return_url .= '#' . $parse_url["fragment"];
        }


        return $return_url;
    }
}
