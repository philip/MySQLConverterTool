<?PHP
/**
* GUI Template: directory conversion summary.
*
* @category   GUI
*
* @author     Andrey Hristov <andrey@php.net>, Ulf Wendel <ulf.wendel@phpdoc.de>
* @copyright  1997-2006 The PHP Group
* @license    http://www.php.net/license/3_0.txt  PHP License 3.0
*
* @version    CVS: $Id:$, Release: @package_version@
*
* @link       http://www.mysql.com
* @since      Available since Release 1.0
*/
?>
<div class="maintextbox">
    <h2>Results of the conversion</h2>    
    <table class="conversiondetailstable" style="margin-left:0em">    
    <tr>
        <th style="padding:0.5em" nowrap="nowrap">Number of Files</th>        
        <th style="padding:0.5em">OK</th>
        <th style="padding:0.5em">Warnings</th>
        <th style="padding:0.5em">Errors</th>
        <th style="padding:0.5em" nowrap="nowrap">Number of mysql_*-functions&nbsp;found</th>
        <th style="padding:0.5em" nowrap="nowrap">Number of mysql_*-functions&nbsp;converted</th>        
        <th style="padding:0.5em">Code&nbsp;(kB)</th>
    </tr>
    <tr>
        <td align="right" style="padding:0.5em"><?php echo $snippet_conv_count; ?></td>
        <td align="right" style="padding:0.5em"><?php echo $snippet_conv_ok; ?></td>
        <td align="right" style="padding:0.5em"><?php echo $snippet_conv_warnings; ?></td>
        <td align="right" style="padding:0.5em"><?php echo $snippet_conv_errors; ?></td>
        <td align="right" style="padding:0.5em"><?php echo $snippet_conv_found; ?></td>
        <td align="right" style="padding:0.5em"><?php echo $snippet_conv_converted; ?></td>
        <td align="right" style="padding:0.5em"><?php printf('%d', ($snippet_conv_length > 0) ? $snippet_conv_length / 1024 : 0); ?></td>
    </tr>
    </table>
</div>