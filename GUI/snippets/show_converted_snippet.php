<?PHP
/**
* GUI Template: converted code snippet
*
* @category   GUI
* @package    MySQLConverterTool
* @author     Andrey Hristov <andrey@php.net>, Ulf Wendel <ulf.wendel@phpdoc.de>
* @copyright  1997-2006 The PHP Group
* @license    http://www.php.net/license/3_0.txt  PHP License 3.0
* @version    CVS: $Id:$, Release: @package_version@
* @link       http://www.mysql.com
* @since      Available since Release 1.0
*/
?>
<div class="conversion">
    <?PHP     
    $id_summary = '_summary';    
    if (($snippet_conv['found'] == $snippet_conv['converted']) && (count($snippet_conv['errors']) == 0)) {
        $title = 'OK: ';
        $class = 'conversionok';                             
    } else if (($snippet_conv['found'] == $snippet_conv['converted']) && (count($snippet_conv['errors']) > 0)) {
        $title = 'Warning: ';
        $class = 'conversionwarning';                     
    } else {
        $title = 'Error: ';
        $class = 'conversionerror';
    }    
    ?>
    <table class="conversionheadline">
    <tr>
        <td><?php printf('<span class="%s" onclick=\'toggle_view("%s")\'>%s</span>', $class, $id_summary, ($snippet_show_details) ? '+' : '+'); ?></td>
        <td align="right">
            <?php        
            printf('<span class="%s">%s</span>', $class, $title);
            ?>                      
        </td>
        <td width="99%">
            <span class="<?php print $class; ?>">Snippet</span>
        </td>
    </tr>
    </table>
    <div class="conversiondetails" id="<?php print $id_summary; ?>"  <?php if ($snippet_show_details) print 'style="display:inline"'; ?>>
    <h3 style="margin-left:1em">Summary</h3>
    <table cellpadding="0" cellspacing="0" class="conversiondetailstable">
    <tr>
        <th align="right">Found&nbsp;equals&nbsp;Converted?</th>
        <th align="right">Found</th>
        <th align="right">Converted</th>        
        <th align="right">Warnings/Errors</th>        
        <th align="right" width="99%">Length</th>
    </tr>
    <tr>
        <td align="right"><?php print ($snippet_conv['found'] == $snippet_conv['converted']) ? 'Yes' : 'No'; ?></td>
        <td align="right"><?php print $snippet_conv['found']; ?></td>
        <td align="right"><?php print $snippet_conv['converted']; ?></td>
        <td align="right"><?php print count($snippet_conv['errors']); ?></td>
        <td align="right"><?php print strlen($snippet_conv['output']); ?></td>
    </tr>    
    </table>   
    <?php if (!empty($snippet_conv['errors'])) { ?>
    <h3 style="margin-left:1em">Warnings/Errors</h3>
    <table cellpadding="0" cellspacing="0" class="conversiondetailstable">    
    <tr>
        <th>Line</td>
        <th>Message</th>
    </tr>
    <?PHP
    foreach ($snippet_conv['errors'] as $k => $msg) {
    ?>
    <tr class="<?php print ($k % 2) ? 'tddark' : 'tdbright'; ?>">
        <td><?php print $msg['line']; ?></td>
        <td><?php print $msg['msg']; ?></td>
    </tr>
    <?PHP
    } 
    ?>
    </table>
    <?php } ?>
    <h3 style="margin-left:1em">Generated Code</h3>
    <table cellpadding="0" cellspacing="0" class="conversiondetailstable">    
    <tr>
        <td style="padding:0.25em"><?php highlight_string($snippet_conv['output']) ?></td>
    </tr>
    </table>
    <br />&nbsp;
    </div>
</div>