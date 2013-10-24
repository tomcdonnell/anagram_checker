<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go -=b
*
* Filename: "index.php"
*
* Project: Anagram Poem Checker.
*
* Purpose: The main file for the project.
*
* Author: Tom McDonnell 2010-11-16.
*
\**************************************************************************************************/

// Settings. ///////////////////////////////////////////////////////////////////////////////////////

error_reporting(-1);
session_start();

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   $action =
   (
      (array_key_exists('action', $_GET))? $_GET['action']:
      (
         (array_key_exists('anagramChecker', $_SESSION))? 'addSessionText': 'clearForm'
      )
   );

   switch ($action)
   {
    case 'addExampleText':
      $anagramPhrase = 'Only Anagram Lines';
      $textToCheck   = <<<STR
I yell anagrams. Non-
angry ones. In a mall,
I normally nag sane,
loyal, Grannies. Man,
orals agleam, ninny
as neon! Alarmingly,
any moral leanings
annoy managers. Ill-
mannerly as-in gaol
mongrels. Nail any a
mall-Grannie. Say 'No',
an' earn manly slog. I
annoy ill managers.
Alas, any lemon-grin,
lone salarying man
yearns manna. Go, ill-
ninny! Slam a lager! O,
lager! Sin on layman,
miner's agony. All an
angler inlays. Moan
insanely man, or lag
solemnly. A ring, an' a
gal. Loins yearn, man.
A lone, grisly man, an'
a mall. Syringe on an
alloy sign. Amen. Ran
lanes angrily. Am no
ogre. Any ill man's an
amoral ninny. A leg's
mangy, sore. All in an
alien slag. Ran on my
leg, airman nylons a
meanly sling. An oar!
A nail! My angler son! -
on a Granny? - I smell a
mil ale! 'Son, a Granny!
In son! Yell! Anagram!'
STR;
      break;
    case 'addSessionText':
      $anagramPhrase = $_SESSION['anagramChecker']['anagramPhrase'];
      $textToCheck   = $_SESSION['anagramChecker']['textToCheck'  ];
      break;
    case 'clearForm':
      $anagramPhrase = '';
      $textToCheck   = '';
      break;
    default:
      throw new Exception("Unknown action '$action'.");
   }
}
catch (Exception $e)
{
   echo $e->getMessage();
   die;
}

// HTML code. //////////////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html>
<html>
 <head><title>Anagram Checker</title></head>
 <body>
  <a class='backLink' href='../../index.php'>Back to tomcdonnell.net</a> |
  <a class='backLink' href='../../submodules/anagram_finder'>Anagram Finder</a>
  <h1>Anagram Checker</h1>
  <p>Use this tool to check that all lines of a given text are anagrams of a given phrase.</p>
  <hr/>
  <p>
   <a href='index.php?action=addExampleText'>Add example text</a> |
   <a href='index.php?action=clearForm'>Clear text</a>
  </p>
  <form action='check_anagram_lines.php' method='post'>
   <p>
    Anagram Phrase:<br/>
    <input type='text' name='anagramPhrase' size='80' value='<?php echo $anagramPhrase; ?>'/>
   </p>
   <p>
    Text to check:<br/>
    <textarea rows='30' cols='80' name='textToCheck'><?php echo $textToCheck;?></textarea>
   </p>
   <input type='submit' value='Submit'/>
  </form>
 </body>
</html>
<?php
/*******************************************END*OF*FILE********************************************/
?>
