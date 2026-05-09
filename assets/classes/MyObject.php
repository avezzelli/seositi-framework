<?php

namespace seositiframework;

/**
 * MyObject è la classe base model di SeoSiti Framework.
 *
 * Definisce il costruttore e la proprietà ID condivisa da tutti
 * i model del framework. Ogni entità del gestionale estende questa classe.
 *
 * @package SeoSitiFramework
 * @since   1.0
 */
class MyObject {

    /** @var int Identificativo univoco dell'oggetto nel database */
    private int $ID;

    /**
     * Inizializza l'oggetto con ID a 0 (non ancora persistito).
     */
    public function __construct() {
        $this->ID = 0;
    }

    /**
     * Restituisce l'ID dell'oggetto.
     *
     * @return int
     */
    public function getID(): int {
        return $this->ID;
    }

    /**
     * Imposta l'ID dell'oggetto.
     *
     * @param int $ID Identificativo univoco.
     * @return void
     */
    public function setID(int $ID): void {
        $this->ID = $ID;
    }
}
