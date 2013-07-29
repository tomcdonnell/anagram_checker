<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go -=b
*
* Filename: "check_anagram_lines.php"
*
* Project: Anagram Poem Checker.
*
* Purpose: Check that each line of a given text is an anagrams of a given string.
*
* Author: Tom McDonnell 2010-11-16.
*
\**************************************************************************************************/

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/../../lib/tom/php/utils/UtilsHtml.php';

// Settings. ///////////////////////////////////////////////////////////////////////////////////////

error_reporting(-1);
session_start();

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   if (!array_key_exists('anagramPhrase', $_POST) || !array_key_exists('textToCheck', $_POST))
   {
      throw new Exception('This page has been used incorrectly.');
   }

   if (strlen($_POST['textToCheck']) > 500000)
   {
      throw new Exception('Text to check exceeded length limit.');
   }

   $_SESSION['anagramChecker'] = $_POST;
}
catch (Exception $e)
{
   echo $e->getMessage();
   die;
}

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function stripNonAlphaCharactersFromString($string)
{
   $strlen    = strlen($string);
   $newString = '';

   for ($i = 0; $i < $strlen; ++$i)
   {
      $char = $string[$i];

      if (ctype_alpha($char))
      {
         $newString .= $char;
      }
   }

   return strtolower($newString);
}

/*
 * String A is the master string against which string B is checked.
 */
function checkAnagram($stringA, $stringB)
{
   if (strlen($stringA) != strlen($stringB))
   {
      return false;
   }

   $missingLettersString = '';
   $extraLettersString   = '';

   for ($i = 0; $i < strlen($stringA); ++$i)
   {
      $char = $stringA[$i];
      $posB = strpos($stringB, $char);

      if ($posB === false)
      {
         $missingLettersString .= $char;
      }
      else
      {
         $stringB = substr_replace($stringB, '', $posB, 1);
      }
   }

   $extraLettersString = $stringB;

   if (strlen($extraLettersString) == 0)
   {
      return true;
   }

   return array($missingLettersString, $extraLettersString);
}

function echoMessage($color, $string)
{
   echo "<span style='color: ", UtilsHtml::escapeSingleQuotes($color), "'>";
   echo htmlentities($string);
   echo "</span><br/>\n";
}

// HTML code. //////////////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html>
<html>
 <head><title>Anagram Checker</title></head>
 <body>
  <a class='backLink' href='http://www.tomcdonnell.net'>Back to tomcdonnell.net</a> |
  <a class='backLink' href='http://www.tomcdonnell.net/small_apps/anagram_finder'>Anagram Finder</a>
  <h1>Anagram Checker</h1>
  <p>Use this tool to check that all lines of a section of text are anagrams of a given phrase.</p>
  <hr/>
  <a href='index.php'>Back</a>
  <p><?php echo $_POST['anagramPhrase']; ?> (&lt;-- Given Phrase)</p>
  <p>
<?php
$linesToCheck                = explode("\n", trim($_POST['textToCheck']));
$anagramPhrase               = trim($_POST['anagramPhrase']);
$strippedAnagramPhrase       = stripNonAlphaCharactersFromString($anagramPhrase);
$strlenStrippedAnagramPhrase = strlen($strippedAnagramPhrase);
$nPerfectLines               = 0;

foreach ($linesToCheck as $line)
{
   $line = str_replace("\r", '', $line);
   echo "   $line";

   $strippedLine       = stripNonAlphaCharactersFromString($line);
   $strlenStrippedLine = strlen($strippedLine);

   if ($strlenStrippedLine != $strlenStrippedAnagramPhrase)
   {
      $difference     = $strlenStrippedLine - $strlenStrippedAnagramPhrase;
      $differenceWord = ($difference > 0)? 'many': 'few';
      $differenceAbs  = abs($difference);
      $lettersWord    = ($differenceAbs == 1)? 'letter': 'letters';
      echoMessage('#f00', " ($differenceAbs $lettersWord too $differenceWord)");
      continue;
   }

   $checkResult = checkAnagram($strippedAnagramPhrase, $strippedLine);

   if (is_array($checkResult))
   {
      echoMessage('#f00', "(missing: '{$checkResult[0]}', extra: '{$checkResult[1]}')");
   }
   else
   {
      if ($checkResult === false)
      {
         throw new Exception('Unexpected result.');
      }

      echoMessage('#0f0', '(perfect anagram)');
      ++$nPerfectLines;
   }
}
?>
  </p>
<?php
$nImperfectLines = count($linesToCheck) - $nPerfectLines;
$stylesString    = 'width: 500px; padding: 10px; text-align: center;';

if ($nImperfectLines == 0)
{
   $stylesString .= ' background: #00ff00;';
   $message       = 'Perfect Result!';
}
else 
{
   $stylesString .= ' background: #ff0000;';
   $message       = "$nImperfectLines incorrect lines were found.";;
}
?>
  <p style='<?php echo UtilsHtml::escapeSingleQuotes($stylesString); ?>'>
<?php
echo '   ', htmlentities($message), "\n";
?>
  </p>
  <a href='index.php'>Back</a>
 </body>
</html>
<?php
/*******************************************END*OF*FILE********************************************/
?>
