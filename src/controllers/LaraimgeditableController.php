<?php

namespace JansenFelipe\Laraeditable\Controllers;

use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Wa72\HtmlPageDom\HtmlPageCrawler;
use function public_path;

class LaraimgeditableController extends Controller {

    public function postIndex() {
        $imagem = Input::file('imagem');

        if (is_null($imagem))
            throw new Exception('Você não selecionou um arquivo');

        $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'uploads';
        $filename = date('YmdHis') . '_' . $imagem->getClientOriginalName();

        if ($imagem->move($destinationPath, $filename)) {

            //Load view laravel paths
            $paths = Config::get('view.paths');

            //Load file view
            $file = $paths[0] . DIRECTORY_SEPARATOR . Input::get('view') . '.blade.php';
            $html = file_get_contents($file);

            //Init crawler
            $crawler = new HtmlPageCrawler($html);

            //Set filter
            $filter = '#' . Input::get('id');

            //Edit node
            $crawler->filter($filter)->setAttribute('src', '/uploads/' . $filename);

            //write file
            file_put_contents($file, $crawler->saveHTML());

            return Redirect::back()->with('alert', 'Banner enviado com sucesso!');
        }
    }
}    