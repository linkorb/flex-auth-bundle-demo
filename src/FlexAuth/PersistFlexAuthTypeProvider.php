<?php

namespace App\FlexAuth;

use App\Entity\User;
use FlexAuth\FlexAuthTypeProviderFactory;
use FlexAuth\FlexAuthTypeProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PersistFlexAuthTypeProvider
 * @author Aleksandr Arofikin <sashaaro@gmail.com>
 */
class PersistFlexAuthTypeProvider implements FlexAuthTypeProviderInterface
{
    public $types = [
        'memory' => 'memory?users=alice:4l1c3:ROLE_ADMIN;ROLE_EXAMPLE,bob:b0b:ROLE_EXAMPLE)',
        'entity' => 'entity?class='.User::class.'&property=username',
    ];

    private $filePath;

    public function __construct(ContainerInterface $container)
    {
        $this->filePath = $container->getParameter('kernel.cache_dir').'/flex_type';

        $certFolderPath = $container->getParameter('kernel.project_dir').'/config/cert';

        $this->types['jwt'] = 'jwt?private_key=@'. $certFolderPath .'/jwtRS256.key&public_key=@'. $certFolderPath .'/jwtRS256.key.pub&algo=RS256';
    }

    public function provide(): array
    {
        $typeKey = null;
        if (is_file($this->filePath)) {
            $typeKey = file_get_contents($this->filePath);
        }

        if (!$typeKey) {
            $typeKey = 'memory|argon2i';
        }

        [$typeKey, $encoder] = explode('|', $typeKey);

        $params = FlexAuthTypeProviderFactory::resolveParamsFromLine($this->types[$typeKey]);
        $params['encoder'] = $encoder;

        return $params;
    }

    public function set($typeKey, $encoder = null)
    {
        if (!in_array($typeKey, array_keys($this->types))) {
            throw new \InvalidArgumentException(sprintf('No flex type %s', $typeKey));
        }
        file_put_contents($this->filePath, $typeKey.'|'.$encoder);
    }
}
