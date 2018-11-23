<?php

namespace UEGMobile\ArduinoOTAServerBundle\Entity;

/**
 * OTABinary
 */
class OTABinary
{
    
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $binaryName;

    /**
     * @var string
     */
    private $binaryVersion;

    /**
     * @var string
     */
    private $userAgent;

    /**
     * @var string
     */
    private $sdkVersion;

    /**
     * @var binary
     */
    private $binaryFile;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var OTAProgram
     */
    private $program;

    /**
     * Construct
     */
    public function __construct($program = null)
    {
        if(is_null($program)){
            $program = new Program();
        }
        $this->setProgram($program);
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set binaryName
     *
     * @param string $binaryName
     *
     * @return OTABinary
     */
    public function setBinaryName($binaryName)
    {
        $this->binaryName = $binaryName;

        return $this;
    }

    /**
     * Get binaryName
     *
     * @return string
     */
    public function getBinaryName()
    {
        return $this->binaryName;
    }

    /**
     * Set binaryVersion
     *
     * @param string $binaryVersion
     *
     * @return OTABinary
     */
    public function setBinaryVersion($binaryVersion)
    {
        $this->binaryVersion = $binaryVersion;

        return $this;
    }

    /**
     * Get binaryVersion
     *
     * @return string
     */
    public function getBinaryVersion()
    {
        return $this->binaryVersion;
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent
     *
     * @return OTABinary
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get userAgent
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set sdkVersion
     *
     * @param string $sdkVersion
     *
     * @return OTABinary
     */
    public function setSdkVersion($sdkVersion)
    {
        $this->sdkVersion = $sdkVersion;

        return $this;
    }

    /**
     * Get sdkVersion
     *
     * @return string
     */
    public function getSdkVersion()
    {
        return $this->sdkVersion;
    }

    /**
     * Set binaryFile
     *
     * @param binary $binaryFile
     *
     * @return OTABinary
     */
    public function setBinaryFile($binaryFile)
    {
        $this->binaryFile = $binaryFile;

        return $this;
    }

    /**
     * Get binaryFile
     *
     * @return binary
     */
    public function getBinaryFile()
    {
        return $this->binaryFile;
    }
    
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return OTABinary
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set program
     *
     * @param Program $program
     *
     * @return OTABinary
     */
    public function setProgram($program)
    {
        $this->program = $program;

        return $this;
    }

    /**
     * Get program
     *
     * @return Program
     */
    public function getProgram()
    {
        return $this->program;
    }

}

