<?php

namespace seositiframework;

/**
 * ObjectDAO è la classe astratta base per il pattern Data Access Object.
 *
 * Gestisce tutte le operazioni CRUD sul database tramite $wpdb,
 * utilizzando prepared statements per prevenire SQL Injection.
 *
 * Le classi concrete devono implementare updateToObj() e newObj()
 * per definire la mappatura tra record DB e oggetti del dominio.
 *
 * @package SeoSitiFramework
 * @since   2.0
 */
abstract class ObjectDAO {

    /** @var \wpdb Istanza del database WordPress */
    private $wpdb;

    /** @var string Nome completo della tabella (con prefisso) */
    private string $table;

    /**
     * Converte i dati di un form/request in proprietà dell'oggetto.
     *
     * @param MyObject $o Oggetto da aggiornare.
     * @return void
     */
    abstract protected function updateToObj(MyObject $o): void;

    /**
     * Crea e restituisce una nuova istanza dell'oggetto concreto.
     *
     * @return MyObject Nuova istanza.
     */
    abstract protected function newObj(): MyObject;

    /**
     * Costruttore: inizializza la connessione al database e il nome della tabella.
     *
     * @param string $table Nome della tabella (senza prefisso).
     */
    public function __construct(string $table) {
        global $wpdb;
        $this->wpdb = $wpdb;

        // Standardizzato con il prefisso di sistema WordPress
        $this->table = $wpdb->prefix . $table;
    }

    /**
     * Salva un nuovo oggetto nel database.
     *
     * Utilizza $wpdb->insert() che gestisce internamente i prepared statements.
     *
     * @param array $campi   Array associativo campo => valore.
     * @param array $formato Array di formati ('%s', '%d', '%f').
     * @return int|false ID del record inserito, o false in caso di errore.
     */
    protected function saveObject(array $campi, array $formato): int|false {
        try {
            $result = $this->wpdb->insert($this->table, $campi, $formato);

            if ($result === false) {
                error_log('[SSF] Errore insert su ' . $this->table . ': ' . $this->wpdb->last_error);
                return false;
            }

            return $this->wpdb->insert_id;
        } catch (\Exception $ex) {
            error_log('[SSF] Eccezione in saveObject: ' . $ex->getMessage());
            return false;
        }
    }

    /**
     * Recupera oggetti dal database con paginazione opzionale.
     *
     * Wrapper per getObjects() che aggiunge supporto all'offset.
     *
     * @param array|null $where  Condizioni WHERE.
     * @param array|null $order  Ordinamento.
     * @param int|null   $offset Offset per la paginazione.
     * @return array|null Risultati o null.
     */
    protected function getObjectsDAO(?array $where = null, ?array $order = null, ?int $offset = null): ?array {
        if ($offset !== null) {
            return $this->getObjects(null, $where, $order, SSF_ELEMENTI_PER_PAGINA, $offset);
        }
        return $this->getObjects(null, $where, $order);
    }

    /**
     * Costruisce e esegue una query SELECT parametrizzata.
     *
     * Utilizza $wpdb->prepare() per tutti i valori utente,
     * prevenendo SQL Injection.
     *
     * @param array|null $select Campi da selezionare (null = tutti).
     * @param array|null $where  Condizioni WHERE. Ogni elemento è un array con:
     *                           - 'campo'     => nome colonna
     *                           - 'valore'    => valore da confrontare
     *                           - 'formato'   => 'INT' | 'NUM' | 'TEXT' (default)
     *                           - 'operatore' => (opzionale) '=', '<=', '>=', 'LIKE'
     * @param array|null $order  Ordinamento. Ogni elemento è un array con:
     *                           - 'campo'  => nome colonna
     *                           - 'ordine' => 'ASC' | 'DESC'
     * @param int|null   $limit  Numero massimo di risultati.
     * @param int|null   $offset Offset iniziale.
     * @return array|null Array associativo dei risultati, o null in caso di errore.
     */
    protected function getObjects(
        ?array $select = null,
        ?array $where = null,
        ?array $order = null,
        ?int $limit = null,
        ?int $offset = null
    ): ?array {

        $prepare_values = array();

        // SELECT clause
        $query = "SELECT";
        if ($select === null) {
            $query .= " *";
        } else {
            // I nomi delle colonne vengono validati come identificatori
            $safe_columns = array();
            foreach ($select as $col) {
                $safe_columns[] = $this->sanitizeIdentifier($col);
            }
            $query .= " " . implode(", ", $safe_columns);
        }

        // FROM clause
        $query .= " FROM " . $this->table;

        // WHERE clause con prepared statements
        if ($where !== null) {
            $query .= " WHERE 1=1";
            foreach ($where as $item) {
                $campo = $this->sanitizeIdentifier($item['campo']);
                $valore = $item['valore'];
                $formato = $item['formato'] ?? 'TEXT';
                $operatore = $item['operatore'] ?? '=';

                // Whitelist degli operatori consentiti
                $operatori_consentiti = array('=', '<=', '>=', '<', '>', '!=', 'LIKE');
                if (! in_array($operatore, $operatori_consentiti, true)) {
                    $operatore = '=';
                }

                // Ignora valori vuoti per campi con operatore personalizzato
                if (isset($item['operatore']) && $valore === '') {
                    continue;
                }

                if ($formato === 'INT' || $formato === 'NUM') {
                    $query .= " AND {$campo} {$operatore} %d";
                    $prepare_values[] = intval($valore);
                } else {
                    if ($operatore === 'LIKE') {
                        $query .= " AND {$campo} LIKE %s";
                        $prepare_values[] = '%' . $this->wpdb->esc_like($valore) . '%';
                    } else {
                        $query .= " AND {$campo} {$operatore} %s";
                        $prepare_values[] = $valore;
                    }
                }
            }
        }

        // ORDER BY clause
        if ($order !== null) {
            $order_parts = array();
            foreach ($order as $item) {
                $campo = $this->sanitizeIdentifier($item['campo']);
                $ordine = (strtoupper($item['ordine']) === 'DESC') ? 'DESC' : 'ASC';
                $order_parts[] = "{$campo} {$ordine}";
            }
            $query .= " ORDER BY " . implode(", ", $order_parts);
        }

        // LIMIT & OFFSET
        if ($limit !== null && $offset !== null) {
            $query .= " LIMIT %d, %d";
            $prepare_values[] = $offset;
            $prepare_values[] = $limit;
        }

        try {
            // Usa prepare() solo se ci sono parametri da preparare
            if (! empty($prepare_values)) {
                // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- La query è costruita con placeholder %s/%d
                $prepared_query = $this->wpdb->prepare($query, $prepare_values);
            } else {
                $prepared_query = $query;
            }

            return $this->wpdb->get_results($prepared_query, ARRAY_A);
        } catch (\Exception $ex) {
            error_log('[SSF] Errore in getObjects: ' . $ex->getMessage());
            return null;
        }
    }

