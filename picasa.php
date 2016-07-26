<?php

/**
 * Created by PhpStorm.
 * User: moridrin
 * Date: 05/06/16
 * Time: 09:37
 */
class picasa
{

    /**
     * @param int    $user_id
     * @param int    $album_id
     * @param string $auth_key default to null
     *
     * @return array
     */
    static function getImages($user_id, $album_id, $auth_key = null)
    {
        ob_start();
        $url = 'https://picasaweb.google.com/data/feed/api/user/';
        $url .= $user_id;
        $url .= '/albumid/';
        $url .= $album_id;
        if ($auth_key != null) {
            $url .= '?authkey=';
            $url .= $auth_key;
        }
        $response = wp_remote_get(esc_url_raw($url));
        $response = simplexml_load_string(str_replace(':', ':', $response["body"]));

        $album = array();
        foreach ($response['entry'] as $entry) {
            /** @noinspection PhpUndefinedMethodInspection */
            $width = intval($entry->xpath('gphoto:width')[0]);
            /** @noinspection PhpUndefinedMethodInspection */
            $height = intval($entry->xpath('gphoto:height')[0]);
            $url = strval($entry->content->attributes()->src);
            $photo = new PicasaPhoto($width, $height, $url);
            $photo->title = strval($entry->title);
            $photo->id = intval($entry->id);
            $album[] = $photo;
        }
        return $album;
    }
}