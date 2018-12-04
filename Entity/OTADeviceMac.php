<?php

namespace UEGMobile\ArduinoOTAServerBundle\Entity;

/**
 * OTADeviceMac
 */
class OTADeviceMac
{
    const MODE_ALPHA = "ALPHA";
    const MODE_BETA = "BETA";
    const MODE_PROD = "PROD";

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $mac;

    /**
     * @var \UEGMobile\ArduinoOTAServerBundle\Entity\OTAProgram
     */
    private $program;

    /**
     * @var string
     */
    private $mode;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var bool
     */
    private $active;

    /**
     * Construct
     */
    public function __construct($mode = OTADeviceMac::MODE_ALPHA)
    {
        $this->mode = $mode;
        $this->active = false;
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt($this->getCreatedAt());
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
     * Set mac
     *
     * @param string $mac
     *
     * @return OTADeviceMac
     */
    public function setMac($mac)
    {
        $this->mac = $mac;

        return $this;
    }

    /**
     * Get mac
     *
     * @return string
     */
    public function getMac()
    {
        return $this->mac;
    }


    /**
     * Set mode
     *
     * @param string $mode
     *
     * @return OTADeviceMac
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Get mode
     *
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set program
     *
     * @param \UEGMobile\ArduinoOTAServerBundle\Entity\OTAProgram $program
     *
     * @return OTAProgram
     */
    public function setProgram($program)
    {
        $this->program = $program;

        return $this;
    }

    /**
     * Get program
     *
     * @return \UEGMobile\ArduinoOTAServerBundle\Entity\OTAProgram
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return OTADeviceMac
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
     * @return OTADeviceMac
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

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

}

