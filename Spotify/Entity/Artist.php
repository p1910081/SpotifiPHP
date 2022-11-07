<?php

namespace App\Entity;

class Artist{

    public string|null $id;
    public $name;
    public $img;
    public int|null $followers;
    public $link;

    public function __construct( $id, $name, $img, $followers, $link) {
        $this->id = $id;
        $this->name = $name;
        $this->img = $img;
        $this->followers = $followers;
        $this->link = $link;
    }

    public function display(): ?string
    {
        return $this->id."<br>"."<img src=".$this->img." >"."<br>".$this->name.$this->followers."<br>";
    }


    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @param mixed $img
     */
    public function setImg($img): void
    {
        $this->img = $img;
    }

    /**
     * @return int|null
     */
    public function getFollowers(): ?int
    {
        return $this->followers;
    }

    /**
     * @param int|null $followers
     */
    public function setFollowers(?int $followers): void
    {
        $this->followers = $followers;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link): void
    {
        $this->link = $link;
    }




}
