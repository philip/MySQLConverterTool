<?PHP
/**
* GUI Template: convert file template.
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
    Convert a file
</div>
<div class="maintextbox">
    You can choose if the result of the conversion gets displayed on the 
    screen or if you want to modify the source file. By default a
    backup of the source file will be created before 
    it gets modified.
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
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" name="file" id="file" method="post">
    <script language="JavaScript">
        
        function activate_backup() {                     
                        
            if (document.file.update[0].checked == true)
                document.file.backup.checked = false;
                
            if (document.file.update[1].checked == true)
                document.file.backup.checked = true;            
            
        }
        
        function validate_form() {
            
            if (document.file.file.value == "") {
                document.file.file.focus();
                alert("Please specify a file!");                
                return false;
            }
            
            return true;
        }
        
    </script>
    <fieldset>
        <legend>Convert a file</legend>
        <br />
        <table align="right">        
        <tr>
            <td class="<?php echo isset($snippet_errors['file']) ? 'formlabelerror' : 'formlabel'; ?>">File</td>
            <td class="formelement"><input type="text" name="file" size="60" value="<?php echo (isset($_POST['file'])) ? $_POST['file'] : ''; ?>" /></td>
        </tr>                
        <tr>
            <td class="<?php echo (isset($snippet_errors['update'])) ? 'formlabelerror' : 'formlabel'; ?>">Update&nbsp;file?</td>
            <td class="formelement">
                <input type="radio" name="update" id="update" value="no" onClick="activate_backup()" <?php echo (isset($_POST['update']) && $_POST['update'] != 'no') ? '' : 'checked'; ?>> No<br />
                <input type="radio" name="update" id="update" value="yes" onClick="activate_backup()" <?php echo (isset($_POST['update']) && $_POST['update'] == 'yes') ? 'checked' : ''; ?>> Yes
            </td>
        <tr>
        <tr>
            <td class="<?php echo (isset($snippet_errors['backup'])) ? 'formlabelerror' : 'formlabel'; ?>">Backup file?</td>
            <td class="formelement">
                <input type="checkbox" name="backup" id="backup" <?php echo (isset($_POST['backup'])) ? 'checked' : ''; ?>> Yes, backup the original file to &lt;name.org&gt;              
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
