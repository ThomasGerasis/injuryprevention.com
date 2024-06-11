<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Poll;
use App\Models\Mission;

class Crons extends BaseController
{

    public function cleanLocks()
	{
		//run every 1m
        $editingLock = model(EditingLock::class);
		$editingLock->cleanLocks();
    }

	public function pollActivation()
	{
		//run every 1m
		$pollModel = model(Poll::class);
        $caches = $pollModel->cronActivate();
        foreach($caches as $cache){
			rebuildCache($cache['model'], $cache['model_id'], $cache['type']);
		}
    }


    public function missionActivation()
    {
        //run every 1m
        $missionModel = model(Mission::class);
        $caches = $missionModel->cronActivate();
        foreach($caches as $cache){
            rebuildCache($cache['model'], $cache['model_id'], $cache['type']);
        }
    }

    public function publishContent()
	{
		//run every 5m
        $banners = model(BannerSchedule::class);
		$updatePlaces = $banners->publish();
        foreach($updatePlaces as $updatePlace){
			rebuildCache('bannerSchedule', $updatePlace, 'update');
		}

        $articleModel = model(Article::class);
		$updateIds = $articleModel->cronPublish();
        foreach($updateIds as $updateId){
			rebuildCache('article', $updateId, 'publish');
		}

		$pollModel = model(Poll::class);
		$updateCaches = $pollModel->cronPublish();
        foreach($updateCaches['updateIds'] as $updateId){
			rebuildCache('poll', $updateId, 'publish');
		}
		foreach($updateCaches['updateCategoryIds'] as $updateCategoryId){
			rebuildCache('pollCategory', $updateCategoryId, 'update');
			rebuildCache('pollCategory', $updateCategoryId, 'feed');
		}

        $missionModel = model(Mission::class);
        $updateMissionCaches = $missionModel->cronPublish();
        foreach($updateMissionCaches['updateIds'] as $updateId){
            rebuildCache('mission', $updateId, 'publish');
        }
        foreach($updateMissionCaches['updateCategoryIds'] as $updateCategoryId){
            rebuildCache('missionCategory', $updateCategoryId, 'update');
            rebuildCache('missionCategory', $updateCategoryId, 'feed');
        }
        rebuildCache('generalMissionCategory', '1', 'feed');
        
		rebuildCache('permalinks', 1, 'update');
    }

	public function updateSchedule()
	{
		//run every 00:01 on Mondays
		//moves all schedule events from last week to next week
		log_message('error','updateSchedule');
        $dbModel = model(ChannelSchedule::class);
		$dbModel->updateWeekSchedule();
    }
}
		