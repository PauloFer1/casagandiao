<?php
/**



/**
 * Tests if a text starts with an given string.
 *
 * @param     string
 * @param     string
 * @return    bool
 */
function startsWith($haystack, $needle){
    return strpos($haystack, $needle) === 0;
}

/**
 * Tests whether a text ends with the given string or not.
 *
 * @param     string
 * @param     string
 * @return    bool
 */
function endsWith($haystack, $needle){
    return strrpos($haystack, $needle) === strlen($haystack)-strlen($needle);
}

/**
 * Tests whether a file is writable for anyone.
 *
 * @param string $file
 * @return boolean
 */
function isWritable($file=null) {
    if ($file === null)
        return false;

    if (!file_exists($file))
        return false;

    $perms = fileperms($file);

    if (is_writable($file) || ($perms & 0x0080) || ($perms & 0x0010) || ($perms & 0x0002))
        return true;
}

?>
