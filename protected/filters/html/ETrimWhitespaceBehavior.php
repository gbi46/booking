<?php
/**
 * 
 * Filter to remove extra white space within the text.
 * 
 * @package Savant3
 * 
 * @author Monte Ohrt <monte@ispi.net>
 * 
 * @author Contributions from Lars Noschinski <lars@usenet.noschinski.de>
 * 
 * @author Converted to a Savant3 filter by Paul M. Jones <pmjones@ciaweb.net>
 *
 * @author Converted to a Yii Behavior by Stefan Volkmar <volkmar_yii@email.de>
 * 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 *
 */

/**
 * 
 * Filter to remove extra white space within the text.
 * 
 * @package Savant3
 * 
 * @author Monte Ohrt <monte@ispi.net>
 * 
 * @author Contributions from Lars Noschinski <lars@usenet.noschinski.de>
 * 
 * @author Converted to a Savant3 filter by Paul M. Jones <pmjones@ciaweb.net>
 * 
 */
class ETrimWhitespaceBehavior extends CBehavior
{

    /**
     * 
     * Removes extra white space within the text.
     * 
     * Trim leading white space and blank lines from template source
     * after it gets interpreted, cleaning up code and saving bandwidth.
     * Does not affect <pre></pre>, <script></script>, or
     * <textarea></textarea> blocks.
     *
     * @access public
     * 
     * @param string $buffer The source text to be filtered.
     * 
     * @return string The filtered text.
     * 
     */
    public function compressHtml($buffer)
    {
        // Pull out the script blocks
        /* preg_match_all("!<script[^>]+>.*?</script>!is", $buffer, $match);
          $script_blocks = $match[0];
          $buffer = preg_replace(
          "!<script[^>]+>.*?</script>!is",
          '@@@SAVANT:TRIM:SCRIPT@@@',
          $buffer
          ); */

        //**************** Pull out the JS comment blocks **********////
        $pattern = '/(\s+)\/\*([^\/]*)\*\/(\s+)/s';
        preg_match_all($pattern, $buffer, $match);
        $buffer = preg_replace(
            $pattern, '', $buffer
        );



        //**************** Pull out the HTML comment blocks **********////
        $pattern = "/<!--(.*)-->/Uis";
        preg_match_all($pattern, $buffer, $match);
        $buffer = preg_replace(
            $pattern, '', $buffer
        );

        // Pull out the pre blocks
        /* preg_match_all("!<pre[^>]*>.*?</pre>!is", $buffer, $match);
          $pre_blocks = $match[0];
          $buffer = preg_replace(
          "!<pre[^>]*>.*?</pre>!is",
          '@@@SAVANT:TRIM:PRE@@@',
          $buffer
          );

          // Pull out the textarea blocks
          preg_match_all("!<textarea[^>]+>.*?</textarea>!is", $buffer, $match);
          $textarea_blocks = $match[0];
          $buffer = preg_replace(
          "!<textarea[^>]+>.*?</textarea>!is",
          '@@@SAVANT:TRIM:TEXTAREA@@@',
          $buffer
          ); */

        // remove all leading spaces, tabs and carriage returns NOT
        // preceeded by a php close tag.
        $buffer = trim(preg_replace('/((?<!\?>)\n)[\s]+/m', '\1', $buffer));

        // replace script blocks
        /* self::replace(
          "@@@SAVANT:TRIM:SCRIPT@@@",
          $script_blocks,
          $buffer
          );

          // replace pre blocks
          self::replace(
          "@@@SAVANT:TRIM:PRE@@@",
          $pre_blocks,
          $buffer
          );

          // replace textarea blocks
          self::replace(
          "@@@SAVANT:TRIM:TEXTAREA@@@",
          $textarea_blocks,
          $buffer
          ); */

        return $buffer;
    }

    /**
     * 
     * Does a simple search-and-replace on the source text.
     * 
     * @access protected
     * 
     * @param string $search The string to search for.
     * 
     * @param string $replace Replace with this text.
     * 
     * @param string &$buffer The source text.
     * 
     * @return string The text after search-and-replace.
     * 
     */
    protected static function replace($search, $replace, &$buffer)
    {
        $len = strlen($search);
        $pos = 0;
        $count = count($replace);

        for ($i = 0; $i < $count; $i++) {
            // does the search-string exist in the buffer?
            $pos = strpos($buffer, $search, $pos);
            if ($pos !== false) {
                // replace the search-string
                $buffer = substr_replace($buffer, $replace[$i], $pos, $len);
            } else {
                break;
            }
        }
    }
}

?>