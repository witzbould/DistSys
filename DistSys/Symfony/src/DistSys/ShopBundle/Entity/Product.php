<?php

namespace DistSys\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 */
class Product
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var float
     */
    private $price;

    /**
     * @var integer
     */
    private $stock;

    /**
     * @var boolean
     */
    private $status;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $galleryItems;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $attributesToProducts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->galleryItems = new \Doctrine\Common\Collections\ArrayCollection();
        $this->attributesToProducts = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     * @return Product
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
    
        return $this;
    }

    /**
     * Get stock
     *
     * @return integer 
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Product
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add galleryItems
     *
     * @param \DistSys\ShopBundle\Entity\GalleryItem $galleryItems
     * @return Product
     */
    public function addGalleryItem(\DistSys\ShopBundle\Entity\GalleryItem $galleryItems)
    {
        $this->galleryItems[] = $galleryItems;
    
        return $this;
    }

    /**
     * Remove galleryItems
     *
     * @param \DistSys\ShopBundle\Entity\GalleryItem $galleryItems
     */
    public function removeGalleryItem(\DistSys\ShopBundle\Entity\GalleryItem $galleryItems)
    {
        $this->galleryItems->removeElement($galleryItems);
    }

    /**
     * Get galleryItems
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGalleryItems()
    {
        return $this->galleryItems;
    }

    /**
     * Add attributesToProducts
     *
     * @param \DistSys\ShopBundle\Entity\Attribute $attributesToProducts
     * @return Product
     */
    public function addAttributesToProduct(\DistSys\ShopBundle\Entity\Attribute $attributesToProducts)
    {
        $this->attributesToProducts[] = $attributesToProducts;
    
        return $this;
    }

    /**
     * Remove attributesToProducts
     *
     * @param \DistSys\ShopBundle\Entity\Attribute $attributesToProducts
     */
    public function removeAttributesToProduct(\DistSys\ShopBundle\Entity\Attribute $attributesToProducts)
    {
        $this->attributesToProducts->removeElement($attributesToProducts);
    }

    /**
     * Get attributesToProducts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAttributesToProducts()
    {
        return $this->attributesToProducts;
    }
}