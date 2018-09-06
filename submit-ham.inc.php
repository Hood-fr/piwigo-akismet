<?php /*
Plugin Name: RV Akismet
Version: 2.8.a
Description: Uses Akismet online service to submit comment as ham
(i.e. comment has been not detected as spam by Akismet although it was)
Plugin URI: http://piwigo.org/ext/extension_view.php?eid=192
Author: Hood_fr
*/

function akismet_user_comment_submit_ham($comment_id, $where)
{

    if (is_array ($comment_id)){
        $comment_id=$comment_id[0];
    }

    global $conf;

    include_once( dirname(__FILE__).'/akismet.class.php' );

    switch($where)
    {
        case 'album':
            $table=COA_TABLE;
            break;
        case 'guestbook':
            $table=GUESTBOOK_TABLE;
            break;
        default:
            $table=COMMENTS_TABLE;
    }

    $query = 'SELECT * FROM '.$table.' WHERE id = '.$comment_id.' LIMIT 1';

    $result=pwg_query($query);
    
    if (pwg_db_num_rows($result) > 0){
        $comment=pwg_db_fetch_assoc($result);
    }
    else
    {
        $comment=array(
            'category_id' => '1',
            'author' => 'bidon1',
            'content' => 'bidon2',
            'email' => 'bi@don.com',
            'anonymous_id' => '0.0.0.0',
            'category_id' => '1',
            'image_id' => '1',
            'website_url' => 'www.bidon.com',
            'spam_feedback' => 'spam'
        );
    }

    if($comment['spam_feedback']=='spam')//if comment was tagged as suspected-spam, whereas it is actually ham, it is submited back to akismet as ham
    {
        set_make_full_url();
        switch($where)
        {
        case 'guestbook':
          $url = defined('GUESTBOOK_URL') ? GUESTBOOK_URL : get_absolute_root_url();
          break;
        case 'album':
          // build category url with minimum data (only id is always known)
          $url = duplicate_index_url( array(
            'section'=>'categories',
            'category'=>array('id'=>$comment['category_id'], 'name'=>'', 'permalink'=>'')
            ) );
          break;
        default:
          $url = duplicate_picture_url( array('image_id'=>$comment['image_id']) );
        }
        unset_make_full_url();


        $aki_comm = array(
            'author' => $comment['author'],
            'body' => $comment['content'],
            'comment_author_url' => @$comment['website_url'],
            'comment_author_email' => $comment['email'],
            'permalink' => $url,
            'referrer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
        );

        $root_url=get_absolute_root_url();

        $akismet = new Akismet(get_absolute_root_url(), $conf['akismet_api_key'], $aki_comm);


        if( !$akismet->errorsExist() )
        {
        $answer=$akismet->submitHam();
        }
        else{
        $answer='Error in ham submission';
        }
    }
    else{
        $answer='Nothing to submit to Akismet';
    }

    return $answer;
}
?>
