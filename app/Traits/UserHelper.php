<?php

namespace App\Traits;

use App\Models\Community;
use App\Models\CommunityInvitation;
use App\Models\CommunityUser;
use Carbon\Carbon;


class UserHelper
{
    public function isChAdmin()
    {
        return $this->ch_admin;
    }

    public function canAccessCommunity(Community $community)
    {

        return CommunityUser::where([['user_id', $this->id], ['community_id', $community->id]])->first();
    }

    public function allowAccessToCommunity(Community $community)
    {
        CommunityUser::create([
            'user_id' => $this->id,
            'community_id' => $community->id
        ]);
    }

    public function isCommunityAdmin($community_id)
    {
        $rel = CommunityUser::where([['user_id', $this->id], ['community_id', $community_id]])->first();
        if ($rel == null) {
            return false;
        }
        if ($rel->role_id == 1 || $this->ch_admin == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function makeCommunityAdmin(Community $community)
    {
        $rel = CommunityUser::where([['user_id', $this->id], ['community_id', $community->id]])->first();
        if ($rel == null) {
            CommunityUser::create([
                'user_id' => $this->id,
                'community_id' => $community->id,
                'role_id' => 1
            ]);
        } else {
            $rel->role_id = 1;
            $rel->save();
        }
    }

    public function acceptCommunityInvitation($invitation_id)
    {
        $inv = CommunityInvitation::findOrFail($invitation_id);
        $com = Community::find($inv->community_id);
        $this->allowAccessToCommunity($com);
        $inv->accepted = true;
        $inv->accepted_at = Carbon::now();
        $inv->save();
    }
}


?>
