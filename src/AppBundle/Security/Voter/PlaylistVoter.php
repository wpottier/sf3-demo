<?php

namespace AppBundle\Security\Voter;

use AppBundle\Entity\Playlist;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PlaylistVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['ROLE_VIEW', 'ROLE_EDIT']) && $subject instanceof Playlist;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var $subject Playlist */

        switch ($attribute) {
            case 'ROLE_VIEW':
                return $subject->isPublic() || (
                    $token->getUser() instanceof User &&
                    $subject->getOwner()->getId() == $token->getUser()->getId()
                );
            case 'ROLE_EDIT':
                return $token->getUser() instanceof User &&
                    $subject->getOwner()->getId() == $token->getUser()->getId();
            default:
                return false;
        }
    }
}