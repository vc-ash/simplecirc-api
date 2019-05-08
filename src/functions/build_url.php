<?php
if(!function_exists('build_url')){
    /**
     *  Cleanly build a url from an existing url.
     *  
     *  @param string $url The url to build on.
     *  @param array $parts (optional) The array of optional parameter parts to build onto the url.
     *      Example with all parts: $parts = [
     *          (string) 'scheme' => 'http', 
     *          (string) 'host' => 'example.com', 
     *          (string) 'port' => '80', 
     *          (string) 'user' => 'someuser', 
     *          (string) 'pass' => 'someuser$pa55', 
     *          (string) 'path' => 'path/on/the/new/url', 
     *          (string|array) 'query' => ['param' => 'value', 'param2' => 'value2'] || 'param=value&param2=value2', 
     *          (string) 'fragment' => '#somefrag'
     *      ];
     *  @param bool $append_query (optional) Default: false. Append the new querystring to the existing querystring.
     *  @return string Returns the newly constructed url.
     *  
     *  @details This helper function helps create a new cleanly formatted url from an existing url based on new parameter parts. With the option to append the new querystring to the existing one.
     *  
     *  @author Ash Bulcroft <ashbulcroft@gmail.com>
     */
    function build_url($url, array $parts=array(), $append_query=false){
        $parsedUrl = parse_url($url);
        if(empty($parsedUrl['scheme'])){
            $url = ltrim($url, '/');
            $parsedUrl = parse_url('http://'.$url);
        }
        
        // If the new querystring is available do some manipulations
        if(!empty($parts['query'])){
            
            // Clean (remove: ?& from beginning) if new querystring is a string
            if(!is_array($parts['query'])){
                $parts['query'] = ltrim ($parts['query'], '?&');
            }
            
            // Appended to the existing querystring if the append_query flag is true
            if($append_query === true){
                
                // Parse the existing query string into an array
                if(!empty($parsedUrl['query'])){
                    parse_str($parsedUrl['query'], $parsedUrl['query']);
                }
                
                // Parse the new querystring into an array if it's a string
                if(!is_array($parts['query'])){
                    parse_str($parts['query'], $parts['query']);
                }
                
                // Merge the two querystring arrays if they are both set. 
                // New querystring overwrites intersecting keys of existing querystring
                if(isset($parsedUrl['query'], $parts['query'])){
                    $parts['query'] = array_merge($parsedUrl['query'], $parts['query']);
                }
                
                // Build the new querystring
                $parts['query'] = http_build_query($parts['query']);
            }
            
            // Convert the new querystring to a string if it's an array
            if(is_array($parts['query'])){
                $parts['query'] = http_build_query($parts['query']);
            }
        }
        
        // New parts take priority over old parts.
        $newUrl['scheme'] = (!empty($parts['scheme']) ? $parts['scheme'].'://' : (!empty($parsedUrl['scheme']) ? $parsedUrl['scheme'].'://' : ''));
        $newUrl['host'] = (!empty($parts['host']) ? $parts['host'] : (!empty($parsedUrl['host']) ? $parsedUrl['host'] : ''));
        $newUrl['port'] = (!empty($parts['port']) ? ':'.$parts['port'] : (!empty($parsedUrl['port']) ? ':'.$parsedUrl['port'] : ''));
        $newUrl['user'] = (!empty($parts['user']) ? $parts['user'] : (!empty($parsedUrl['user']) ? $parsedUrl['user'] : ''));
        $newUrl['pass'] = (!empty($parts['pass']) ? $parts['pass'] : (!empty($parsedUrl['pass']) ? $parsedUrl['pass'] : ''));
        $newUrl['path'] = (!empty($parts['path']) ? $parts['path'] : (!empty($parsedUrl['path']) ? $parsedUrl['path'] : ''));
        $newUrl['path'] = (!empty($newUrl['path']) ? '/'.ltrim($newUrl['path'], '/') : '');
        $newUrl['query'] = (!empty($parts['query']) ? '?'.$parts['query'] : (!empty($parsedUrl['query']) ? '?'.$parsedUrl['query'] : ''));
        $newUrl['fragment'] = (!empty($parts['fragment']) ? '#'.ltrim($parts['fragment'], '#') : (!empty($parsedUrl['fragment']) ? '#'.$parsedUrl['fragment'] : ''));
        
        // String together the username and password parameters
        $newUrl['user_and_pass'] = $newUrl['user'] . (!empty($newUrl['pass']) ? ':'.$newUrl['pass'].'@' : '');
        
        
        // Return the newly built url
        return $newUrl['scheme'] . $newUrl['user_and_pass'] . $newUrl['host'] . $newUrl['port'] . $newUrl['path'] . $newUrl['query'] . $newUrl['fragment'];
    }
}
