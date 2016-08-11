<?PHP
/**
* GUI Template: convert directory form.
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
    Read all files from a directory and convert them. 
</div>
<div class="maintextbox">
    You can choose if the result of the conversion gets only displayed on the 
    screen or if you want to modify the source files. By default 
    backups of the source files will be created before 
    they get modified. A search pattern can be defined to restrict
    the search for source files to a certain file suffix.
</div>
<?PHP
if (!empty($snippet_errors)) {
    ?>
<div class="maintextbox">
    <h2>Errors</h2>    
    <ul>
    <?PHP
    foreach ($snippet_errors as $field => $msg) {
        printf('<li class="error">%s</li>', htmlspecialchars($msg));
    } ?>
    </ul>    
</div>            
<?php

}
?>  
<div class="maintextbox">    
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" name="directory" id="directory" method="post">
    <script language="JavaScript">
        
        function activate_backup() {                     
                        
            if (document.directory.update[0].checked == true)
                document.directory.backup.checked = false;
                
            if (document.directory.update[1].checked == true)
                document.directory.backup.checked = true;            
            
        }
        
        function validate_form() {
            
            if (document.directory.directory.value == "") {
                document.directory.directory.focus();
                alert("Please specify a directory!");                
                return false;
            }
            
            return true;
        }
        
    </script>
    <fieldset>
        <legend>Convert a directory</legend>
        <br />
        <table align="right">        
        <tr>
            <td class="<?php echo isset($snippet_errors['directory']) ? 'formlabelerror' : 'formlabel'; ?>">Directory</td>
            <td class="formelement"><input type="text" name="directory" size="60" value="<?php echo (isset($_POST['directory'])) ? $_POST['directory'] : ''; ?>" /></td>
        </tr>        
        <tr>
            <td class="<?php echo isset($snippet_errors['pattern']) ? 'formlabelerror' : 'formlabel'; ?>">File pattern</td>
            <td class="formelement"><input type="text" name="pattern" size="60" value="<?php echo (isset($_POST['pattern'])) ? $_POST['pattern'] : '*.php, *.phps, *.php3, *.php4, *.php5'; ?>" /></td>
        <tr>
        <tr>
            <td></td>
            <td class="formhint">
                The only recognized pattern is "*". Use "," to seperate several patterns.<br />
                Examples: "myapp_*.php", "*.php", "*.php, *.php4, *.phps".             
            </td>
        <tr>
        <tr>
            <td class="formlabel">Skip pattern</td>
            <td class="formelement"><input type="text" name="skip_pattern" size="60" value="<?php echo (isset($_POST['skip_pattern'])) ? $_POST['skip_pattern'] : ''; ?>" /></td>
        </tr>
        <tr>
            <td></td>
            <td class="formhint">
                Enter . to skip files/directories that begin with ., or a full match pattern passed directly to preg_match (so include delimiters)<br />
                -- leave empty (the default) to not perform this skip pattern.
            </td>
        <tr>
        <tr>
            <td class="<?php echo (isset($snippet_errors['update'])) ? 'formlabelerror' : 'formlabel'; ?>">Update&nbsp;files?</td>
            <td class="formelement">
                <input type="radio" name="update" id="update" value="no" onClick="activate_backup()" <?php echo (isset($_POST['update']) && $_POST['update'] != 'no') ? '' : 'checked'; ?>> No<br />
                <input type="radio" name="update" id="update" value="yes" onClick="activate_backup()" <?php echo (isset($_POST['update']) && $_POST['update'] == 'yes') ? 'checked' : ''; ?>> Yes
            </td>
        <tr>
        <tr>
            <td class="<?php echo (isset($snippet_errors['backup'])) ? 'formlabelerror' : 'formlabel'; ?>">Backup files?</td>
            <td class="formelement">
                <input type="checkbox" name="backup" id="backup" <?php echo (isset($_POST['backup'])) ? 'checked' : ''; ?>> Yes, backup the original files to &lt;name.org&gt;              
            </td>
        <tr>
        <tr>
            <td colspan="2" class="formsubmit">
                <input type="submit" name="start" value="Start the conversion &gt;" onclick="return validate_form()">&nbsp;&nbsp;
                <input type="submit" name="cancel" value="Cancel">
            </td>
        </tr>        
        </table>
    </fieldset>
</form>
</div>
