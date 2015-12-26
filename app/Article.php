<?php namespace App;

use App\BaseModel;
use DB;
use Illuminate\Support\Facades\Input;

class Article extends BaseModel {
    protected $guarded = [];
    protected $primaryKey = 'article_id';
    public $timestamps = false;

    public function getLatestNews($lang='ua', $quantity) {
        $last_articles = DB::table('articles')
            ->where('language', $lang)
            ->orderBy('date', 'desc')
            ->take($quantity)
            ->get();
        return $last_articles;
    }
    public function getSameArticles($lang, $quantity, $category) {
        $articles = DB::table('articles')->where('language', $lang);
        $articles = $articles->where('category', $category);
        $articles = $articles->orderBy('date', 'desc');
        $articles = $articles->take($quantity);
        $articles = $articles->get();
        print_r($articles);
        exit;
        return $articles;
    }
    public function getArticlesByLang($lang='ua') {
        $articles = DB::table('articles')
            ->where('language', $lang)
            ->orderBy('date', 'desc')
            ->paginate(8);
        return $articles;
    }

}