<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateComputerRequest;
use App\Http\Requests\UpdateComputerRequest;
use App\Models\ComputerPivot;
use App\Models\Content;
use Carbon\Carbon;


use App\Models\Store;
use App\Repositories\ComputerPivotRepository;
use Illuminate\Http\Request;
use Response;

class ComputerPivotController extends AppBaseController
{
  /** @var  StoreRepository */
  private $computerPivotRepository;

  public function __construct(ComputerPivotRepository $computerPivotRepo)
  {
    $this->computerPivotRepository = $computerPivotRepo;
  }
  public function index(Request $request)
  {

  }
  public function show($id)
  {

  }

  public function create()
  {

  }

  public function store(CreateComputerRequest $request)
  {

  }

  public function edit($id, $store_id)
  {

  }

  public function update($id, UpdateComputerRequest $request)
  {

  }
  public function destroy($id)
  {

  }

  public function getInfo($code, $pass)
  {
    $pivot = ComputerPivot::with(['onpivots','onpivots.computer','onpivots.computer.screens', 'onpivots.computer.screens', 'onpivots.computer.screens.playlist', 'onpivots.computer.screens.playlist.versionPlaylists', 'onpivots.computer.screens.playlist.versionPlaylists.versionPlaylistDetails','onpivots.computer.screens.playlist.versionPlaylists.versionPlaylistDetails.contentWithTrashed'])->where('code', $code)->where('pass', $pass)->first();
    if (isset($pivot)) {
      $jsonResponse = [];
      $jsonResponse['code'] = $pivot->code;
      foreach($pivot->onpivots as $key=>$onpivot){
				$jsonResponse['computers'][$key]['code'] = $onpivot->computer->code;
        foreach ($onpivot->computer->screens as $key2=>$screen) {
					$jsonResponse['computers'][$key]['screens'][$key2]['code'] = $screen->id;
          $jsonResponse['computers'][$key]['screens'][$key2]['name'] = $screen->name;
          $jsonResponse['computers'][$key]['screens'][$key2]['width'] = $screen->width;
          $jsonResponse['computers'][$key]['screens'][$key2]['height'] = $screen->height;
					$jsonResponse['computers'][$key]['screens'][$key2]['state'] = $screen->state;
          foreach ($screen->playlist->versionPlaylists as $versionPlaylist); {
            if ($versionPlaylist->state == 1) {
							$jsonResponse['computers'][$key]['screens'][$key2]['version'] = $versionPlaylist->version;
							$vPlaylistDetails = $versionPlaylist->versionPlaylistDetails;
              foreach ($vPlaylistDetails as $key3 => $vPlaylistDetail) {
								$jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$key3]['defOrder'] = $key3;
								$jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$key3]['originalID'] = $vPlaylistDetail->contentWithTrashed->id;
                $jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$key3]['name'] = $vPlaylistDetail->contentWithTrashed->name;
                $jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$key3]['width'] = $vPlaylistDetail->contentWithTrashed->width;
                $jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$key3]['height'] = $vPlaylistDetail->contentWithTrashed->height;
								$jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$key3]['deleted'] = empty($vPlaylistDetail->contentWithTrashed->deleted_at) ? null : Carbon::parse($vPlaylistDetail->contentWithTrashed->deleted_at)->format('d/m/Y H:i');
                $jsonResponse['computers'][$key]['screens'][$key2]['playlist'][$key3]['download'] = route('contents.download', $vPlaylistDetail->contentWithTrashed->id);
              }
            }
          }
        }
      }
			//return dump($jsonResponse);
       return response()->json($jsonResponse);
    }
		else {
      return abort(404);
    }
  }

}
