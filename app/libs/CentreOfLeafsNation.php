<?php

namespace App\Parser;

class CentreOfLeafsNation 
{

    /**
     * 
     * @param string $content 
     * @return array 
     */
    public static function parse(string $content) : array
    {
        $response = [];

        if(!preg_match_all(
            '/<h1\s*?class="entry-title"><a\s*?href="(http[^"]+?)"[^>]+>([^<]+?)<\/a>/',
            $content, $matches)) {
            return [];
        }

        // loop through matches and add to response
        foreach($matches[1] as $key => $match) {

            $article_url = $matches[1][$key];

            $article_content = get_content($article_url);

            \sleep(\get_config('sleep_between_requests'));

            // author
            if(!\preg_match('/author-name">([^<]+?)<\/span>/', $article_content, $author_match)) {
                continue;
            }

            if(!\preg_match('/published"\s*?datetime="([^"]+?)"/', $article_content, $date_match)) {
                continue;
            }

            $response[] = [
                'title' => $matches[2][$key],
                'url' => $article_url,
                'author' => $author_match[1],
                'date' => Date('Y-m-d H:i:s', strtotime($date_match[1]))
            ];
        }

        return $response;
    }
}