<?php
function plugin_install()
{
  $q = '
INSERT INTO '.CONFIG_TABLE.' (param,value,comment)
  VALUES
  ("akismet_api_key","","Akismet online service API key")
;';
  pwg_query($q);
  $q = '
INSERT INTO '.CONFIG_TABLE.' (param,value,comment)
  VALUES
  ("akismet_spam_action","moderate","Action when akismet detects spam")
;';
  pwg_query($q);
  $q = '
INSERT INTO '.CONFIG_TABLE.' (param,value,comment)
  VALUES
  ("akismet_counters","0/0","Akismet counters")
;';
  pwg_query($q);
  $q = '
 ALTER TABLE '.COMMENTS_TABLE.' 
     ADD spam_feedback ENUM(\'spam\',\'ham\') NOT NULL DEFAULT \'ham\' COMMENT \'from Akismet plugin\',
     ADD user_agent VARCHAR(512) NOT NULL AFTER anonymous_id COMMENT \'from Akismet plugin\'     
;';
  pwg_query($q);
  $q = '
 ALTER TABLE '.COA_TABLE.' 
     ADD spam_feedback ENUM(\'spam\',\'ham\') NOT NULL DEFAULT \'ham\' COMMENT \'from Akismet and Comment on Albums plugins\',
     ADD user_agent VARCHAR(512) NOT NULL AFTER anonymous_id COMMENT \'from Akismet and Comment on Albums plugins\'
;';
  pwg_query($q);
  $q = '
 ALTER TABLE '.GUESTBOOK_TABLE.'
     ADD spam_feedback ENUM(\'spam\',\'ham\') NOT NULL DEFAULT \'ham\' COMMENT \'from Akismet plugin and Guestbook plugins\',
     ADD user_agent VARCHAR(512) NOT NULL AFTER anonymous_id COMMENT \'from Akismet and Guestbook plugins\'
;';
  pwg_query($q);

}

function update()
{
  $q = '
 ALTER TABLE '.COMMENTS_TABLE.' 
     ADD spam_feedback ENUM(\'spam\',\'ham\') NOT NULL DEFAULT \'ham\' COMMENT \'from Akismet plugin\',
     ADD user_agent VARCHAR(512) NOT NULL AFTER anonymous_id COMMENT \'from Akismet plugin\'     
;';
  pwg_query($q);
  $q = '
 ALTER TABLE '.COA_TABLE.' 
     ADD spam_feedback ENUM(\'spam\',\'ham\') NOT NULL DEFAULT \'ham\' COMMENT \'from Akismet and Comment on Albums plugins\',
     ADD user_agent VARCHAR(512) NOT NULL AFTER anonymous_id COMMENT \'from Akismet and Comment on Albums plugins\'
;';
  pwg_query($q);
  $q = '
 ALTER TABLE '.GUESTBOOK_TABLE.'
     ADD spam_feedback ENUM(\'spam\',\'ham\') NOT NULL DEFAULT \'ham\' COMMENT \'from Akismet plugin and Guestbook plugins\',
     ADD user_agent VARCHAR(512) NOT NULL COMMENT \'from Akismet and Guestbook plugins\'
;';
  pwg_query($q);
}

function plugin_uninstall()
{
  foreach (array('akismet_api_key','akismet_spam_action','akismet_counters') as $param)
  {
    $q = '
DELETE FROM '.CONFIG_TABLE.' WHERE param="'.$param.'" LIMIT 1';
    pwg_query( $q );
  }
    $q = '
 ALTER TABLE '.COMMENTS_TABLE.' 
     DROP COLUMN spam_feedback,
     DROP COLUMN user_agent
;';
  pwg_query($q);
    $q = '
 ALTER TABLE '.COA_TABLE.' 
     DROP COLUMN spam_feedback,
     DROP COLUMN user_agent
;';
  pwg_query($q);
    $q = '
 ALTER TABLE '.GUESTBOOK_TABLE.' 
     DROP COLUMN spam_feedback,
     DROP COLUMN user_agent
;';
  pwg_query($q);
}

?>
