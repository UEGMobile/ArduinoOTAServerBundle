<?php

namespace UEGMobile\ArduinoOTAServerBundle\Entity;

/**
 * OTADeviceMac
 */
class OTADeviceMac
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $mac;

    /**
     * @var \UEGMobile\ArduinoOTAServerBundle\Entity\OTABinary
     */
    private $otaBinary;

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
        $this->setUpdatedAt($this->createdAt());
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
     * Set otaBinary
     *
     * @param \UEGMobile\ArduinoOTAServerBundle\Entity\OTABinary $otaBinary
     *
     * @return OTADeviceMac
     */
    public function setOtaBinary($otaBinary)
    {
        $this->otaBinary = $otaBinary;

        return $this;
    }

    /**
     * Get otaBinary
     *
     * @return \UEGMobile\ArduinoOTAServerBundle\Entity\OTABinary
     */
    public function getOtaBinary()
    {
        return $this->otaBinary;
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
    public function setUpdateAt($updatedAt)
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

