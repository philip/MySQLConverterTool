<?php

require_once 'Converter.php';

/**
 * CLI Interface.
 *
 * @category   CLI
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

/**
 * Parse the command line options.
 *
 * @param    int
 * @param    array
 *
 * @return array
 */
function parseOptions($argc, $argv)
{
    $options = array();
    $error = null;

    if ($argc < 2) {
        $error = 'No options given';

        return array($options, $error);
    }

    reset($argv);

    // skip $argv[0] - program name
    next($argv);

    while (list($k, $arg) = each($argv)) {
        $arg = trim($arg);
        switch ($arg) {
            case '-f':
                if (list($k, $arg) = each($argv)) {
                    $arg = trim($arg);
                    if (substr($arg, 0, 1) == '-') {
                        $error = '-f needs a file name';
                        break 2;
                    } elseif (!file_exists($arg)) {
                        $error = sprintf('"%s" does not exist', $arg);
                        break 2;
                    } elseif (!is_file($arg)) {
                        $error = sprintf('"%s" is not a file', $arg);
                        break 2;
                    } elseif (!is_readable($arg)) {
                        $error = sprintf('"%s" is not reabale', $arg);
                        break 2;
                    } else {
                        $options['files'][$arg] = $arg;
                    }
                } else {
                    $error = sprintf('-f needs a file name');
                    break 2;
                }
                break;

            case '-d':
                if (list($k, $arg) = each($argv)) {
                    $arg = trim($arg);
                    if (substr($arg, 0, 1) == '-') {
                        $error = '-d needs a directory name';
                        break 2;
                    } elseif (!file_exists($arg)) {
                        $error = sprintf('"%s" does not exist', $arg);
                        break 2;
                    } elseif (!is_dir($arg)) {
                        $error = sprintf('"%s" is not a directory', $arg);
                        break 2;
                    } elseif (!is_readable($arg)) {
                        $error = sprintf('"%s" is not readable', $arg);
                        break 2;
                    } else {
                        $options['directories'][$arg] = $arg;
                    }
                } else {
                    $error = sprintf('-d needs a directory name');
                    break 2;
                }
                break;

            case '-s':
                if (list($k, $arg) = each($argv)) {
                    $arg = trim($arg);
                    if ('' == $arg) {
                        $error = '-s expects a code snippet to follow the option';
                        break 2;
                    }
                    $options['snippet'] = $arg;
                } else {
                    $error = '-s expects a code snippet to follow the option';
                    break 2;
                }
                break;

            case '-h':
            case '--help':
                $options['help'] = true;

            case '-u':
                $options['update'] = true;
                break;

            case '-b':
                $options['backup'] = true;
                $options['update'] = true;
                break;

            case '-v':
                if (isset($options['quiet'])) {
                    $error = 'You cannot use -v with -q';
                    break 2;
                }
                $options['verbose'] = true;
                break;

            case '-q':
                if (isset($options['verbose'])) {
                    $error = 'You cannot use -q with -v';
                    break 2;
                }
                $options['quiet'] = true;
                break;

            case '-w':
                $options['warnings'] = true;
                break;

            case '-p':
                if (list($k, $arg) = each($argv)) {
                    $arg = trim($arg);
                    if ('' == $arg) {
                        $error = '-p needs a search pattern';
                        break 2;
                    } else {
                        $options['pattern'] = $arg;
                    }
                } else {
                    $error = '-p needs a search pattern';
                    break 2;
                }
                break;

            default:
                $error = sprintf('Invalid option "%s"', $arg);
                break;

        }
    }

    return array($options, $error);
} // end func parseOptions

/**
 * Prints the help message with usage instructions.
 *
 * @param    array
 */
function printHelp($argv)
{
    printf("\n");
    printf("Usage of %s :\n\n", $argv[0]);
    printf("-f <file>         Convert file\n");
    printf("-d <directory>    Convert directory\n");
    printf('-p <pattern>      File name pattern for -d, e.g. -p "*.php,*.php3". Default: *');
    printf("\n");
    printf("-s <code>         Convert code snippet\n");
    printf("\n");
    printf("-u                Update (modify) input file during the conversion\n");
    printf("-b                Backup files to [original_name].org before they get updated\n\n");
    printf("-v                verbose - print conversion details\n");
    printf("-w                warnings - print errors/warnings, if any\n");
    printf("-q                quiet - don't print the generated code\n");
    printf("\n\n");
} // end func printHelp

/**
 * Returns a status description (OK, Error, Warning) based on the conversion result.
 *
 * @param    array
 *
 * @return string
 */
function getConversionStatus($conv_result)
{
    $status = null;
    if (($conv_result['found'] == $conv_result['converted']) && (count($conv_result['errors']) == 0)) {
        $status = 'OK';
    } elseif (($conv_result['found'] == $conv_result['converted']) && (count($conv_result['errors']) > 0)) {
        $status = 'Warning';
    } elseif (($conv_result['found'] != $conv_result['converted']) && (count($conv_result['errors']) > 0)) {
        $status = 'Error';
    }

    return $status;
} // end func getConversionStatus

/**
 * Prints the overview summary with conversion details.
 *
 * @param    array
 * @param    string
 * @param    mixed
 */
function printConversionHeader($conv_result, $status, $file = null)
{
    if (!is_null($file)) {
        echo "\n";
        printSeperator("File $file");
        echo "\n";
    }

    echo "\n";
    printSeperator('Summary', '-');
    echo "\n";

    printf("Status: %s\n", $status);
    printf("Number of mysql_-functions found: %d\n", $conv_result['found']);
    printf("Number of mysql_-functions converted: %d\n", $conv_result['converted']);
    printf("Warnings/Errors: %d\n", count($conv_result['errors']));
    printf("Code length: %d Bytes\n", strlen($conv_result['output']));
} // end func printConversionHeader

