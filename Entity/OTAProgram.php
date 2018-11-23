<?php

namespace UEGMobile\ArduinoOTAServerBundle\Entity;

/**
 * OTAProgram
 */
class OTAProgram
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $programName;

    /**
     * @var OTABinary
     */
    private $binaryAlpha;

    /**
     * @var OTABinary
     */
    private $binaryBeta;

    /**
     * @var OTABinary
     */
    private $binaryProd;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
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
     * @return OTAProgram
     */
    public function setBinaryAlpha($binaryAlpha)
    {
        $this->binaryAlpha = $binaryAlpha;

        return $this;
    }

    /**
     * @return OTABinary
     */
    public function getBinaryAlpha()
    {
        return $this->binaryAlpha;
    }

        /**
     * @return OTAProgram
     */
    public function setBinaryBeta($binaryBeta)
    {
        $this->binaryBeta = $binaryBeta;

        return $this;
    }

    /**
     * @return OTABinary
     */
    public function getBinaryBeta()
    {
        return $this->binaryBeta;
    }

        /**
     * @return OTAProgram
     */
    public function setBinaryProd($binaryProd)
    {
        $this->binaryProd = $binaryProd;

        return $this;
    }

    /**
     * @return OTABinary
     */
    public function getBinaryProd()
    {
        return $this->binaryProd;
    }


    /**
     * @return OTABinary
     */
    public function setProgramName($programName)
    {
        $this->programName = $programName;

        return $this;
    }

    /**
     * @return string
     */
    public function getProgramName()
    {
        return $this->programName;
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return OTABinary
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

}

