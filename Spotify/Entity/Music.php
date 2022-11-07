<?php

namespace App\Entity;

class Music extends Model
{
    public ?string $table = 'music';
    public string|null $id;
    public $name;
    public $img;

    /**
     * @param string|null $id
     * @param $name
     * @param $img
     */
    public function __construct(?string $id, $name, $img)
    {
        $this->id = $id;
        $this->name = $name;
        $this->img = $img;
    }

    public function display(): ?string
    {
        return "L'id de la musique ".$this->id . "<br>" . "<img src=" . $this->img . " >" . "<br> Nom de la music : " . $this->name . "<br>";
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



}