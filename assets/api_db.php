<?php

namespace seositiframework;

/**
 * API per la gestione dello schema del database.
 *
 * Fornisce funzioni per creare e rimuovere tabelle personalizzate
 * nel database WordPress, utilizzate dai plugin child.
 *
 * @package SeoSitiFramework
 * @since   2.0
 */

/**
 * Crea una tabella personalizzata nel database WordPress.
 *
 * Utilizza dbDelta() per la creazione compatibile con gli aggiornamenti.
 * NON sovrascrive $wpdb->prefix: usa una variabile locale con DB_PREFIX.
 *
 * @param string     $tabella Nome della tabella (senza prefisso).
 * @param array      $param   Array di definizioni colonne, ognuna con 'nome', 'tipo', e opzionalmente 'null'.
 * @param array|null $fks     Array opzionale di foreign keys, ognuna con 'key1' e 'tabella'.
 * @return bool True se la tabella è stata creata con successo.
 */
function ssf_crea_tabella(string $tabella, array $param, ?array $fks = null): bool {

    global $wpdb;

    $prefix = $wpdb->prefix;
    $table_name = $prefix . $tabella;

    $charset_collate = '';
    if (! empty($wpdb->charset)) {
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
    }
    if (! empty($wpdb->collate)) {
        $charset_collate .= " COLLATE {$wpdb->collate}";
    }

    // Costruzione della query CREATE TABLE
    $query = "CREATE TABLE IF NOT EXISTS {$table_name} (";
    $query .= SSF_DBT_ID . " INT NOT NULL AUTO_INCREMENT PRIMARY KEY";

    foreach ($param as $p) {
        $query .= ", " . $p['nome'] . " " . $p['tipo'];
        if (isset($p['null'])) {
            $query .= " " . $p['null'];
        }
    }

    // Aggiunta delle foreign keys
    if ($fks !== null) {
        foreach ($fks as $fk) {
            $fk_table = $prefix . $fk['tabella'];
            $query .= ", FOREIGN KEY (" . $fk['key1'] . ") REFERENCES " . $fk_table . "(" . SSF_DBT_ID . ")";
        }
    }

    // charset_collate va DENTRO la definizione della tabella per dbDelta
    $query .= ") {$charset_collate};";

    try {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($query);
        return true;
    } catch (\Exception $ex) {
        error_log('[SSF] Errore creazione tabella ' . $tabella . ': ' . $ex->getMessage());
        return false;
    }
}

/**
 * Rimuove una tabella personalizzata dal database WordPress.
 *
 * Esegue un DROP TABLE IF EXISTS. Il nome della tabella viene
 * validato per contenere solo caratteri alfanumerici e underscore
 * (prevenzione SQL Injection).
 *
 * @param string $tabella Nome della tabella (senza prefisso).
 * @return bool True se la tabella è stata rimossa con successo.
 */
function ssf_drop_tabella(string $tabella): bool {

    global $wpdb;

    // Validazione del nome tabella: solo alfanumerici e underscore
    if (! preg_match('/^[a-zA-Z0-9_]+$/', $tabella)) {
        error_log('[SSF] Nome tabella non valido per drop: ' . $tabella);
        return false;
    }

    $table_name = $wpdb->prefix . $tabella;

    try {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange -- Drop intenzionale via API SSF.
        $wpdb->query("DROP TABLE IF EXISTS `{$table_name}`");
        return true;
    } catch (\Exception $ex) {
        error_log('[SSF] Errore drop tabella ' . $tabella . ': ' . $ex->getMessage());
        return false;
    }
}
