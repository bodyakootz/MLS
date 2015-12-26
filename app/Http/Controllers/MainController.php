<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Mail;
use Request;
use App\Article;
use App\Http\Controllers\LanguageController;
use File;

class MainController extends Controller {
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

    public function index($lang='ua') {
        $article = new Article();
        $articles = $article->getLatestNews($lang, 4);
        if ($this->getTexts($lang)) {
            $texts = $this->getTexts($lang);
            return v()->with([
                'articles'   => $articles,
                'text'       => $texts
            ]);
        } else {
//            return redirect()->back();
//            return redirect()->route('error_c');
            return \View::make('errors/404', ['error' => 'Holy shit there are no such language']);
        }
    }
    public function about($lang='ua') {
        if ($this->getTexts($lang)) {
            $texts = $this->getTexts($lang);
            return v()->with([
                'text' => $texts
            ]);
        } else {
            return \View::make('errors/404', ['error' => 'Holy shit there are no such language']);
        }
    }
    public function contacts($lang='ua') {
        if ($this->getTexts($lang)) {
            $texts = $this->getTexts($lang);
            return v()->with([
                'text' => $texts
            ]);
        } else {
            return \View::make('errors/404', ['error' => 'Holy shit there are no such language']);
        }
    }
    public function services($lang='ua') {
        if ($this->getTexts($lang)) {
            $texts = $this->getTexts($lang);
            return v()->with([
                'text' => $texts,
                'lang' => $this->getLang($lang),
            ]);
        } else {
            return \View::make('errors/404', ['error' => 'Holy shit there are no such language']);
        }
    }
    public function service($lang='ua') {
        if ($this->getTexts($lang)) {
            $texts = $this->getTexts($lang);
            return v()->with([
                'text' => $texts
            ]);
        } else {
            return \View::make('errors/404', ['error' => 'Holy shit there are no such language']);
        }
    }
    public function admin() {
        return v();
    }
    public function admin_feedback() {
        return v();
    }
    public function admin_subscribers() {
        return v();
    }
    public function logging() {
        $data = Request::all();
        unset($data['_token']);

        $pass = Auth::attempt($data, true);
        if ($pass) {
            return redirect()->route('admin');
        } else {
            return redirect()->route('login')->withErrors('Неверный логин или пароль!');
        }
    }
    public function login() {
        if (Auth::check()) {
            return redirect()->route('admin');
        } else {
            return v();
        }
    }
    public function error() {
        return v();
    }

}