<?php

require_once'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_Query');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');

function createPublishedPost($user, $pass, $blogID, $title, $content, $labelnya) {
    $service = 'blogger';
    $client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $service, null, Zend_Gdata_ClientLogin::DEFAULT_SOURCE, null, null, Zend_Gdata_ClientLogin::CLIENTLOGIN_URI, 'GOOGLE');
    $gdClient = new Zend_Gdata($client);
    $uri = 'http://www.blogger.com/feeds/' . $blogID . '/posts/default';
    $entry = $gdClient->newEntry();
    $entry->title = $gdClient->newTitle($title);
    $entry->content = $gdClient->newContent($content);
    $labels = $entry->getCategory();
    $newLabel = $gdClient->newCategory($labelnya, 'http://www.blogger.com/atom/ns#');
    $labels[] = $newLabel; // Append the new label to the list of labels.
    $entry->setCategory($labels);
    $entry->content->setType('text');

    $createdPost = $gdClient->insertEntry($entry, $uri);
    $idText = preg_split('/-/i', $createdPost->id->text);
    $newPostID = $idText[2];

    return $newPostID;
}

?>