/**
 * Prints the conversion errors.
 *
 * @param    array
 */
function printConversionErrors($conv_result)
{
    echo "\n";
    echo "\n";
    printSeperator('Warnings/Errors', '-');
    echo "\n";

    foreach ($conv_result['errors'] as $k => $error) {
        printSeperator(sprintf('Warning/Error on line %d', $error['line']), ' ');
        echo $error['msg'];
        echo "\n";
    }
} // end func printConversionErrors

/**
 * Prints the generated source code.
 * 
 * @param    array
 */
function printConversionOutput($conv_result, $verbose, $quiet)
{
    if ($verbose) {
        echo "\n";
        echo "\n";
        printSeperator('Generated code', '-');
        echo "\n";
    }

    if (!$quiet) {
        print $conv_result['output'];
    }

    if ($verbose) {
        echo "\n";
        echo "\n";
        printSeperator('End of code', '-');
        echo "\n";
    }
} // end func printConversionOutput

/**
 * Converts a file.
 *
 * @param    string
 * @param    bool
 */
function convertFile($file, $verbose, $quiet, $update, $backup, $warnings, $seperator = '#')
{
    $conv = new MySQLConverterTool_Converter();
    $ret = $conv->convertFile($file);
    $status = getConversionStatus($ret);

    if ($quiet) {
        printSeperator(sprintf('[ %-7s ] %s', $status, $file), $seperator);
    }

    if ($verbose) {
        printConversionHeader($ret, $status, $file);
    }

    if ($backup) {
        $ffile = $file.'.org';
        if (file_exists($ffile) && !unlink($ffile)) {
            printf("Error:\n");
            printf("Cannot unlink old backup file '%s'. Check the file permissions.\n\n", $ffile);

            return;
        }

        if (!rename($file, $ffile)) {
            printf("Error:\n");
            printf("Cannot rename '%s' to %s'. Check the file permissions.\n\n", $file, $ffile);

            return;
        }

        if ($verbose) {
            printf("Backup created.\n", $ffile);
        }
    }

    if ($update) {
        if (!$fp = fopen($file, 'w')) {
            printf("Error:\n");
            printf("Cannot modify file '%s'. Check the file permissions.\n\n", $file);

            return;
        }
        fwrite($fp, $ret['output']);
        fclose($fp);

        if ($verbose) {
            printf("File updated/modified.\n", $file);
        }
    }

    if (($verbose || $warnings) && count($ret['errors']) > 0) {
        printConversionErrors($ret);
    }

    printConversionOutput($ret, $verbose, $quiet);
} // end func convertFile

/**
 * 
 */
function convertSnippet($code, $verbose, $quiet, $warnings, $seperator = '#')
{
    $conv = new MySQLConverterTool_Converter();
    $ret = $conv->convertString($code);
    $status = getConversionStatus($ret);

    if ($quiet) {
        printSeperator('Snippet', $seperator);
    }

    if ($verbose) {
        printConversionHeader($ret, $status, null);
    }

    if (($verbose || $warnings) && count($ret['errors']) > 0) {
        printConversionErrors($ret);
    }

    printConversionOutput($ret, $verbose, $quiet);
} // end func convertSnippet

/**
 * Helper: prints a line with a title.
 *
 * @param    string
 * @param    string
 */
function printSeperator($title, $seperator = '#')
{
    $len = strlen($title) + 2;

    $num = 1;
    // $num = max(floor((80 - $len) / 2), 0);
    for ($i = 0; $i < $num; ++$i) {
        print $seperator;
    }

    printf(' %s ', $title);

    if ($num > 0) {
        $num = 80 - $num - $len;
        for ($i = 0; $i < $num; ++$i) {
            print $seperator;
        }
    }

    echo "\n";
} // end func printSeperator

if (!isset($argc) || !isset($argv)) {
    // redirect to the web gui
    // or maybe die("This is the command line interface, use php -f cli.php to run it! Open index.php for the web interface."); ?    
    header('Location: GUI/index.php');
    exit(0);
}

list($options, $error) = parseOptions($argc, $argv);
if (!is_null($error) || empty($options) || isset($options['help'])) {
    // some sort of trouble or help output requested

    if (!is_null($error)) {
        printf("\n");
        printf("Error: %s\n", $error);
    }
    printHelp($argv);
} else {
    echo "\n";

    if (!empty($options['files'])) {
        foreach ($options['files'] as $k => $file) {
            convertFile($file, isset($options['verbose']), isset($options['quiet']), isset($options['update']), isset($options['backup']), isset($options['warnings']));
        }
    }

    if (!empty($options['directories'])) {
        $conv = new MySQLConverterTool_Converter();
        foreach ($options['directories'] as $k => $directory) {
            $files = $conv->getFilesOfDirectory($directory, (isset($options['pattern'])) ? $options['pattern'] : '');
            if (empty($files)) {
                printSeperator(sprintf('No files found in "%s"', $directory), '*');
                echo "\n";
                continue;
            }

            printSeperator(sprintf('Directory "%s"', $directory), '*');
            echo "\n";
            foreach ($files as $k => $file) {
                convertFile($file,  isset($options['verbose']), isset($options['quiet']), isset($options['update']), isset($options['backup']), isset($options['warnings']), ' ');
            }
        }
    }

    if (isset($options['snippet'])) {
        convertSnippet($options['snippet'], isset($options['verbose']), isset($options['quiet']), isset($options['warnings']));
    }
}
