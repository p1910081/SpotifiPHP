<?php

namespace App\Entity;

use App\Core\Db;

class Model extends Db
{
    // Table de la base de données
    protected ?string $table;

    // Instance de Db
    private $db;


    public function findAll(): bool|array
    {
        $query = $this->requete('SELECT * FROM ' . $this->table);
        return $query->fetchAll();
    }

    public function findBy(array $criteria): bool|array
    {
        $fields = [];
        $values = [];

        // On boucle pour éclater le tableau
        foreach ($criteria as $field => $value) {
            // SELECT * FROM annonces WHERE actif = ? AND signale = 0
            // bindValue(1, valeur)
            $fields[] = "$field = ?";
            $values[] = $value;
        }

        // On transforme le tableau "champs" en une chaine de caractères
        $fieldsList = implode(' AND ', $fields);

        // On exécute la requête
        return $this->requete('SELECT * FROM ' . $this->table . ' WHERE ' . $fieldsList, $values)->fetchAll();
    }

    public function find(int $id)
    {
        return $this->requete("SELECT * FROM {$this->table} WHERE id = $id")->fetch();
    }

    public function create(): bool|\PDOStatement
    {
        $fields = [];
        $inter = [];
        $values = [];

        // On boucle pour éclater le tableau
        foreach ($this as $field => $value) {
            // INSERT INTO annonces (titre, description, actif) VALUES (?, ?, ?)
            if ($value !== null && $field != 'db' && $field != 'table') {
                $fields[] = $field;
                $inter[] = "?";
                $values[] = $value;
            }
        }

        // On transforme le tableau "champs" en une chaine de caractères
        $fieldsList = implode(', ', $fields);
        $interList = implode(', ', $inter);

        // On exécute la requête
        return $this->requete('INSERT INTO ' . $this->table . ' (' . $fieldsList . ')VALUES(' . $interList . ')', $values);
    }

    public function update(): bool|\PDOStatement
    {
        $fields = [];
        $values = [];

        // On boucle pour éclater le tableau
        foreach ($this as $field => $value) {
            // UPDATE annonces SET titre = ?, description = ?, actif = ? WHERE id= ?
            if ($value !== null && $field != 'db' && $field != 'table') {
                $fields[] = "$field = ?";
                $values[] = $value;
            }
        }
        $values[] = $this->id;

        // On transforme le tableau "champs" en une chaine de caractères
        $fieldsList = implode(', ', $fields);

        // On exécute la requête
        return $this->requete('UPDATE ' . $this->table . ' SET ' . $fieldsList . ' WHERE id = ?', $values);
    }

    public function delete(int $id): bool|\PDOStatement
    {
        return $this->requete("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }


    public function requete(string $sql, array $attributes = null)
    {
        // On récupère l'instance de Db
        $this->db = Db::getInstance();

        // On vérifie si on a des attributs
        if ($attributes !== null) {
            // Requête préparée
            $query = $this->db->prepare($sql);
            $query->execute($attributes);
            return $query;
        } else {
            // Requête simple
            return $this->db->query($sql);
        }
    }


    public function hydrate($datas)
    {
        foreach ($datas as $key => $value) {
            // On récupère le nom du setter correspondant à la clé (key)
            // titre -> setTitre
            $setter = 'set' . ucfirst($key);

            // On vérifie si le setter existe
            if (method_exists($this, $setter)) {
                // On appelle le setter
                $this->$setter($value);
            }
        }
        return $this;
    }
}
