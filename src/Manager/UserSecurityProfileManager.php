<?php
/**
 * Created by PhpStorm.
 * User: chehimi
 * Date: 03/03/22
 * Time: 01:47 م
 */

namespace App\Manager;


use App\Entity\QaOrganization;
use App\Entity\QaUser;
use App\Model\UserProfile;

class UserSecurityProfileManager
{

    public function createOrgProfile(QaUser $user, QaOrganization $organization)
    {
        return new UserProfile($user, $organization);
    }
}
