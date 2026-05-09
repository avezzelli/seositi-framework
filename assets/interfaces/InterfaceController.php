<?php

namespace seositiframework;

/**
 * Interfaccia per il pattern Controller.
 *
 * Definisce il contratto CRUD per la logica di business
 * che orchestra DAO e View.
 *
 * @package SeoSitiFramework
 * @since   1.0
 */
interface InterfaceController {

    /**
     * Salva un nuovo oggetto.
     *
     * @param MyObject $o Oggetto da salvare.
     * @return bool True se il salvataggio è riuscito.
     */
    public function save(MyObject $o): bool;

    /**
     * Aggiorna un oggetto esistente.
     *
     * @param MyObject $o Oggetto con i dati aggiornati.
     * @return bool True se l'aggiornamento è riuscito.
     */
    public function update(MyObject $o): bool;

    /**
     * Elimina un oggetto tramite il suo ID.
     *
     * @param int $ID Identificativo dell'oggetto.
     * @return bool True se l'eliminazione è riuscita.
     */
    public function delete(int $ID): bool;

    /**
     * Recupera un oggetto tramite il suo ID.
     *
     * @param int $ID Identificativo dell'oggetto.
     * @return MyObject|null Oggetto trovato o null.
     */
    public function get(int $ID): ?MyObject;
}