    /**
     * Esegue una query di ricerca personalizzata sul database.
     *
     * @param string $query Query SQL già preparata.
     * @return array|null Risultati o null in caso di errore.
     */
    protected function searchObjects(string $query): ?array {
        try {
            return $this->wpdb->get_results($query, ARRAY_A);
        } catch (\Exception $ex) {
            error_log('[SSF] Errore in searchObjects: ' . $ex->getMessage());
            return null;
        }
    }

    /**
     * Elimina un record dal database.
     *
     * @param array $array Condizioni WHERE per l'eliminazione (campo => valore).
     * @return bool True se eliminato con successo.
     */
    protected function deleteObject(array $array): bool {
        try {
            $result = $this->wpdb->delete($this->table, $array);
            return ($result !== false);
        } catch (\Exception $ex) {
            error_log('[SSF] Errore in deleteObject: ' . $ex->getMessage());
            return false;
        }
    }

    /**
     * Elimina un record dal database tramite il suo ID.
     *
     * @param int $ID Identificativo del record.
     * @return bool True se eliminato con successo.
     */
    protected function deleteObjectByID(int $ID): bool {
        return $this->deleteObject(array(SSF_DBT_ID => $ID));
    }

    /**
     * Aggiorna un record nel database.
     *
     * Utilizza $wpdb->update() che gestisce internamente i prepared statements.
     *
     * @param array $update       Array campo => nuovo valore.
     * @param array $formatUpdate Array di formati per i campi da aggiornare.
     * @param array $where        Condizioni WHERE per individuare il record.
     * @param array $formatWhere  Array di formati per le condizioni WHERE.
     * @return int|false Numero di righe aggiornate, o false in caso di errore.
     */
    protected function updateObject(array $update, array $formatUpdate, array $where, array $formatWhere): int|false {
        try {
            $result = $this->wpdb->update(
                $this->table,
                $update,
                $where,
                $formatUpdate,
                $formatWhere
            );

            if ($result === false) {
                error_log('[SSF] Errore update su ' . $this->table . ': ' . $this->wpdb->last_error);
            }

            return $result;
        } catch (\Exception $ex) {
            error_log('[SSF] Eccezione in updateObject: ' . $ex->getMessage());
            return false;
        }
    }

    /**
     * Restituisce il nome completo della tabella (con prefisso).
     *
     * @return string
     */
    protected function getTable(): string {
        return $this->table;
    }

    /**
     * Restituisce l'istanza di $wpdb per query avanzate nelle classi figlie.
     *
     * @return \wpdb
     */
    protected function getWpdb(): \wpdb {
        return $this->wpdb;
    }

    /**
     * Verifica se un record con il dato ID esiste nella tabella.
     *
     * @param int $ID Identificativo del record.
     * @return bool True se il record esiste.
     */
    protected function existsID(int $ID): bool {
        $where = array(
            array(
                'campo'   => SSF_DBT_ID,
                'valore'  => $ID,
                'formato' => 'INT',
            ),
        );

        $result = $this->getObjects(null, $where);
        return ($result !== null && count($result) > 0);
    }

    /**
     * Restituisce un singolo record dal database tramite il suo ID.
     *
     * @param int $ID Identificativo del record.
     * @return array|null Array associativo del record, o null se non trovato.
     */
    public function getResultByID(int $ID): ?array {
        $where = array(
            array(
                'campo'   => SSF_DBT_ID,
                'valore'  => $ID,
                'formato' => 'INT',
            ),
        );

        $temp = $this->getObjects(null, $where);

        if ($temp !== null && count($temp) > 0) {
            return $temp[0];
        }
        return null;
    }

    /**
     * Sanitizza un identificatore SQL (nome colonna/tabella).
     *
     * Consente solo caratteri alfanumerici, underscore e punto (per alias).
     * Previene SQL injection nei nomi di colonna.
     *
     * @param string $identifier Nome della colonna o tabella.
     * @return string Identificatore sanitizzato.
     */
    private function sanitizeIdentifier(string $identifier): string {
        return preg_replace('/[^a-zA-Z0-9_.]/', '', $identifier);
    }
}