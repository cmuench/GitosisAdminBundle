<?php

namespace N98\GitosisAdminBundle\Twig\Extension;

class GitosisExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    protected $gitosisSshUser = '';

    /**
     * @var string
     */
    protected $gitosisHost = '';

    /**
     * @param string $gitosisSshUser
     * @param string $gitosisHost
     */
    public function __construct($gitosisSshUser, $gitosisHost)
    {
        $this->gitosisSshUser = $gitosisSshUser;
        $this->gitosisHost = $gitosisHost;
    }

    /**
     * @return array
     */
    public function getGlobals()
    {
        return array(
            'gitosis_sshUser' => $this->gitosisSshUser,
            'gitosis_host'    => $this->gitosisHost,
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gitosis';
    }
}