<?php
/**
 * - OAuthUserProvider.php
 *
 * @author chris
 * @created 13/04/15 14:46
 */

namespace Kurl\Silex\Auth\Security\User\Provider;

use Doctrine\DBAL\Connection;
use Gigablah\Silex\OAuth\Security\Authentication\Token\OAuthTokenInterface;
use Gigablah\Silex\OAuth\Security\User\Provider\OAuthUserProviderInterface;
use Gigablah\Silex\OAuth\Security\User\Provider\UserInterface;
use Symfony\Component\DependencyInjection\Tests\Compiler\CheckExceptionOnInvalidReferenceBehaviorPassTest;

class OAuthUserProvider implements OAuthUserProviderInterface
{
    /**
     * The db connection.
     *
     * @var Connection
     */
    private $db;

    /**
     * Creates a new url shortener service.
     *
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * Loads a user based on OAuth credentials.
     *
     * @param OAuthTokenInterface $token
     *
     * @return UserInterface|null
     */
    public function loadUserByOAuthCredentials(OAuthTokenInterface $token)
    {
        $user = $this->findUserByUid($token->getService(), $token->getUid());
    }

    protected function findUserByUid($service, $uid)
    {
        $query = <<<EOT
select u.* from shorty_user u
inner join shorty_user_oauth_service o on o.user_id = u.id
inner join shorty_oauth_service s on s.id = o.service_id
where u.uid = :uid and s.name = :service_name
EOT;

        return $this->db->fetchAssoc($query, array('uid' => $uid, 'service_name' => $service));
    }
}