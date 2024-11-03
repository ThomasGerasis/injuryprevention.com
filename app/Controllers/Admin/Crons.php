<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\EditingLock;
use App\Models\Article;	

class Crons extends BaseController
{
    public function cleanLocks()
	{
		//run every 1m
        $editingLock = model(EditingLock::class);
		$editingLock->cleanLocks();
    }

    public function publishContent()
	{
		//run every 5m
        $articleModel = model(Article::class);
		$updateIds = $articleModel->cronPublish();
        foreach($updateIds as $updateId){
			rebuildCache('article', $updateId, 'publish');
		}
        
		rebuildCache('permalinks', 1, 'update');
    }

}
		