<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Article;

class ArticleController extends Controller {
    private function getLang($lang='ua') {
        return $lang;
    }
    private function pickLanguage($lang) {
        $path = dir_path('resources');
        $file_name = $lang;
        $file_extension = '.php';
        $file = $path.DIRECTORY_SEPARATOR.$file_name.$file_extension;
        $current_texts = [];
        if (File::exists($file)) {
            include $file;
            $page = (string)r();
            $current_texts = $texts[$page];
        }
        return $current_texts;
    }
    private function getTexts($lang) {
        $lang = $this->getLang($lang);
        $texts_array = $this->pickLanguage($lang);
        if (!empty($texts_array)) {
            $texts = (object) $texts_array;
            return $texts;
        }
        return false;
    }

    public function articles($lang='ua') {
        $article = new Article();
        $articles = $article->getArticlesByLang($lang);
        return v()->with([
            'articles'      => $articles,
            'lang'          => $this->getLang($lang),
        ]);
    }
    public function article($article, $article_id, $lang) {
        $article = Article::find($article_id);
        $articles = new Article();
        $same = $articles->getSameArticles($lang, 2, $article->category);
//        print_r($same);
        exit;
        return v()->with([
            'article'       => $article,
            'same_articles' => $same
        ]);
    }
}