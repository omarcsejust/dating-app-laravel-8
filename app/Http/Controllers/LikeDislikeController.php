<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LikeDislike;
use Carbon\Carbon;
use function PHPSTORM_META\type;

class LikeDislikeController extends Controller
{
    /**
     * like dislike logic goes here
     * @param Request $request
     * @return false|string
     */
    public function postLikeUserProfile(Request $request){
        $logged_use_id = session('id');
        $liked_profile_id = $request->profile_id;
        $like_dislike_id = $request->like_dislike_id;
        $like_status = $request->like_status;

        if ($like_status == null){
            //first time like request, check the combination exist or not, add new record to like-dislike entity
            $result = LikeDislike::where(['user_id'=>$logged_use_id, 'profile_id'=>$liked_profile_id])->first();
            if (empty($result)){
                LikeDislike::insert([
                    'user_id' => $logged_use_id,
                    'profile_id' => $liked_profile_id,
                    'like_dislike_status' => true,
                    'created_at' => Carbon::now()
                ]);
                $is_mutual = $this->checkMutualLike($liked_profile_id);
                return json_encode(['status'=>200, 'message'=>'Record added successfully', 'like_status'=>true, 'is_mutual' => $is_mutual]);
            }

        }else if ($like_status === 'true' || $like_status === 'false'){
            //dislike or like request, update like-dislike entity
            $status = $like_status === 'true' ? false : true;
            $is_mutual = false;

            LikeDislike::findOrFail($like_dislike_id)->update([
                'like_dislike_status' => $status,
                'updated_at' => Carbon::now()
            ]);

            // if User A like B, then check B previously liked A or not
            // (if dislike request comes then no chance to be a mutual like)
            if ($status)
                $is_mutual = $this->checkMutualLike($liked_profile_id);

            return json_encode(['status'=>200, 'message' => 'Updated Successfully.', 'like_status'=>$status, 'is_mutual' => $is_mutual]);
        }
    }

    /**
     * check mutual like exist or not for a like request
     * @param $profile_id
     * @return bool
     */
    private function checkMutualLike($profile_id){
        $is_mutual = false;
        if (!empty($profile_id)){
            $matchThese = ['user_id' => $profile_id, 'profile_id' => session('id'), 'like_dislike_status' => true];
            $result = LikeDislike::where($matchThese)->first();
            if (isset($result))
                $is_mutual = true;
        }
        return $is_mutual;
    }
}
