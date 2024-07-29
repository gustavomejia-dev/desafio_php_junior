<?php
namespace App\traits;
trait UserTrait {
    function validateGroupUser ($groupId) {
        $permissionGroup = [1];
        if(!in_array($groupId, $permissionGroup)) {
            return true;
        }
        return false;
    }
}