<?php

namespace seositiframework;

/**
 * Interfaccia per il pattern DAO (Data Access Object).
 *
 * Definisce il contratto per tutte le classi che gestiscono
 * la persistenza degli oggetti nel database.
 *
 * @package SeoSitiFramework
 * @since   1.0
 */
interface InterfaceDAO {

    /**
     * Converte un oggetto in un array associativo per il database.
     *
     * @param MyObject $o Oggetto da convertire.
     * @return array Array chiave => valore dei campi.
     */
    public function getArray(MyObject $o): array;

    /**
     * Restituisce l'array dei formati per $wpdb->insert/update.
     *
     * @return array Array di formati ('%s', '%d', '%f').
     */
    public function getFormato(): array;

    /**
     * Converte un record del database in un oggetto MyObject.
     *
     * @param array $item Riga del database (array associativo).
     * @return MyObject
     */
    public function getObj(array $item): MyObject;

    /**
     * Salva un nuovo oggetto nel database.
     *
     * @param MyObject $o Oggetto da salvare.
     * @return bool|int ID del record inserito, o false in caso di errore.
     */
    public function save(MyObject $o): bool|int;

    /**
     * Recupera una lista di risultati dal database.
     *
     * @param array|null $where  Condizioni di filtro.
     * @param array|null $order  Ordinamento.
     * @return array|null Array di risultati o null.
     */
    public function getResults(?array $where = null, ?array $order = null): ?array;

    /**
     * Aggiorna un oggetto esistente nel database.
     *
     * @param MyObject $o Oggetto con i dati aggiornati.
     * @return int|bool|null Numero di righe aggiornate, false in caso di errore.
     */
    public function update(MyObject $o): int|bool|null;

    /**
     * Elimina un record dal database tramite il suo ID.
     *
     * @param int $ID Identificativo del record.
     * @return bool True se eliminato con successo.
     */
    public function deleteByID(int $ID): bool;

    /**
     * Verifica se un oggetto esiste già nel database.
     *
     * @param MyObject $o Oggetto da verificare.
     * @return bool True se esiste.
     */
    public function exists(MyObject $o): bool;

    /**
     * Esegue una ricerca personalizzata nel database.
     *
     * @param string $query Query SQL di ricerca.
     * @return array|null Risultati della ricerca.
     */
    public function search(string $query): ?array;

    /**
     * Recupera un singolo record tramite ID.
     *
     * @param int $ID Identificativo del record.
     * @return array|null Record trovato o null.
     */
    public function getResultByID(int $ID): ?array;

    /**
     * Converte un array di risultati del database in un array di oggetti.
     *
     * @param array $resultQuery Risultati grezzi della query.
     * @return array|null Array di oggetti MyObject.
     */
    public function getArrayResult(array $resultQuery): ?array;
}
