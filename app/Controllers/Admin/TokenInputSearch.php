<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class TokenInputSearch extends BaseController
{
	function searchArticles($published = 1){

		$request = \Config\Services::request();
		$term = $request->getVar('q');
		$articleModel = model(Article::class);
		$results = $articleModel->tokeniputSearch(array('term'=>$term,'published'=>$published));
		echo json_encode($results);
		return; die();
	}

	function searchArticleCategories(){

		$request = \Config\Services::request();
		$term = $request->getVar('q');
		$categoryModel = model(ArticleCategory::class);
		$results = $categoryModel->tokeniputSearch(array('term'=>$term));
		echo json_encode($results);
		return; die();
	}


	function searchPolls(){

		$request = \Config\Services::request();
		$term = $request->getVar('q');
		$dbModel = model(Poll::class);
		$results = $dbModel->tokeniputSearch(array('term'=>$term));
		echo json_encode($results);
		return; die();
	}
	
	
}
