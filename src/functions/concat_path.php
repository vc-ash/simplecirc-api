<?php 
if(!function_exists('concat_path')){
    /**
     *  Concatenate a set of array values as a path glued with slashes (separator).
     *  
     *  @param array $path_bits The input array of path values to concatenate into a single path string.
     *  @param string|array $replace (optional) Sometimes a character, such as a backslash, needs to be replaced with the separator.
     *  @param string $separator (optional) The separator string that will glue the path together. Defaults to constant: DIRECTORY_SEPARATOR
     *  @return string The concatenated path.
     *  
     *  @details A helper function to create clean paths without extra slashes.
     *  
     *  @author Ash Bulcroft <ashbulcroft@gmail.com>
     */
    function concat_path(array $path_bits, $replace='', $separator=DIRECTORY_SEPARATOR){
        $new_path_bits = array();
        foreach($path_bits as $path_bit){
            // If replace is specified, then replace the set of provided strings with the separator
            if(!empty($replace)){
                $path_bit = str_replace($replace, $separator, $path_bit);
            }
            
            $path_bit = str_replace($separator.$separator, $separator, $path_bit); //make all double separators a single separator
            $path_bit = trim($path_bit, $separator); //remove the separator from either side of the path bit
            
            if(!empty($path_bit)){
                $new_path_bits[] = $path_bit;
            }
        }
        
        // Glue the path bits together with the separator and make sure the separator is not in the front
        $newPath = ltrim(implode($separator, $new_path_bits), $separator);
        
        return $newPath;
    }
}