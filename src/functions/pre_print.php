<?php
/**
 *  Pre-format printing.
 *  
 *  @param mixed $input The input value that will be printed
 *  @param string $label (optional) A string label to be prepended to the formatted output. Use for clearity.
 *  @param string $css_class (optional) The css class to add to the <pre> tag for css selecting.
 *  @return void immediately echos/prints the return value.
 *  
 *  @details Display (print out) the input value enclosed in <pre> tags for easy reading.
 *  
 *  @author Ash Bulcroft <ashbulcroft@gmail.com>
 */
function pre_print($input, $label='', $css_class=''){
	$css_class = (!empty($css_class) ? $css_class : '');
	$backtrace = debug_backtrace();
	$caller = array_shift($backtrace);
    
    
    echo "<pre class=\"{$css_class}\">";
    echo "File: {$caller['file']}<br>";
    echo "Line: {$caller['line']}<br>";
    print $label;
    
	// If the input is an array or object use print_r to output data. 
    // Else use print to output data.
	if(preg_match('/^(array|object)$/',gettype($input))){
		print_r($input);
	}
	else{
		print $input ;
	}
    
    // End the output.
    echo "</pre>";
}